<?php

class Form_Client extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'inn', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите ИНН')),
                array('StringLength', false, array(10, 10, 'messages' => 'Размер поля 10 символов')),
                array( 'regex', false, array('/[0-9]{10}/', 'messages' => "Разрешены только цифры")),
                new Core_Validate_UniqueField(new Model_Mapper_Client(), 'getByInn'),
            ),
            'required'   => true,
            'label'      => 'ИНН',
        ));

        $this->addElement('text', 'surname', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите фамилию')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Фамилия',
        ));

        $this->addElement('text', 'firstname', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите имя')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Имя',
        ));

        $this->addElement('text', 'parentname', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', false, array('messages' => 'Введите Отчество')),
                array('StringLength', false, array(2, 45, 'messages' => 'Размер поля от 2 до 45 символов')),
            ),
            'required'   => true,
            'label'      => 'Отчество',
        ));

        $this->addElement('select', 'gender', array(
            'multiOptions' => Model_Entity_User::getGenders(),
            'required'   => true,
            'label'      => 'Пол',
        ));

        $element = new ZendX_JQuery_Form_Element_DatePicker('birthday' , array(
            'jQueryParams' => array(
                'dateFormat' => SYSTEM_DATE_JS,
                'changeMonth' => true,
                'changeYear' => true,
                'maxDate' => 'new Date()-9y -1d',
                'yearRange' => '1930:new Date()')));
        $element->setLabel('Дата рождения')
                ->setRequired(true)
                ->addValidator('NotEmpty', true, array(
                    'messages' => 'Введите значение'))
                ->addValidator('Date', true, array(
                    'messages' => array(
                        Zend_Validate_Date::INVALID_DATE => 'Правильный формат даты ' . SYSTEM_DATE_RU,
                        Zend_Validate_Date::FALSEFORMAT => 'Правильный формат даты ' . SYSTEM_DATE_RU),
                    'format'=>SYSTEM_DATE_JS));
        $this->addElement($element);

        $this->addElement('text', 'phone1', array(
            'filters'    => array( 'StringTrim', new Core_Filter_Phone()),
            'validators' => array(
                array( 'regex', false, array('/^(\+38|38|8)?0\d{9}$/', 'messages' => "Неправильный формат телефона"))
            ),
            'required'   => true,
            'label'      => 'Телефон',
        ));

        $this->addElement('text', 'phone2', array(
            'filters'    => array( 'StringTrim', new Core_Filter_Phone()),
            'validators' => array(
                array( 'regex', false, array('/^(\+38|38|8)?0\d{9}$/', 'messages' => "Неправильный формат телефона"))
            ),
            'required'   => false,
            'label'      => 'Дополнительный телефон',
        ));

        $this->addElement('checkbox', 'is_official', array(
            'label'    => 'Официльное трудоустройство'
        ));

        $this->addElement('select', 'rating', array(
            'multiOptions' => Model_Entity_Client::getRating(),
            'validators' => array(
                array('InArray', false, array('haystack' => array_keys(Model_Entity_Client::getRating()),
                               'messages' => 'Неправильное значение')),
                ),
            'required'   => true,
            'label'      => 'Рейтинг УБКИ',
        ));


        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Сохранить',
        ));
    }
}


