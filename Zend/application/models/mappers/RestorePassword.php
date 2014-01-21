<?php

class Model_Mapper_RestorePassword extends Model_Mapper_Abstract
{
    protected $_table = 'restore_password';
    protected $_primary = 'restore_password_id';
    protected $_modelClass = 'Model_Entity_RestorePassword';

    public function getByCode($code)
    {
        $select = $this->_gateway->select()->where('code = ?', $code);
        $row = $this->_gateway->fetchRow($select);

        return  $row ? $this->populate($row->toArray()) : null;
    }

    public function getLastByEmail($email)
    {
        $select = $this->_gateway->select()
                                ->where('whom = ?', $email)
                                ->order('created DESC');

        $row = $this->_gateway->fetchRow($select);

        return  $row ? $this->populate($row->toArray()) : null;
    }
}
