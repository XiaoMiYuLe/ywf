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
 * @since      2009-8-25
 * @version    SVN: $Id: Module.php 12613 2012-02-01 10:03:54Z xsharp $
 */

class Zeed_Controller_Router_Route_Module
{
    
    /**
     * URI delimiter
     */
    const URI_DELIMITER = '/';
    
    /**
     * Default values for the route (ie. module, controller, action, params)
     *
     * @var array
     */
    protected $_defaults = array();
    
    protected $_values = array();
    protected $_moduleValid = false;
    protected $_keysSet = false;
    
    protected $_moduleKey = 'module';
    protected $_controllerKey = 'controller';
    protected $_actionKey = 'action';
    
    /**
     *
     * @var Zeed_Controller_Dispatcher
     */
    protected $_dispatcher;
    
    /**
     *
     * @var Zeed_Controller_Request
     */
    protected $_request;
    
    /**
     * Constructor
     *
     * @param $defaults array Defaults for map variables with keys as variable names
     * @param $dispatcher Zeed_Controller_Dispatcher_Interface Dispatcher object
     * @param $request Zeed_Controller_Request Request object
     */
    public function __construct(array $defaults = array(), Zeed_Controller_Dispatcher_Interface $dispatcher = null, Zeed_Controller_Request $request = null)
    {
        $this->_defaults = $defaults;
        
        if (isset($request)) {
            $this->_request = $request;
        }
        
        if (isset($dispatcher)) {
            $this->_dispatcher = $dispatcher;
        }
    }
    
    /**
     * Matches a user submitted path. Assigns and returns an array of variables on a successful match. If a request
     * object is registered, it uses its setModuleName(), setControllerName(), and setActionName() accessors to set
     * those values. Always returns the values as an array.
     *
     * @param $path string Path used to match against this routing map
     * @return array An array of assigned values or a false on a mismatch
     */
    public function match($path, $partial = false)
    {
        $this->_setRequestKeys();
        
        $values = array();
        $params = array();
        
        if (! $partial) {
            $path = trim($path, self::URI_DELIMITER);
        } else {
            // 未实现
        }
        
        if ($path != '') {
            $path = explode(self::URI_DELIMITER, $path);
            
            if ($this->_dispatcher && $this->_dispatcher->isValidModule($path[0])) {
                $values[$this->_moduleKey] = array_shift($path);
                $this->_moduleValid = true;
            }
            
            if (count($path) && ! empty($path[0])) {
                $_controller = array_shift($path);
                if (strstr($_controller, '.')) {
                    $_tmp = explode('.', $_controller);
                    $values[$this->_controllerKey] = $_tmp[0];
                    $values[$this->_actionKey] = $_tmp[1];
                } else {
                    $values[$this->_controllerKey] = $_controller;
                }
            }
            
            // 兼容iNewS6.x
            if ('' != $do = Zeed_Controller_Request::query('do')) {
                $values[$this->_actionKey] = $do;
            } elseif (count($path) && ! empty($path[0])) {
                $values[$this->_actionKey] = array_shift($path);
            }
            
            if (0 < $numSegs = count($path)) {
                for ($i = 0; $i < $numSegs; $i = $i + 2) {
                    $key = urldecode($path[$i]);
                    $val = isset($path[$i + 1]) ? urldecode($path[$i + 1]) : null;
                    $params[$key] = (isset($params[$key]) ? (array_merge((array) $params[$key], array($val))) : $val);
                }
            }
            // Zeed_Benchmark::print_r($values, 'Module / Controller / Action');
        }
        
        $this->_values = $values + $params;
        
        return $this->_values + $this->_defaults;
    }
    
    /**
     * Set request keys based on values in request object
     *
     * @return void
     */
    protected function _setRequestKeys()
    {
        if (null !== $this->_request) {
            $this->_moduleKey = $this->_request->getModuleKey();
            $this->_controllerKey = $this->_request->getControllerKey();
            $this->_actionKey = $this->_request->getActionKey();
        }
        
        if (null !== $this->_dispatcher) {
            $this->_defaults += array(
                    $this->_controllerKey => $this->_dispatcher->getDefaultControllerName(), 
                    $this->_actionKey => $this->_dispatcher->getDefaultAction(), 
                    $this->_moduleKey => $this->_dispatcher->getDefaultModule());
        }
        
        $this->_keysSet = true;
    }
}

// End ^ LF ^ UTF-8
