<?php

// Define base path obtainable throughout the whole application
defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__)));

set_include_path(get_include_path() . PATH_SEPARATOR .
    dirname(__FILE__) . DIRECTORY_SEPARATOR . 'library'
);

//Check for the existense of the APPLICATION_ENV enviroment variable
if (!($appEnv = getenv('APPLICATION_ENV')))
{
	$appEnv = 'development';
}
define ('APPLICATION_ENV', $appEnv);

require_once 'Core/Tool/Project/Provider/Abstract.php';
require_once 'Core/Tool/MigrationProvider.php';

class Manifest
    implements Zend_Tool_Framework_Manifest_Interface,
        Zend_Tool_Framework_Manifest_ProviderManifestable
{
    public function getProviders()
    {
        return array(
            new Core_Tool_MigrationProvider(),
        );
    }
}
