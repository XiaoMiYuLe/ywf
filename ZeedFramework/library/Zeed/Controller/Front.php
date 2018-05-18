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
 * @version    SVN: $Id: Front.php 8847 2010-12-07 09:34:32Z xsharp $
 */

class Zeed_Controller_Front
{
    
    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Zeed_Controller_Front
     */
    protected static $_instance = null;
    
    /**
     * @var Zeed_Controller_Dispatcher
     */
    protected $_dispatcher;
    
    /**
     * Enter description here...
     *
     * @return Zeed_Controller_Front
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     * Dispatch an HTTP request to a controller/action.
     *
     * @return void
     */
    public function dispatch()
    {
        $request = Zeed_Controller_Request::instance();
        $router = $this->getRouter();
        $router->route($request);
        
        $this->getDispatcher()->dispatch($request);
    }
    
    /**
     * @return Zeed_Controller_Dispatcher
     */
    public function setDispatcher(Zeed_Controller_Dispatcher_Interface $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
        return $this;
    }
    
    /**
     * Return the dispatcher object.
     *
     * @return Zeed_Controller_Dispatcher
     */
    public function getDispatcher()
    {
        if (! $this->_dispatcher instanceof Zeed_Controller_Dispatcher) {
            $this->_dispatcher = new Zeed_Controller_Dispatcher();
        }
        return $this->_dispatcher;
    }
    
    public function batchSetControllerDirectories($config)
    {
        if (! is_array($config)) {
            return false;
        }
        
        if (isset($config['defaultController'])) {
            $this->setControllerDirectory(ZEED_PATH_APPS . $config['defaultController']);
        }
        
        if (isset($config['controllers']) && is_array($config['controllers'])) {
            foreach ($config['controllers'] as $name => $directory) {
                $this->addControllerDirectory(ZEED_PATH_APPS . $directory, $name);
            }
        }
    }
    
    /**
     * Set controller directory
     *
     * Stores controller directory(ies) in dispatcher. May be an array of
     * directories or a string containing a single directory.
     *
     * @param string|array $directory Path to Zend_Controller_Action controller
     * classes or array of such paths
     * @param  string $module Optional module name to use with string $directory
     * @return Zend_Controller_Front
     */
    public function setControllerDirectory($directory, $module = null)
    {
        $this->getDispatcher()->setControllerDirectory($directory, $module);
        return $this;
    }
    
    /**
     * Add a controller directory to the controller directory stack
     *
     * If $args is presented and is a string, uses it for the array key mapping
     * to the directory specified.
     *
     * @param string $directory
     * @param string $module Optional argument; module with which to associate directory. If none provided, assumes 'default'
     * @return Zend_Controller_Front
     * @throws Zend_Controller_Exception if directory not found or readable
     */
    public function addControllerDirectory($directory, $module = null)
    {
        $this->getDispatcher()->addControllerDirectory($directory, $module);
        return $this;
    }
    
    /**
     * Instance of Zeed_Controller_Router_Interface
     * @var Zeed_Controller_Router_Interface
     */
    protected $_router = null;
    
    /**
     * @param string|Zeed_Controller_Router_Interface $router
     * @return Zeed_Controller_Front
     */
    public function setRouter($router)
    {
        $this->_router = $router;
        
        return $this;
    }
    
    /**
     * Return the router object.
     *
     * Instantiates a Zend_Controller_Router_Rewrite object if no router currently set.
     *
     * @return Zeed_Controller_Router_Rewrite
     */
    public function getRouter()
    {
        if (null == $this->_router) {
            $this->setRouter(new Zeed_Controller_Router_Rewrite());
        }
        
        return $this->_router;
    }
}

// End ^ LF ^ UTF-8
