<?php

class Form_ForgotPassword extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/index/forgot');
        $this->setAttrib('class', 'form-signin');
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));

        $this->addElement('hidden', 'plaintext', array(
            'description' => '<h4 class="form-signin-heading">Восстановление пароля</h2>',
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
            'placeholder'=> 'Введите Email адрес',
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Отправить',
            'class'    => 'btn btn-large btn-primary pull-left',
        ));

        $this->addElement('hidden', 'forgot', array(
            'description' => '<p class="navbar-text pull-right"><a href="/">На главную</a></p>',
            'ignore' => true,
            'decorators' => array(
                array('Description', array('escape'=>false, 'tag'=>'')),
            ),
        ));
    }
}

