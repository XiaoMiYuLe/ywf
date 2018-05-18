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
 * @since      Jun 4, 2009
 * @version    SVN: $Id: Php.php 13223 2012-07-13 09:42:25Z xsharp $
 */

class Zeed_View_Php extends Zeed_View
{
    protected $_action;
    protected $_result;
    
    public function process($result, Zeed_Controller_Action $action)
    {
        $action_config = $action->getConfig();
        $resource = $action_config[$result]['resource'];
        $this->_action = & $action;
        $this->_result = array($result => $action_config[$result]);
        
        echo parent::render($resource);
    }
    
    public function __get($p)
    {
        return $this->_action->$p;
    }
    
    /**
     * 魔术函数, 可以在视图中直接调用CONTROLLER的PUBLIC方法.
     * 
     * @param string $fun CONTROLLER中存在的PUBLIC方法
     * @param array $p    参数
     */
    public function __call($fun, $params)
    {
        $argString = '';
        foreach ($params as $key => $arg) {
            $p = 'p_' . $key;
            $$p = $arg;
            $argString .= '$' . $p . ', ';
        }
        $argString = substr($argString, 0, - 2);
        
        return eval('return $this->_action->$fun(' . $argString . ');');
    }
}

// End ^ LF ^ UTF-8
