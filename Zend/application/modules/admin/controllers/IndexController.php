<?php

class Admin_IndexController extends Zend_Controller_Action
{

    protected $_authService;


    public function init()
    {
        $this->_authService = new Service_Authentication();
    }

    public function indexAction()
    {
        if($this->_authService->getIdentity())
        {
            $redirector = $this->_helper->getHelper('Redirector');
            $redirector->gotoSimple('index', 'main', 'admin');
        }

        $this->view->loginForm = new Form_UserLogin();
    }



    public function authenticateAction()
    {
        sleep(1); // prevent brute force

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('index');
        }

        // Validate
        $form = $this->view->loginForm = new Form_UserLogin();

        if (!$form->isValid($request->getPost())) {
            return $this->render('index');
        }

        if (false == $this->_authService->authenticate($form->getValues())) {
            $this->view->message = 'Не удалось найти пользователя с такими значениями email и пароля';
            return $this->render('index');
        }

//        return $this->_helper->redirector('index');
        $redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoSimple('index', 'main', 'admin');
    }


    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index');
    }


    public function registrationAction()
    {
        $code = $this->_request->getParam('code', 0);

        $inviteMapper = new Model_Mapper_Invite();
        $invite = $inviteMapper->getByCode($code);

        // is invite correct?
        if (!$invite) {
            $this->view->error = 'Неправильный регистрационный код.<br/>
                Убедитесь, что Вы правильно скопировали ссылку из письма.';
            $this->view->message = 'Не получается зарегистрироваться?<br/>
                Свяжитесь с администрацией сайта: <strong>' . COMPANY_MAIL . '</strong>';

            return;
        }

        $userMapper = new Model_Mapper_User();
        $user = $userMapper->getByEmail($invite->to);

        // is invited user already exists?
        if ($user) {
            $this->view->error = 'Пользователь с адресом <h2>' . $user->email . '</h2> уже зарегистрирован в системе<br/>';
            $this->view->message = ' - Если Вы забыли пароль,<br/>
                - Если Вы не регистрировались на этом сайте, но это Ваш почтовый ящик и кто-то зарегистрировался вместо Вас,<br/><br/>
                Вы можете перейти на
                <a href="/admin/index/forgot">страницу восстановления пароля</a>';

            return;
        }

        // is invite was used and registered user was removed
        if ($invite->status == Model_Entity_Invite::STATUS_USED) {
            $this->view->error = 'Это приглашение уже было использовано ранее';
            $this->view->message = 'Если Вы зарегистрированы, но забыли пароль, перейдите на
                <a href="/admin/index/forgot">страницу восстановления пароля</a>';

            return;
        }

        if ($invite->isExpired()) {
            $this->view->error = 'Время действия этого приглашения истекло';
            $this->view->message = 'Если Вы зарегистрированы, но забыли пароль, перейдите на
                <a href="/admin/index/forgot">страницу восстановления пароля</a>';

            return;
        }

        $form = new Form_Registration();
        $form->setAction('/admin/index/registration?code=' . $code);
        $this->view->form = $form;

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return;
        }

        if ($form->isValid($request->getPost())) {
            // saving new user
            $newUser = new Model_Entity_User();
            $newUser->first_name = $form->getValue('first_name');
            $newUser->last_name = $form->getValue('last_name');
            $newUser->phone = $form->getValue('phone');
            $newUser->gender = $form->getValue('gender');
            $newUser->branch_id = $form->getValue('branch_id');
            $newUser->email = $invite->to;
            $newUser->role = $invite->role;
            $newUser->created = date('Y-m-d H:i:s');
            $newUser->setPassword($form->getValue('password'));
            $userMapper->save($newUser);

            // mark invite as used
            $invite->status = Model_Entity_Invite::STATUS_USED;
            $inviteMapper->save($invite);

            $this->view->form = null;
            $this->view->message = 'Поздравляем. <br/> Вы зарегистрированы и можете
                <a href="/admin">войти в систему</a>';
        }
    }

        // @TODO testing
    public function forgotAction()
    {
        $this->view->form = $form = new Form_ForgotPassword();

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return;
        }

        if (!$form->isValid($request->getPost())) {
            return;
        }

        $userMapper = new Model_Mapper_User();
        $userModel = $userMapper->getByEmail($form->getValue('email'));

        if (!$userModel) {
            $this->view->error = 'Пользователя с таким email не существует';

            return;
        }

        if ($userModel->status != Model_Entity_User::STATUS_ENABLED) {
            $this->view->error = 'Пользователь с таким email заблокирован';
            $this->view->message = 'Свяжитесь с администрацией сайта: <strong>' . COMPANY_MAIL . '</strong>, чтобы узнать причину';

            return;
        }

        $restoreMapper = new Model_Mapper_RestorePassword();
        $existedRestore = $restoreMapper->getLastByEmail($form->getValue('email'));

        if ($existedRestore 
                && $existedRestore->status == Model_Entity_RestorePassword::STATUS_NEW
                && !$existedRestore->isItPossibleToRegenerate()) {
            $this->view->error = 'Восстановление пароля для пользователя с таким email уже запрашивалось';
            $this->view->message = 'Следующий запрос можно будте сделать после '
                                    . $existedRestore->getNextGenerateTime()->format('H:i d-m-Y');

            return;
        }


        // all Ok, create restore entity and send email
        $restore = new Model_Entity_RestorePassword();

        $restore->whom = $form->getValue('email');
        $restore->generateCode();
        $restore->created = date('Y-m-d H:i:s');

        $restoreMapper->save($restore);

        $bodyHtml = $this->view->partial(
                '_email_templates/restore.phtml',
                'admin',
                array('restore' => $restore, 'user' => $userModel)
        );
        $bodyText = strip_tags($bodyHtml);

        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText($bodyText);
        $mail->setBodyHtml($bodyHtml);
        $mail->setFrom(COMPANY_MAIL, COMPANY_NAME);
        $mail->addTo($restore->whom);
        $mail->setSubject('Восстановление пароля');

        try {
            $mail->send();
        } catch (Zend_Application_Exception $e) {
            $this->view->message = $e->getMessage();
            return;
        }

        $this->view->form = null;
        $this->view->message = 'Вам отправлено письмо со ссылкой для восстановления пароля, которой можно воспользоваться до '. $restore->getExpiredDate()->format('H:i d-m-Y');
    }

    public function restoreAction()
    {
        $code = $this->_request->getParam('code', 0);

        $restoreMapper = new Model_Mapper_RestorePassword();
        $restore = $restoreMapper->getByCode($code);

        // is restore record correct?
        if (!$restore) {
            $this->view->error = 'Неправильный регистрационный код.<br/>
                Убедитесь, что Вы правильно скопировали ссылку из письма.';
            $this->view->message = 'Не получается восстановить пароль?<br/>
                Свяжитесь с администрацией сайта: <strong>' . COMPANY_MAIL . '</strong>';

            return;
        }

        $userMapper = new Model_Mapper_User();
        $user = $userMapper->getByEmail($restore->whom);

        // is user exists?
        if (!$user) {
            $this->view->error = 'Пользователь с адресом <h2>' . $restore->whom. 
                    '</h2> больше не существует в системе<br/>';
            $this->view->message = 'Свяжитесь с администрацией сайта: <strong>' . COMPANY_MAIL . '</strong>';

            return;
        }

        if ($user->status != Model_Entity_User::STATUS_ENABLED) {
            $this->view->error = 'Пользователь с адресом <h2>' . $restore->whom. '</h2> заблокирован';
            $this->view->message = 'Свяжитесь с администрацией сайта: <strong>' . COMPANY_MAIL . '</strong>, чтобы узнать причину';

            return;
        }

        // is invite was used and registered user was removed
        if ($restore->status == Model_Entity_RestorePassword::STATUS_USED) {
            $this->view->error = 'Эта ссылка восстановления пароля была использовано ранее';
            $this->view->message = ' <a href="/admin/index/forgot">Запросите пароль</a> ещё раз';

            return;
        }

        if ($restore->isExpired()) {
            $this->view->error = 'Время действия этого приглашения истекло';
            $this->view->message = ' <a href="/admin/index/forgot">Запросите пароль</a> ещё раз';

            return;
        }

        $form = new Form_RestorePassword();
        $form->setAction('/admin/index/restore?code=' . $code);
        $this->view->form = $form;

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return;
        }

        if (!$form->isValid($request->getPost())) {
            return;
        }

        // seems all Ok, update entities
        $restore->status = Model_Entity_RestorePassword::STATUS_USED;
        $restoreMapper->save($restore);

        $user->setPassword($form->getValue('new'));
        $userMapper->save($user);

        $this->view->form = null;
        $this->view->message = 'Пароль был успешно изменён.<br/>Вы можете
            <a href="/admin">войти в систему</a>';
    }

//    public function createAction()
//    {
//        $data['email'] = 'vodolazky@gmail.com';
//        $data['first_name'] = 'Yuri';
//        $data['last_name'] = 'vod';
//        $data['created'] = date('Y-m-d H:i:s');
//        $data['role'] = 'ADMIN';
//
//        $model = new Model_Entity_User($data);
//        $model->setPassword('123qwe');
//
//        $mapper = new Model_Mapper_User();
//        $mapper->save($model);
//    }


    public function ghostLoginAction()
    {
        $userId = $this->_getParam('user_id', 0);

        if (isset(Zend_Auth::getInstance()->getIdentity()->ghost_id)) {
            $this->view->message = 'Вы уже используйте гост-логин. Если Вы хотите войти под другим пользователем, сначала вернитесь к нормальному логину.';
            return;
        }

        if (!$userId) {
            $this->_helper->redirector->gotoUrl('/admin/user');
        }

        $mapper = new Model_Mapper_User();
        $user = $mapper->fetch($userId);

        if ($user) {
            if ($user->isDisabled()) {
                $this->view->message = 'Пользователь заблокирован. Сначала разблокируйте.';
            } else {
                $user = $user->toArray();
                $user['ghost_id'] = Zend_Auth::getInstance()->getIdentity()->user_id;

                Zend_Auth::getInstance()->getStorage()->write((object)$user);
                $this->_helper->redirector->gotoUrl('/admin');
            }
        } else  {
            $this->view->message = 'Пользователь не найден';
        }
    }

    public function ghostLogoutAction()
    {
        if (isset(Zend_Auth::getInstance()->getIdentity()->ghost_id)) {
            $mapper = new Model_Mapper_User();
            $user = $mapper->fetch(Zend_Auth::getInstance()->getIdentity()->ghost_id);

            if ($user) {
                Zend_Auth::getInstance()->getStorage()->write((object)$user);
                $this->_helper->redirector->gotoUrl('/admin/user');
            }
        }

        $this->_helper->redirector->gotoUrl('/admin');
    }

}

