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
 * @since      2011-10-21
 * @version    SVN: $Id$
 */

class Zeed_Db_Mongo
{
    // Database instances
    public static $instances = null;
    
    /**
     * 数据库连接单列模式
     * 这里处理是否有多个数据配置
     * 
     * @param string $name 连接的数据库SchemaName
     * @param mixed configuration array or DSN
     * @return Mongo
     */
    public static function &instance($name = 'default', $config = NULL)
    {
        if (empty($config)) {
            $config = Zeed_Config::loadGroup('database.' . $name);
        }
        
        $hash = $config['server'];
        if (isset($config['server_options']) && !empty($config['server_options'])) {
            $hash .= '-' . json_encode(ksort($config['server_options']));
        } else {
            $hash .= '-' . "{}";
        }
        if (isset($config['server_slaveok']) && $config['server_slaveok']) {
            $hash .= '-{1}';
        } else {
            $hash .= '-{0}';
        }
        $hash = md5($hash);
        
        if (! isset(self::$instances[$hash])) {
            if(isset($config['server_options']) && !empty($config['server_options'])) {
                self::$instances[$hash] = new Mongo($config['server'], $config['server_options']);
            } else {
                self::$instances[$hash] = new Mongo($config['server']);
            }
            if(isset($config['server_slaveok']) && $config['server_slaveok']) {
                self::$instances[$hash]->setSlaveOkay();
            }
        }
        
        return self::$instances[$hash];
    }
}
