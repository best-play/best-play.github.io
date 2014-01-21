
<?php

class Model_Mapper_Client extends Model_Mapper_Abstract
{
    protected $_table = 'client';
    protected $_primary = 'client_id';
    protected $_modelClass = 'Model_Entity_Client';

    protected $_enableLog = true;


    public function getAllByManager($userId)
    {
        $select = $this->_gateway->select()->where('owner_id = ?', $userId);
        $row = $this->_gateway->fetchAll($select);

        return  $row->toArray();
    }

    public function getClByManager($userId)
    {
        $select = $this->_gateway->select()->from($this->_gateway, array('client_id','first_name','last_name','middle_name'))->where('owner_id = ?', $userId);
        $row = $this->_gateway->fetchAll($select);

        return  $row->toArray();
    }

}