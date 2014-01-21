<?php

class Model_Mapper_Meet extends Model_Mapper_Abstract
{
    protected $_table = 'meet';
    protected $_primary = 'meet_id';
    protected $_modelClass = 'Model_Entity_Meet';

    protected $_enableLog = true;



    public function getAllMeets($clientId)
    {
        $select = $this->_gateway->select()->where('client_id = ?', $clientId);
        $row = $this->_gateway->fetchAll($select);

        return  $row->toArray();
    }

}