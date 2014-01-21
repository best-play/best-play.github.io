<?php

class Form_InviteUser extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/user/invite');

        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите email')),
                array('StringLength', false, array(6, 85, 'messages' => 'Размер поля от 6 до 85 символов')),
                array(new Core_Validate_EmailSimpleMessage(), false)
            ),
            'required'   => true,
            'label'      => 'Email',
            'placeholder'=> 'Введите email адрес',
        ));

        $this->addElement('select', 'role', array(
            'multiOptions' => Model_Entity_User::getRoles(),
            'required'   => true,
            'label'      => 'Роль',
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Отправить приглашение',
            'class'    => 'btn btn-success',
        ));
    }
}

