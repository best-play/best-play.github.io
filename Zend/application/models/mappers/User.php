<?php

class Model_Mapper_User extends Model_Mapper_Abstract
{
    protected $_table = 'user';
    protected $_primary = 'user_id';
    protected $_modelClass = 'Model_Entity_User';

    protected $_enableLog = true;

    public function getByEmailRaw($email)
    {
        $select = $this->_gateway->select()->where('email = ?', $email);
        $row = $this->_gateway->fetchRow($select);

        return  $row;
    }

    /**
     *
     * @param string $email
     * @return Model_Entity_User
     */
    public function getByEmail($email)
    {
        $select = $this->_gateway->select()->where('email = ?', $email);
        $row = $this->_gateway->fetchRow($select);

        return  $row ? $this->populate($row->toArray()) : null;
    }


}
