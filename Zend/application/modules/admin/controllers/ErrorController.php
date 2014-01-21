<?php

class Admin_ErrorController extends Zend_Controller_Action
{
    public function noauthAction()
    {
        $this->view->message = "Доступ запрещён";
    }
}

