<?php

class Form_RestorePassword extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('password', 'new', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите новый пароль')),
                array('StringLength', false, array(6, 32, 'messages' => 'Размер поля от 6 до 32 символов')),
            ),
            'required'   => true,
            'label'      => 'Новый пароль',
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
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Восстановить',
        ));
    }

    public function comparePassword($value)
    {
        $password = $this->getValue('new');
        return $password == $value;
    }

}


