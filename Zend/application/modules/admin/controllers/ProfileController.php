<?php

class Admin_ProfileController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $mapper = new Model_Mapper_User();
        $id = Zend_Auth::getInstance()->getIdentity()->user_id;
        $this->view->user = $mapper->fetch($id);
    }

    public function editAction()
    {
        $form = new Form_EditProfile();

        $request = $this->getRequest();

        $mapper = new Model_Mapper_User();
        $id = Zend_Auth::getInstance()->getIdentity()->user_id;
        $user = $mapper->fetch($id);

        if ($request->isPost()) {

            if ($form->isValid($request->getPost())) {
                // save data
                $user->first_name = $form->getValue('first_name');
                $user->last_name = $form->getValue('last_name');
                $user->phone = $form->getValue('phone');



                $mapper->save($user);

                $form = null;
                $this->view->message = "Профиль сохранён";
            }
        } else {
            $form->setDefaults($user->toArray());
        }
        $this->view->form = $form;
    }

    public function changepasswordAction()
    {
        $form = new Form_ChangePassword();

        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                // save data
                $mapper = new Model_Mapper_User();
                $id = Zend_Auth::getInstance()->getIdentity()->user_id;
                $user = $mapper->fetch($id);
                $user->setPassword($request->getParam('new'));
                $mapper->save($user);
                Zend_Auth::getInstance()->getIdentity()->password = $user->password;

                $form = null;
                $this->view->message = "Пароль изменён";
            }
        }

        $this->view->form = $form;
    }
}
