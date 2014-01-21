<?php

class Model_Mapper_Invite extends Model_Mapper_Abstract
{
    protected $_table = 'invite';
    protected $_primary = 'invite_id';
    protected $_modelClass = 'Model_Entity_Invite';

    public function getByCode($code)
    {
        $select = $this->_gateway->select()->where('code = ?', $code);
        $row = $this->_gateway->fetchRow($select);

        return  $row ? $this->populate($row->toArray()) : null;
    }
}