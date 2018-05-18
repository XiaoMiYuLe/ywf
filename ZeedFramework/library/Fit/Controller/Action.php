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
 * @since      2009-8-5
 * @version    SVN: $Id$
 */

class Fit_Controller_Action extends Zend_Controller_Action implements Zeed_Controller_Action_Interface
{
    
    protected $_allowedResultType = array(
            'default' => 'Fit_View_Default',
            'php' => 'Fit_View_Php',
            'redirector' => 'Fit_View_Redirector',
            'json' => 'Fit_View_Json',
            'xml' => 'Fit_View_Xml');
    
    protected $_resultType = array();
    
    public function addResult($result, $resultType = null, $resource = null)
    {
        if (is_null($resultType)) {
            $resultType = 'default';
        }
        $resultType = strtolower($resultType);
        
        if (! in_array($resultType, array_keys($this->_allowedResultType))) {
            Zeed_Loader::loadClass('Zeed_Exception');
            throw new Zeed_Exception('视图类型不被允许. 允许的视图类型:<code>' . implode(',', $this->_allowedResultType) . '</code>');
        }
        
        $this->_resultType[$result] = array(
                'type' => $resultType,
                'resource' => $resource);
        
        return $this;
    }
    
    /**
     * 获取RESULT配置
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->_resultType;
    }
    
    public function index()
    {
        echo '<h2>Default Method. Overwrite me plz!</h2> (<code>' . __METHOD__ . '</code>)';
    }
    
    /**
     * Enter description here...
     *
     * @param string $name
     * @return Zeed_View
     */
    public function getResultType($name)
    {
        $_resultClass = 'Fit_View_' . ucfirst($name);
        
        return new $_resultClass();
    }
    
    /**
     * @param Fit_Controller_Action $action
     * @see Controller/Zend_Controller_Action#dispatch($action)
     */
    public function dispatch($action)
    {
        $result = $this->_dispatch($action);
        $actionConfiguration = $this->getConfig();
        if (isset($actionConfiguration[$result])) {
            $processor = $this->getResultType($actionConfiguration[$result]['type']);
            $processor->process($result, $this);
        } else {
            $processor = $this->getResultType("Default");
            $processor->process($result, $this);
        }
    }
    
    public function _dispatch($action)
    {
        // Notify helpers of action preDispatch state
        $this->_helper->notifyPreDispatch();
        
        $this->preDispatch();
        if ($this->getRequest()->isDispatched()) {
            if (null === $this->_classMethods) {
                $this->_classMethods = get_class_methods($this);
            }
            
            // preDispatch() didn't change the action, so we can continue
            if ($this->getInvokeArg('useCaseSensitiveActions') || in_array($action, $this->_classMethods)) {
                if ($this->getInvokeArg('useCaseSensitiveActions')) {
                    trigger_error('Using case sensitive actions without word separators is deprecated; please do not rely on this "feature"');
                }
                $return = $this->$action();
            } else {
                $this->__call($action, array());
            }
            $this->postDispatch();
        }
        
        // whats actually important here is that this action controller is
        // shutting down, regardless of dispatching; notify the helpers of this
        // state
        $this->_helper->notifyPostDispatch();
        
        return $return;
    }
}

// End ^ LF ^ encoding
