<?php
// Define base path obtainable throughout the whole application
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__)));
defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'application');
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
// Define server name
defined('SERVER_NAME')
    || define('SERVER_NAME', ($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : 'vivo'));


set_include_path(get_include_path() . PATH_SEPARATOR .
    BASE_PATH . DIRECTORY_SEPARATOR . 'library'
);

require_once 'Zend/Application.php';
require_once 'Zend/Config/Ini.php';
require_once 'Zend/Log.php';

$config = getConfig();
//var_dump($config);

// Create application
$application = new Zend_Application(APPLICATION_ENV, $config);
$application->bootstrap()->run();


/**
 * Read config options from config file
 * Merge development config and standart config file
 * 
 * @return Zend_Config_Ini
 */
function getConfig()
{
    $configPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs';
    $config = new Zend_Config_Ini($configPath . '/application.ini', APPLICATION_ENV, true);
    if ((!defined('APPLICATION_ENV') || 'development' == APPLICATION_ENV)
         && file_exists($configPath . '/application.development.ini')) {
        $configOther = new Zend_Config_Ini($configPath . '/application.development.ini', APPLICATION_ENV);
        $config->merge($configOther);
    }

    return $config;
}
