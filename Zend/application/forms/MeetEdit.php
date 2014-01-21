<?php

class Form_MeetEdit extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');


        $element = new ZendX_JQuery_Form_Element_DatePicker('date' , array(
                'jQueryParams' => array(
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => true,
                'changeYear' => true,
                'maxDate' => 'new Date()+1y',
                )));
        $element->setLabel('Дата')
                ->setRequired(true)
                ->addValidator('NotEmpty', true, array(
                                'messages' => 'Введите значение'))
                ->addValidator('date', true, array(
                                'messages' => array(
                                    Zend_Validate_Date::INVALID_DATE => 'Правильный формат даты ' . 'ГГГГ-ММ-ДД',
                                    Zend_Validate_Date::FALSEFORMAT => 'Правильный формат даты ' . 'ГГГГ-ММ-ДД'),
                                'format'=>'yy-mm-dd'));
        $this->addElement($element);

        $this->addElement('textarea', 'comment', array(
            'filters'    => array('StringTrim'),
            'required'   => false,
            'rows'       => '4',
            'cols'       => '8',
            'label'      => 'Комментарий',
            'placeholder'=> 'Комментарий',
        ));


        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Сохранить',
            'class'    => 'btn btn-success',
        ));
    }
}
