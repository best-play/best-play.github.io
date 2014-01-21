<?php


class Form_MeetAdd extends Form_MeetEdit
{
    public function init()
    {
        $mapper = new Model_Mapper_Client();
        $userId = Zend_Auth::getInstance()->getIdentity()->user_id;

        $client = $mapper->getClByManager($userId);
        $arr = array();

        $count = count($client)-1;
        for ($i = 0; $i <= $count; $i++) {
                $arr[$client[$i]["client_id"]] = $client[$i]["last_name"] ." ". $client[$i]["first_name"] ." ". $client[$i]["middle_name"];
        } // получение ИД и ФИО клиента


       $this->addElement('select','pickClient', array(
            'MultiOptions' => $arr,
            'required'   => false,
            'label'      => 'Клиент:',
       ));

        parent::init();
    }
}