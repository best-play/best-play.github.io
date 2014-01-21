<?php

class Admin_MainController extends Zend_Controller_Action
{
    public function indexAction()
    {

    }

    public function imguploadAction()
    {
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        // files storage folder
        $local = '/upload/redactor/';
        $dir = PUBLIC_PATH . $local;
        $_FILES['file']['type'] = strtolower($_FILES['file']['type']);
        if ($_FILES['file']['type'] == 'image/png'
        || $_FILES['file']['type'] == 'image/jpg'
        || $_FILES['file']['type'] == 'image/gif'
        || $_FILES['file']['type'] == 'image/jpeg'
        || $_FILES['file']['type'] == 'image/pjpeg')
        {
            // setting file's mysterious name
            $file = md5(date('YmdHis')).'.jpg';

            // copying
            move_uploaded_file($_FILES['file']['tmp_name'], $dir . $file);

            // displaying file
            $array = array(
                'filelink' => $local . $file
            );

            echo stripslashes(json_encode($array));
        }
    }
}