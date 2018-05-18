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
 * @version    SVN: $Id: Rewrite.php 12613 2012-02-01 10:03:54Z xsharp $
 */

class Zeed_Controller_Router_Rewrite
{
    /**
     * Array of routes to match against
     *
     * @var array
     */
    protected $_routes = array();
    
    /**
     *
     * @var Zeed_Controller_Front
     */
    protected $_frontController;
    
    public function route(Zeed_Controller_Request $request)
    {
        $this->addDefaultRoutes();
        
        foreach (array_reverse($this->_routes) as $name => $route) {
            if (! method_exists($route, 'getVersion') || $route->getVersion() == 1) {
                $match = $request->baseUri();
            } else {
                $match = $request;
            }
            
            if (is_array($params = $route->match($match))) {
                // 合并 $_GET 和 $_POST 参数
                Zeed_Controller_Request::setParsedParams($params);
                $params = array_merge($params, Zeed_Controller_Request::get(), Zeed_Controller_Request::post());
                $this->_setRequestParams($request, $params);
                break;
            }
        }
        
        return $request;
    }
    
    protected function _setRequestParams(Zeed_Controller_Request $request, $params)
    {
        foreach ($params as $param => $value) {
            $request->setParam($param, $value);
            
            if ($param === $request->getModuleKey()) {
                $request->setModuleName($value);
            }
            if ($param === $request->getControllerKey()) {
                $request->setControllerName($value);
            }
            if ($param === $request->getActionKey()) {
                $request->setActionName($value);
            }
        }
    }
    
    /**
     * Add default routes which are used to mimic basic router behaviour
     *
     * @return Zeed_Controller_Router_Rewrite
     */
    public function addDefaultRoutes()
    {
        $compat = new Zeed_Controller_Router_Route_Module(array(), $this->getFrontController()->getDispatcher(), Zeed_Controller_Request::instance());
        
        $this->_routes = array('default' => $compat) + $this->_routes;
        
        return $this;
    }
    
    /**
     * Add route to the route chain If route implements Zend_Controller_Request_Aware interface it is initialized with a
     * request object
     *
     * @param $name string Name of the route
     * @param Zend_Controller_Router_Route_Interface Route
     */
    public function addRoute($name, $route)
    {
        if (method_exists($route, 'setRequest')) {
            $route->setRequest($this->getFrontController()->getRequest());
        }
        
        $this->_routes[$name] = $route;
        
        return $this;
    }
    
    /**
     * Retrieve Front Controller
     *
     * @return Zeed_Controller_Front
     */
    public function getFrontController()
    {
        // Used cache version if found
        if (null !== $this->_frontController) {
            return $this->_frontController;
        }
        
        $this->_frontController = Zeed_Controller_Front::getInstance();
        return $this->_frontController;
    }
}

// End ^ LF ^ UTF-8
