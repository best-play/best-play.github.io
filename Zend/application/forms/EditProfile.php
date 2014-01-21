<?php

class Form_EditProfile extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/profile/edit');

        $this->addElement('text', 'first_name', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите имя')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Имя',
            'placeholder'=> 'Ваше имя',
        ));

        $this->addElement('text', 'last_name', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите фамилию')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Фамилия',
            'placeholder'=> 'Ваша фамилия',
        ));

        $this->addElement('text', 'phone', array(
            'filters'    => array( 'StringTrim', new Core_Filter_Phone()),
            'validators' => array(
                array( 'regex', false, array('/^(\+38|38|8)?0\d{9}$/', 'messages' => "Неправильный формат телефона"))
            ),
            'required'   => false,
            'label'      => 'Телефон',
            'placeholder'=> 'Ваш номер телефона',
        ));


        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Сохранить',
            'class'    => 'btn btn-success',
        ));
    }
}
