<?php

class Form_ChangePassword extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/profile/changepassword');

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите пароль')),
                array('StringLength', false, array(6, 32, 'messages' => 'Размер поля от 6 до 32 символов')),
                array(new Core_Validate_CheckPassword())
            ),
            'required'   => true,
            'label'      => 'Текущий пароль',
            'placeholder'=> 'Ваш текущий пароль',
        ));

        $this->addElement('password', 'new', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите новый пароль')),
                array('StringLength', false, array(6, 32, 'messages' => 'Размер поля от 6 до 32 символов')),
            ),
            'required'   => true,
            'label'      => 'Новый пароль',
            'placeholder'=> 'Ваш новый пароль',
        ));

        $this->addElement('password', 'confirm_new', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Повторите новый пароль')),
                array('StringLength', false, array(6, 32, 'messages' => 'Размер поля от 6 до 32 символов')),
                array( 'Callback', false,
                    array(
                        'messages' => array(
                            Zend_Validate_Callback::INVALID_VALUE => 'Ваши пароли не совпадают',
                            Zend_Validate_Callback::INVALID_CALLBACK => 'Подтвердите пароль',
                        ),
                        'callback' => array($this, 'comparePassword')))
            ),
            'required'   => true,
            'label'      => 'Повторите новый пароль',
            'placeholder'=> 'Повторите новый пароль',
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Сохранить',
            'class'    => 'btn btn-success', 
        ));
    }

    public function comparePassword($value)
    {
        $password = $this->getValue('new');
        return $password == $value;
    }

}

