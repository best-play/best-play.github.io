<?php

class Admin_MeetController extends Zend_Controller_Action
{
    public function indexAction()
    {
       $clientId = $this->_getParam('client_id', 0);
       if (!$clientId) {
           $this->_helper->redirector->gotoUrl('/admin/client');
       } // проверка получения client_id


       $mapper_client = new Model_Mapper_Client();
       $client = $mapper_client->fetch($clientId);
       $userId = Zend_Auth::getInstance()->getIdentity()->user_id;
       if ($userId != $client->owner_id){
           $this->_helper->redirector->gotoUrl('/admin/client');
       } // проверка владельца клиента


       $mapper = new Model_Mapper_Meet();
       $meets = $mapper->getAllMeets($clientId);
       $this->view->meets = $meets; // получение и передача во вью всех встреч конкретного клиента
       $this->view->client = $client;

    }

    public function addAction()
    {
        $form = new Form_MeetAdd();
        $form->setAction('/admin/meet/add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $meet = new Model_Entity_Meet();
                $meet->client_id = $form->getValue('pickClient');
                $meet->date = $form->getValue('date');
                $meet->comment = $form->getValue('comment');

                $mapperMeet = new Model_Mapper_Meet();
                $mapperMeet->save($meet);
                $this->_helper->redirector->gotoUrl('/admin/meet?client_id='.$form->getValue('pickClient'));
            }
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
       $meetId = $this->_getParam('meet_id', 0);
       if (!$meetId) {
           $this->_helper->redirector->gotoUrl('/admin/client');
       } // проверка получения meet_id


       $mapper = new Model_Mapper_Meet();
       $meet = $mapper->fetch($meetId);
       $clientId = $meet->client_id;  // получаем ИД клиента


       $mapper_client = new Model_Mapper_Client();
       $client = $mapper_client->fetch($clientId);
       $userId = Zend_Auth::getInstance()->getIdentity()->user_id;
       if ($userId != $client->owner_id){
           $this->_helper->redirector->gotoUrl('/admin/client');
       } // проверка владельца клиента


        $form = new Form_MeetEdit();
        $form->setAction('/admin/meet/edit?meet_id=' . $meetId);

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $meet->date = $form->getValue('date');
                $meet->comment = $form->getValue('comment');

                $mapper->save($meet);
                $this->_helper->redirector->gotoUrl('/admin/meet?client_id=' . $clientId);
            }
        } else {
            $form->setDefaults($meet->toArray());
        }
        $this->view->form = $form;
    }

    public function delAction()
    {
       $meetId = $this->_getParam('delete');
       $mapper = new Model_Mapper_Meet();
       $meet = $mapper->fetch($meetId);
       $clientId = $meet->client_id;  // получаем ИД клиента

       $mapper_client = new Model_Mapper_Client();
       $client = $mapper_client->fetch($clientId);
       $userId = Zend_Auth::getInstance()->getIdentity()->user_id;
       if ($userId != $client->owner_id){
           $this->_helper->redirector->gotoUrl('/admin/client');
       } // проверка владельца клиента


        if ($meetId) {
                $mapper->delete($meetId);
                $this->_helper->redirector->gotoUrl('/admin/meet?client_id=' . $clientId);
        }   // удаляем запись
    }
}