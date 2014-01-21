<?php

class Service_Acl extends Zend_Acl
{

    /**
     * Single instance of class
     * @var Service_Acl
     */
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __construct()
    {
        $aclConfig = Zend_Registry::get('acl');
        $roles = $aclConfig->acl->roles;
        $resources = $aclConfig->acl->resources;
        $this->_addRoles($roles);
        $this->_addResources($resources);
    }

    protected function __clone()
    {}

    protected function _addRoles($roles)
    {
        foreach ($roles as $name => $parents) {
            if (!$this->hasRole($name)) {
                if (empty($parents)) {
                    $parents = null;
                } else {
                    $parents = explode(',', $parents);
                }
                $this->addRole(new Zend_Acl_Role($name), $parents);
            }
        }
    }

    protected function _addResources($resources)
    {
        foreach ($resources as $permissions => $modules) {

            foreach ($modules as $module => $controllers) {
                if ($module == 'all') {
                    $module = null;
                } else {
                    if (!$this->has($module)) {
                        $this->add(new Zend_Acl_Resource($module));
                    }
                }

                foreach ($controllers as $controller => $role) {
                    if ($controller == 'all') {
                        $controller = null;
                    } 

                    if ($permissions == 'allow') {
                        $this->allow($role, $module, $controller);
                    }
                    if ($permissions == 'deny') {
                        $this->deny($role, $module, $controller);
                    }

                }

            }

        }
    }

}
