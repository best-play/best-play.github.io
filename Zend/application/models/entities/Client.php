<?php

class Model_Entity_Client extends Model_Entity_Abstract
{
    protected $_data = array(
        'client_id' => null,
        'owner_id' => null,
        'first_name' => null,
        'last_name' => null,
        'middle_name' => null,
        'address' => null,
        'phone' => null,
        'facility' => null,
        'created' => null,
    );


    public function getFullName()
    {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name;
    }


}
