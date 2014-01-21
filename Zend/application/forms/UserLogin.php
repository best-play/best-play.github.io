<?php

class Form_UserLogin extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/index/authenticate');
        $this->setAttrib('class', 'form-signin');
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));


        $this->addElement('hidden', 'plaintext', array(
            'description' => '<h2 class="form-signin-heading">Форма входа</h2>',
            'ignore' => true,
            'decorators' => array(
                array('Description', array('escape'=>false, 'tag'=>'')),
            ),
        ));

        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите email')),
                array('StringLength', false, array(6, 85, 'messages' => 'Размер поля от 6 до 85 символов')),
                array(new Core_Validate_EmailSimpleMessage(), false)
            ),
            'required'   => true,
            'class'      => 'input-block-level',
            'placeholder'=> 'Email адрес',
        ));
        
        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите пароль')),
                array('StringLength', false, array(6, 32, 'messages' => 'Размер поля от 6 до 32 символов'))
            ),
            'required'   => true,
            'class'      => 'input-block-level',
            'placeholder'=> 'Пароль',
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Войти',
            'class'    => 'btn btn-large btn-primary pull-left',
        ));

        $this->addElement('hidden', 'forgot', array(
            'description' => '<p class="navbar-text pull-right"><a href="/admin/index/forgot">Забыли пароль?</a></p>',
            'ignore' => true,
            'decorators' => array(
                array('Description', array('escape'=>false, 'tag'=>'')),
            ),
        ));
    }
}
