<?php 

abstract class Model_Entity_Abstract 
{
    protected $_data = array();

    public function __construct(array $data = null, $prefix = null) {
        $this->setData($data, $prefix);
    }

    public function setData(array $data = null, $prefix = null) {
        if (!is_null($data)) {
            if ($prefix) {
                $prefixLength = strlen($prefix);
                foreach ($data as $key => $field) {
                    $this->{substr($key, $prefixLength)} = $field;
                }
            } else {
                foreach ($data as $name => $value) {
                    $this->{$name} = $value;
                }
            }
        }
    }

    public function toArrayNotNull()
    {
        $data = $this->_data;
        foreach ($this->_data as $k => $val) {
            if ($val === null) {
                unset($data[$k]);
            }
        }

        return $data;
    }

    public function __toString() {
        $result = '';

        foreach ($this->_data as $k => $val) {
            $result .= $k . '="' . $val . '" ';
        }

        return $result;
    }

    public function toArray()
    {
        return $this->_data;
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->_data)) {
            $this->_data[$name] = $value;
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
    }

    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    public function __unset($name)
    {
        if (isset($this->_data[$name])) {
            unset($this->_data[$name]);
        }
    }

    public function getMapForJoin($prefix, $tableShort)
    {
        $result = array();

        foreach ($this->_data as $key => $val) {
            $result[$prefix . $key] = $tableShort . $key;
        }

        return $result;
    }
}
