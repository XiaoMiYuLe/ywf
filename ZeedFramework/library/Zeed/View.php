<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_View
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: View.php 13223 2012-07-13 09:42:25Z xsharp $
 */

abstract class Zeed_View
{
    abstract function process($result, Zeed_Controller_Action $action);
    
    /**
     * 设置View对象的值
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    
    public function render($name)
    {
        $module = defined('ZEED_PATH_MODULE') ? basename(ZEED_PATH_MODULE) . '/' : '';
//         $file = ZEED_PATH_VIEW . strtolower($module) . $name;
        $file = ZEED_PATH_VIEW . strtolower($module) . ZEED_PATH_ADMIN_OR_FRONT . $name; // temp update at 2013-07-14
        if (substr($name, (0 - strlen(EXT))) != EXT) {
            $file .= EXT;
        }
        if (file_exists($file)) {
            $this->_run($file);
            return;
        } elseif (file_exists($file = ZEED_PATH_APPS . $module . 'views/' . $name)) {
            $this->_run($file);
            return;
        }
        
        throw new Zeed_Exception("View file: '$file' not exist!");
    }
    
    protected function _run()
    {
        include func_get_arg(0);
    
    }
}

// End ^ LF ^ UTF-8
