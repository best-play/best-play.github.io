<?php

class Admin_UserController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $mapper = new Model_Mapper_User();

        if ($userId = $this->_getParam('ban')) {
            $user = $mapper->fetch($userId);
            if ($user->status == Model_Entity_User::STATUS_ENABLED) {
                $user->status = Model_Entity_User::STATUS_DISABLED;
            } else {
                $user->status = Model_Entity_User::STATUS_ENABLED;
            }
            if ($userId !== Zend_Auth::getInstance()->getIdentity()->user_id) {
                $mapper->save($user);
            }
        }

        if ($userId = $this->_getParam('delete')) {
            if ($userId !== Zend_Auth::getInstance()->getIdentity()->user_id) {
                $mapper->delete($userId);
            }
        }

        $users = $mapper->getAll();
        $this->view->users = $users;  
    }

    public function inviteAction()
    {
        $form = new Form_InviteUser();

        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                // save data
                $userMapper = new Model_Mapper_User();
                $id = Zend_Auth::getInstance()->getIdentity()->user_id;
                $user = $userMapper->fetch($id);

                $invite = new Model_Entity_Invite();
                $invite->from = $id;
                $invite->to = $request->getParam('email');
                $invite->role = $request->getParam('role');
                $invite->generateCode();
                $invite->created = date("Y-m-d H:i:s");

                $inviteMapper = new Model_Mapper_Invite();
                $inviteMapper->save($invite);

                $bodyHtml = $this->view->partial(
                        '_email_templates/invite.phtml',
                        'admin',
                        array('user'=> $user, 'invite' => $invite)
                );
                $bodyText = strip_tags($bodyHtml);

                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText($bodyText);
                $mail->setBodyHtml($bodyHtml);
                $mail->setFrom(COMPANY_MAIL, COMPANY_NAME);
                $mail->addTo($invite->to);
                $mail->setSubject('Приглашение');

                try {
                    $mail->send();
                } catch (Zend_Application_Exception $e) {
                    $this->view->message = $e->getMessage();
                    return;
                }

                $form = null;
                $this->view->message = "Приглашение отправлено";
            }
        }

        $this->view->form = $form;
    }


    public function edituserAction()
    {

        $userId = $this->_getParam('user_id', 0);

        if (!$userId) {
            $this->_helper->redirector->gotoUrl('/admin/user');
        }

        $mapper = new Model_Mapper_User();
        $user = $mapper->fetch($userId);

        $form = new Form_EditProfile();
        $form->setAction('/admin/user/edituser?user_id=' . $userId);

        $form->getElement('first_name')
                ->setAttrib('disabled', true)
                ->setAttrib('readonly', true)
                ->setRequired(false);

        $form->getElement('last_name')
                ->setAttrib('disabled', true)
                ->setAttrib('readonly', true)
                ->setRequired(false);

        $form->getElement('phone')
                ->setAttrib('disabled', true)
                ->setAttrib('readonly', true)
                ->setRequired(false);

        $request = $this->getRequest();
        if ($request->isPost()) {

            if ($form->isValid($request->getPost())) {
                // save data
                $user->branch_id = $form->getValue('branch_id');

                $mapper->save($user);

                $this->_helper->redirector->gotoUrl('/admin/user');
            }
        } else {
            $form->setDefaults($user->toArray());
        }
        $this->view->form = $form;
    }
}