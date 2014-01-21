<?php

abstract class Model_Mapper_Abstract 
{
    /**
     *
     * @var Zend_Db_Table
     */
    protected $_gateway = null;
    protected $_table = null;
    protected $_primary = null;
    protected $_modelClass = null;


    public function __construct()
    {
        if ($this->_table !== null) {
            $this->_gateway = new Zend_Db_Table(array(
            'name'      => $this->_table, 
            'primary'   => $this->_primary));
        }
    }

    public function getTable()
    {
        return $this->_table;
    }
    
    public function getPrimary()
    {
        return $this->_primary;
    }

    public function getModelClass()
    {
        return $this->_modelClass;
    }


    public function fetch($id)
    {
        $row = $this->_gateway->find($id);
        $plainRow = $row->toArray();

        return $this->populate($plainRow[0]);
    }

    public function getAll()
    {
        $select = Zend_Db_Table::getDefaultAdapter()->select();

        $select->from($this->_table);
        $rows = $select->query()->fetchAll();

        $models = array();
        foreach ($rows as $row) {
           $models[] = $this->populate($row);
        }

        return $models;
    }

    public function getAllAsIndexedArray()
    {
        $models = $this->getAll();

        $result = array();

        foreach($models  as $model) {
            $result[$model->{$this->_primary}] = $model;
        }

        return $result;
    }

    public function populate($data)
    {
        $model = new $this->_modelClass($data);

        return $model;
    }

    public function save(Model_Entity_Abstract $model)
    {
        $result = null;

        if ($model->{$this->_primary} !== null) {
            $where = $this->_gateway->getAdapter()->quoteInto($this->_primary . ' = ?', $model->{$this->_primary});
            $this->_gateway->update($model->toArrayNotNull(), $where);
        } else {
            $row = $this->_gateway->createRow($model->toArrayNotNull());
            $id = $row->save();
            $model->{$this->_primary} = $id;
            $result = $id;
        }

        return $result;
    }

    public function delete($model)
    {
        if ($model instanceof $this->_modelClass) {
            $id = $model->{$this->_primary};
        } else {
            $id = $model;
        }


        $where = $this->_gateway->getAdapter()->quoteInto($this->_primary . ' = ?', $id);

        $this->_gateway->delete($where);
    }
}
