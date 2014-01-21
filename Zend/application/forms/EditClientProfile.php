<?php

class Form_EditClientProfile extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        //$this->setAction('/admin/client/edit');
	$this->setAttrib('id', 'clientform');

        $this->addElement('text', 'last_name', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите фамилию')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Фамилия',
            'placeholder'=> 'Фамилия',
        ));

        $this->addElement('text', 'first_name', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите имя')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Имя',
            'placeholder'=> 'Имя',
        ));

        $this->addElement('text', 'middle_name', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите отчество')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Отчество',
            'placeholder'=> 'Отчество',
        ));

        $this->addElement('text', 'address', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите адрес')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Адрес',
            'placeholder'=> 'Адрес',
        ));

        $this->addElement('text', 'phone', array(
            'filters'    => array( 'StringTrim', new Core_Filter_Phone()),
            'validators' => array(
                array( 'regex', false, array('/^(\+38|38|8)?0\d{9}$/', 'messages' => "Неправильный формат телефона"))
            ),
            'required'   => false,
            'label'      => 'Телефон',
            'placeholder'=> 'Телефон',
        ));

        $this->addElement('text', 'facility', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите предприятие')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Предприятие',
            'placeholder'=> 'Предприятие',
        ));


        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Сохранить',
            'class'    => 'btn btn-success',
        ));
    }
}
