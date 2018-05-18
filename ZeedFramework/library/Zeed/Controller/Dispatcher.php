<?php
/**
 * Playcool Project
 *
 * LICENSE
 *
 * http://www.playcool.com/license/ice
 *
 * @category   ICE
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Jun 3, 2009
 * @version    SVN: $Id: Dispatcher.php 12316 2011-11-18 06:56:25Z xsharp $
 */

defined('ZEED_CONTROLLER_SUFFIX') || define('ZEED_CONTROLLER_SUFFIX', 'Controller');

class Zeed_Controller_Dispatcher implements Zeed_Controller_Dispatcher_Interface
{
    
    /**
     * Controller directory(ies)
     * @var array
     */
    protected $_controllerDirectory = array();
    
    /**
     * Default module
     * @var string
     */
    protected $_defaultModule = 'default';
    protected $_defaultController = 'index';
    protected $_defaultAction = 'index';
    
    /**
     * Current module (formatted)
     * @var string
     */
    protected $_curModule;
    
    /**
     * Current dispatchable directory
     * @var string
     */
    protected $_curDirectory;
    
    /**
     * @param Zeed_Controller_Request $resquest
     */
    public function dispatch(Zeed_Controller_Request $resquest)
    {
        $action = $this->getControllerClass($resquest);
        
        if (null == ($method = $resquest->getActionName())) {
            $method = $this->_defaultAction;
        }
        $result = $action->$method();
        
        $actionConfiguration = $action->getConfig();
        if (is_string($result) && isset($actionConfiguration[$result])) {
            $resultClass = $action->getResultType($actionConfiguration[$result]['type']);
        } else {
            $resultClass = $action->getResultType('default');
        }
        
        if ($resultClass) {
            $processor = new $resultClass();
            $processor->process($result, $action);
        }
    }
    
    /**
     * Get controller class.
     *
     * @param Zeed_Controller_Request $resquest
     * @return Zeed_Controller_Action
     */
    protected function getControllerClass(Zeed_Controller_Request $resquest)
    {
        if ('' == ($module = $resquest->getModuleName())) {
            $module = $this->_defaultModule;
        }
        if ('' != ($controllerName = $resquest->getControllerName())) {
            $controllerNameWithoutSuffix = ucfirst($controllerName);
        } else {
            $controllerNameWithoutSuffix = ucfirst($this->getDefaultControllerName());
        }
        $controllerName = $controllerNameWithoutSuffix . ZEED_CONTROLLER_SUFFIX;
        
        // Search controller path.
        $controllerDirs = $this->getControllerDirectory();
        $controllerFolder = str_replace('\\', '/', $controllerDirs[$module]);
        $this->_curDirectory = $controllerFolder;
        $this->_curModule = $module;
        
        // The module path.
        define('ZEED_PATH_MODULE', str_replace('\\', '/', realpath($controllerFolder . '/../')) . '/');
        
        // path of admin or front
        $admin_or_front = substr(strrchr($controllerFolder, '/'), 1) . '/';
        define('ZEED_PATH_ADMIN_OR_FRONT', $admin_or_front == 'controllers/' ? '' : $admin_or_front); // temp update at 2013-07-14
        
        /* add at 2013-10-05 by Cyrano */
        // The controller path.
        define('ZEED_PATH_CONTROLLER', $resquest->getControllerName());
        
        // The action path.
        define('ZEED_PATH_ACTION', $resquest->getActionName());
        /* add at 2013-10-05 by Cyrano @end */
        
        /**
         * @todo 加入权限检测(需考虑Shell下的执行)
         */
        if (! defined('ZEED_IN_CONSOLE')) {
            $permissionHandleClass = Zeed_Config::loadGroup('access.__PERMISSION_CLASS__');
            if (! is_null($permissionHandleClass)) {
                $permissionHandle = new $permissionHandleClass($resquest);
                $permissionHandle->compare();
            }
        }
        
        // Get controller class name.
        if ($module != $this->getDefaultModule()) {
            $controllerClassFinal = $module . '_' . $controllerName;
            $_autoCreateClass = true;
        } else {
            $controllerClassFinal = $controllerName;
            $_autoCreateClass = false;
        }
        
        // Add models dir to include path.
        $includePaths = array();
        if (is_dir(ZEED_PATH_MODULE . 'libraries/')) {
            $includePaths['library'] = ZEED_PATH_MODULE . 'libraries/';
        }
        if (is_dir(ZEED_PATH_MODULE . 'models/')) {
            $includePaths['model'] = ZEED_PATH_MODULE . 'models/';
        }
        if (is_dir(ZEED_PATH_MODULE . 'hooks/')) {
            $includePaths['hook'] = ZEED_PATH_MODULE . 'hooks/';
        }
        if (is_dir(ZEED_PATH_MODULE . 'entities/')) {
            $includePaths['entitie'] = ZEED_PATH_MODULE . 'entities/';
        }
        $includePaths['controller'] = $controllerFolder . '/';
        
        Zeed::register(array($module => $includePaths), 'ZEED_INCLUDE_PATH');
        set_include_path(implode(PATH_SEPARATOR, $includePaths) . PATH_SEPARATOR . get_include_path());
        
        /**
         * 查找控制器:
         * 1.查找请求的模块+控制器
         * 2.查找请求的模块+错误控制器
         * 3.查找请求的模块+默认控制器, 设置请求的请求方法为请求的控制器名
         * 4.查找默认的模块+控制器
         */
        if (file_exists($controllerFolder . '/' . $controllerName . EXT)) {
            include_once $controllerFolder . '/' . $controllerName . EXT;
            // Auto generate class for class name with module name prefix.
            if (! class_exists($controllerClassFinal, false) && $_autoCreateClass) {
                create_class($controllerClassFinal, $controllerName);
            }
        } elseif (file_exists($controllerFolder . '/Error' . ZEED_CONTROLLER_SUFFIX . EXT)) {
            $controllerName = 'Error' . ZEED_CONTROLLER_SUFFIX;
            include_once $controllerFolder . '/' . $controllerName . EXT;
            if (class_exists($controllerName, false)) {
                $controllerClassFinal = $controllerName;
            } elseif (class_exists($module . '_Error' . ZEED_CONTROLLER_SUFFIX, false)) {
                $controllerClassFinal = $module . '_Error' . ZEED_CONTROLLER_SUFFIX;
            } else {
                throw new Zeed_Exception('Controller (<code>' . $controllerClassFinal . '</code> or <code>Error' . ZEED_CONTROLLER_SUFFIX . '</code>) not found. Case sensitive.');
            }
        } else {
            /*$controllerName = ucfirst($this->getDefaultControllerName()) . ZEED_CONTROLLER_SUFFIX;
            if (file_exists($controllerFolder . '/' . $controllerName . EXT)) {
                $resquest->setActionName($resquest->getControllerName());
                include_once $controllerFolder . '/' . $controllerName . EXT;
                if (class_exists($controllerName, false)) {
                    $controllerClassFinal = $controllerName;
                } elseif (class_exists($module . '_' . $controllerName, false)) {
                    $controllerClassFinal = $module . '_' . $controllerName;
                }
            } else {
                throw new Zeed_Exception('Controller (<code>' . $controllerClassFinal . '</code>) not found. Case sensitive.');
            }*/
            
            throw new Zeed_Exception('Controller (<code>' . $controllerClassFinal . '</code>) not found. Case sensitive.');
        }
        
        switch (get_parent_class($controllerClassFinal)) {
            case 'Fit_Controller_Action' :
                // Fit_Controller_Action, SubClass of Zend_Controller_Action
                $action = new $controllerClassFinal(new Zend_Controller_Request_Http(), new Zend_Controller_Response_Http());
                break;
            case 'Zeed_Kohana_Controller' :
                die('Zeed_Kohana_Controller NOT IMPLEMENTED.');
                break;
            case 'ActionSupport' :
                die('ActionSupport(iNewS6) NOT IMPLEMENTED.');
                break;
            default :
                // Zeed_Controller_Action
                $action = new $controllerClassFinal($resquest);
        }
        
        if (! ($action instanceof Zeed_Controller_Action_Interface)) {
            throw new Zeed_Exception('Controller "' . $controllerName . '" is not an instance of Zeed_Controller_Action_Interface');
        }
        
        if ($controllerName == 'Error' . ZEED_CONTROLLER_SUFFIX) {
            $action = new $controllerClassFinal($resquest);
            $action->setParam('__REQUEST_METHOD__', $resquest->getActionName());
            $action->setParam('__REQUEST_ACTION__', $resquest->getControllerName());
            $resquest->setActionName('index');
        } else {
            $class = new ReflectionClass(get_class($action));
            if ('' != ($methodName = $resquest->getActionName())) {
                if (! $class->hasMethod($methodName) || ! $class->getMethod($methodName)->isPublic()) {
                    $action->setParam('__REQUEST_METHOD__', $methodName);
                    $resquest->setActionName(null);
                }
            }
            
            // Assign merged-parameters to public vars of controller.
            foreach ($resquest->getParams() as $name => $parameter) {
                if ($class->hasProperty($name)) {
                    $property = $class->getProperty($name);
                    if ($property->isPublic()) {
                        $action->$name = $parameter;
                    }
                }
            }
        }
        
        return $action;
    }
    
    /**
     * Set controller directory
     *
     * @param array|string $directory
     * @return Zeed_Controller_Dispatcher
     */
    public function setControllerDirectory($directory, $module = null)
    {
        $this->_controllerDirectory = array();
        
        if (is_string($directory)) {
            $this->addControllerDirectory($directory, $module);
        } elseif (is_array($directory)) {
            foreach ((array) $directory as $module => $path) {
                $this->addControllerDirectory($path, $module);
            }
        } else {
            throw new Zeed_Exception('Controller directory spec must be either a string or an array');
        }
        
        return $this;
    }
    
    /**
     * Add a single path to the controller directory stack
     *
     * @param string $path
     * @param string $module
     * @return Zeed_Controller_Dispatcher
     */
    public function addControllerDirectory($path, $module = null)
    {
        if (null === $module) {
            $module = $this->_defaultModule;
        }
        
        $module = (string) $module;
        $path = rtrim((string) $path, '/\\');
        
        $this->_controllerDirectory[$module] = $path;
        return $this;
    }
    
    /**
     * Return the currently set directories for Zeed_Controller_Action class lookup
     *
     * If a module is specified, returns just that directory.
     *
     * @param  string $module Module name
     * @return array|string Returns array of all directories by default, single module directory if module argument provided
     */
    public function getControllerDirectory($module = null)
    {
        if (null === $module) {
            return $this->_controllerDirectory;
        }
        
        $module = (string) $module;
        if (array_key_exists($module, $this->_controllerDirectory)) {
            return $this->_controllerDirectory[$module];
        }
        
        return null;
    }
    
    /**
     * Determine if a given module is valid
     *
     * @param  string $module
     * @return bool
     */
    public function isValidModule($module)
    {
        if (! is_string($module)) {
            return false;
        }
        
        if (array_key_exists(strtolower($module), array_change_key_case($this->getControllerDirectory(), CASE_LOWER))) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Retrieve the default controller name (minus formatting)
     *
     * @return string
     */
    public function getDefaultControllerName()
    {
        return $this->_defaultController;
    }
    
    public function setDefaultControllerName($controller)
    {
        $this->_defaultController = (string) $controller;
        return $this;
    }
    
    /**
     * Retrieve the default action name (minus formatting)
     *
     * @return string
     */
    public function getDefaultAction()
    {
        return $this->_defaultAction;
    }
    
    public function setDefaultAction($action)
    {
        $this->_defaultAction = (string) $action;
        return $this;
    }
    
    /**
     * Retrieve the default module
     *
     * @return string
     */
    public function getDefaultModule()
    {
        return $this->_defaultModule;
    }
    
    public function setDefaultModule($module)
    {
        $this->_defaultModule = (string) $module;
        return $this;
    }
}

function create_class($class_name, $extend_to)
{
    if (class_exists($extend_to, false)) {
        eval('class ' . $class_name . ' extends ' . $extend_to . '{}');
    } else {
        throw new Zeed_Exception('Copy class fail. Class (<code>' . $extend_to . '</code>) not found.');
    }
}

// End ^ LF ^ UTF-8
