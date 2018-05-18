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
 * @package    Zeed_Db
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Db.php 6710 2010-09-08 13:51:53Z xsharp $
 */

class Zeed_Db extends Zend_Db
{
    // Database instances
    public static $instances = null;
    
    public static $metaCacheEnable = false;
    
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
            /**
             * @todo 判断是否已有相同服务器&相同用户名
             */
            self::$instances[$name] = parent::factory($config['adapter'], $config);
            if (isset($config['metacache'])) {
                self::$metaCacheEnable = $config['metacache'];
            }
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
        return Zeed_Db_Model::getModel($name);
    }
}

// End ^ LF ^ UTF-8
