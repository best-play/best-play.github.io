<?php

class Form_Registration extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'first_name', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите имя')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Имя',
        ));

        $this->addElement('text', 'last_name', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите фамилию')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Фамилия',
        ));

        $this->addElement('select', 'gender', array(
            'multiOptions' => Model_Entity_User::getGenders(),
            'required'   => true,
            'label'      => 'Пол',
        ));


        $this->addElement('text', 'phone', array(
            'filters'    => array( 'StringTrim', new Core_Filter_Phone()),
            'validators' => array(
                array( 'regex', false, array('/^(\+38|38|8)?0\d{9}$/', 'messages' => "Неправильный формат телефона"))
            ),
            'required'   => false,
            'label'      => 'Телефон',
        ));


        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите пароль')),
                array('StringLength', false, array(6, 32, 'messages' => 'Размер поля от 6 до 32 символов')),
            ),
            'required'   => true,
            'label'      => 'Пароль',
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
            'label'    => 'Зарегистрироваться',
        ));
    }

    public function comparePassword($value)
    {
        $password = $this->getValue('password');
        return $password == $value;
    }
}

