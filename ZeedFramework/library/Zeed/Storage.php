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
 * @package    Zeed_Benchmark
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-11-1
 * @version    SVN: $Id$
 */

class Zeed_Storage
{
    //Storage instances
    public static $instances = null;
    
    /**
     * Storage Singleton Pattern
     * 
     * @param string $name Schema Name
     * @param mixed configuration array
     * @return Zeed_Storage_Adapter_Abstract|Zeed_Storage_Adapter_Disk
     */
    public static function &instance($name = 'default', $config = NULL)
    {
        if (empty($config)) {
            $config = Zeed_Config::loadGroup('attachment.adapters.' . $name);
        }
        
        $hash = $config['adapter'];
        
        if (! isset(self::$instances[$hash])) {
            $className = 'Zeed_Storage_Adapter_' . $hash;
            if (!class_exists($className, true)) {
                throw new Exception("Unknown storage adapter {$className}");
            }
            self::$instances[$hash] = new $className($config);
        }
        
        return self::$instances[$hash];
    }

}