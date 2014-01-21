<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    // view settings
    protected function _initViewSettings()
    {
        if(isset($_SERVER['HTTP_HOST']) && substr($_SERVER['HTTP_HOST'],0,3)=='www') {
            $url = ($_SERVER['HTTPS']?'https://':'http://').substr($_SERVER['HTTP_HOST'],4).$_SERVER["REQUEST_URI"];
            header("Location: $url",true,301);
        }
        
        $this->bootstrap('view');

        $this->_view = $this->getResource('view');

        // set encoding and doctype
        $this->_view->setEncoding('UTF-8');
        $this->_view->doctype('XHTML1_STRICT');

        // set the content type and language
        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'ru-RU');

        // set css links and a special import for the accessibility styles
        $this->_view->headLink()->appendStylesheet('/css/bootstrap.css');


        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        // init jQuery & UI
        ZendX_JQuery::enableView($view);
        $view->jQuery()
            ->enable()
            ->uiEnable()
            ->setLocalPath('/js/jquery-1.7.2.min.js')
            ->addStylesheet('/js/jquery-ui-1.8.21.custom.css')
            ->setUiLocalPath('/js/jquery-ui-1.8.21.custom.min.js')
            ->addJavascriptFile('/js/jquery.ui.datepicker-ru.js');
    }

    protected function _initAutoload()
    {
        new Zend_Loader_Autoloader_Resource(array(
            'basePath' => APPLICATION_PATH,
            'namespace' => '',
            'resourceTypes' => array(
                'form'          => array(
                    'namespace' => 'Form',
                    'path'      => 'forms',
                ),
                'model'         => array(
                    'namespace' => 'Model',
                    'path'      => 'models',
                ),
                'plugin'        => array(
                    'namespace' => 'Plugin',
                    'path'      => 'plugins',
                ),
                'service'       => array(
                    'namespace' => 'Service',
                    'path'      => 'services',
                ),
                'modelentity' => array(
                    'namespace' => 'Model_Entity',
                    'path'      => 'models/entities'
                ),
                'modelmapper' => array(
                    'namespace' => 'Model_Mapper',
                    'path'      => 'models/mappers'
                ),
            )
        ));
        
        return;
    }


    protected function _initLoadAclIni()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/acl.ini');
        Zend_Registry::set('acl', $config);
    }

    protected function _initAclControllerPlugin()
    {
        $this->bootstrap('frontcontroller');
        $this->bootstrap('loadAclIni');

        $front = Zend_Controller_Front::getInstance();

        $aclPlugin = new Core_Controller_Plugin_Acl(Service_Acl::getInstance());

        $front->registerPlugin($aclPlugin);
    }

    protected function _initNavigation()
    {

        $this->bootstrap("frontController");
        $this->frontController->registerPlugin(new Core_Controller_Plugin_Navigation);
    }

    protected function _initSetConstants()
    {
        $arrConstants = $this->getOption("constant");
        foreach( $arrConstants as $strName => $strValue ){
            if( ! defined( $strName ) ) {
                define( $strName, $strValue );
            }
        }
    }
}

