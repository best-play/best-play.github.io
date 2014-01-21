<?php

class Admin_ClientController extends Zend_Controller_Action
{
    public function indexAction()
    {
       $mapper = new Model_Mapper_Client();
       $userId = Zend_Auth::getInstance()->getIdentity()->user_id;

        if ($clientId = $this->_getParam('delete')) {
            $client = $mapper->fetch($clientId);
                if ($userId != $client->owner_id){  // ��������� ���������
                    $this->_helper->redirector->gotoUrl('/admin/client');
                } elseif ($clientId != $userId) {
                    $mapper->delete($clientId);
                }
        }

        $clients = $mapper->getAllByManager($userId);
        $this->view->clients = $clients;
        //print_r($listClients) ;

    }


    public function editAction()
    {
        $clientId = $this->_getParam('client_id', 0);

        if (!$clientId) {
            $this->_helper->redirector->gotoUrl('/admin/client');
        }

        $mapper = new Model_Mapper_Client();
        $client = $mapper->fetch($clientId);

        $userId = Zend_Auth::getInstance()->getIdentity()->user_id;
        if ($userId != $client->owner_id){
            $this->_helper->redirector->gotoUrl('/admin/client');
        }

        $form = new Form_EditClientProfile();
        $form->setAction('/admin/client/edit?client_id=' . $clientId);

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $client->first_name = $form->getValue('first_name');
                $client->last_name = $form->getValue('last_name');
                $client->middle_name = $form->getValue('middle_name');
                $client->address = $form->getValue('address');
                $client->phone = $form->getValue('phone');
                $client->facility = $form->getValue('facility');

                $mapper->save($client);
                $this->_helper->redirector->gotoUrl('/admin/client');
            }
        } else {
            $form->setDefaults($client->toArray());
        }
        $this->view->form = $form;
    }

    private function saveClient($form){
      $userMapper = new Model_Mapper_User();
      $id = Zend_Auth::getInstance()->getIdentity()->user_id;

      $client = new Model_Entity_Client();
      $client->owner_id = $id;
      $client->first_name = $form->getValue('first_name');
      $client->last_name = $form->getValue('last_name');
      $client->middle_name = $form->getValue('middle_name');
      $client->address = $form->getValue('address');
      $client->phone = $form->getValue('phone');
      $client->facility = $form->getValue('facility');
      $client->created = date("Y-m-d H:i:s");

      $mapperClient = new Model_Mapper_Client();
      $saveClientID = $mapperClient->save($client);

      return $saveClientID;
    }

    public function addAction()
    {
      $form = new Form_EditClientProfile();
      $form->setAction('/admin/client/add');

       if($this->_request->isXmlHttpRequest()){
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $messages = $this->saveClient($form);
            } else {
                $messages = $form->getMessages();
              }
            header('Content-type: application/json');
            echo Zend_Json::encode($messages);
        } else {
            echo $form;
        }
      } else {
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $this->saveClient($form);
                $this->_helper->redirector->gotoUrl('/admin/client');
            }
        }
        $this->view->form = $form;
      }
    }
}