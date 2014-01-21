<?php

class Zend_View_Helper_FormRedactor extends Zend_View_Helper_FormElement
{

    public function formRedactor($name = null, $value = null, $attribs = null)
    {
        $redactor_uri = "/js/redactor/";

        $this->view->headLink()->appendStylesheet("{$redactor_uri}css/redactor.css");
        $this->view->headScript()->appendFile("{$redactor_uri}redactor.js");
        $this->view->headScript()->appendFile("{$redactor_uri}langs/ru.js");


        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        //init redactor
        $js = "
            $(document).ready(function() {
                $('#" . $this->view->escape($id) . "').redactor({ imageUpload: '/admin/main/imgupload', lang: 'ru' })
            })
            ";
        $this->view->headScript()->appendScript($js);

        // build the element
        $xhtml = '<textarea name="' . $this->view->escape($name) . '"'
            . ' id="' . $this->view->escape($id) . '"'
            . $this->_htmlAttribs($attribs) . '>'
            . $this->view->escape($value) . '</textarea>';

        return $xhtml;
    }
}
