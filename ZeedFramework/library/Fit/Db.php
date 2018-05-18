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
 * @version    SVN: $Id: Db.php 4687 2009-12-22 08:31:14Z xsharp $
 */

class Fit_Db extends Zend_Db
{
    // Database instances
    public static $instances = null;
    
    /**
     * 数据库连接单列模式
     * 这里处理是否有多个数据配置
     * 
     * @param string $name 连接的数据库SchemaName
     * @param mixed configuration array or DSN
     * @return Zend_Db_Adapter_Abstract
     */
    public static function &instance($name = 'default', $config = NULL)
    {
        if (empty($config)) {
            $config = Zeed_Config::loadGroup('database.' . $name);
        }
        
        if (! isset(self::$instances[$name])) {
            self::$instances[$name] = parent::factory($config['adapter'], $config);
            if (isset($config['profiler']) && $config['profiler']) {
                self::$instances[$name]->getProfiler()->setEnabled(true);
            }
        }
        
        return self::$instances[$name];
    }
    
    /**
     * 获取指定MODEL的实例
     *
     * @param string $name
     * @return Zeed_Db_Model
     */
    public static function getModel($name)
    {
        return Fit_Db_Model::getModel($name);
    }
}

// End ^ LF ^ UTF-8
