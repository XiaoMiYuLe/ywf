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
 * @package    Zeed_Config
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Config.php 10460 2011-06-07 05:35:51Z xsharp $
 */

/**
 * 部分代码参考 Zend_Confg & Kohana_Config
 */
class Zeed_Config
{
    
    private static $_cacheMain;
    private static $_cachePartial;
    
    /**
     * 快速加载配置(数组), 优先使用静态缓存.
     * 使用方法:
     * Zeed_Config::loadGroup('database');
     * Zeed_Config::loadGroup('database.default');
     * Zeed_Config::loadGroup('database.default.host');
     *
     * @param string $group
     * @return array|null
     */
    public static function loadGroup($group)
    {
        $grouproot = $group;
        if (strstr($group, '.')) {
            /**
             * group.foo
             * 1.检查缓存,存在则返回
             * 2.检查 group.foo.php 配置文件, 存在则缓存到 $_cachePartial 并返回
             * 3.检查 group.php 配置文件, 存在则缓存到 $_cacheMain 并返回
             */
            $hierarchical = explode('.', $group);
            $grouproot = array_shift($hierarchical);
            $hasCachedConfigs = self::_loadCachedConfigs($group);
            if (is_null($hasCachedConfigs)) {
                $filename = self::_findFile($group);
                if (! is_null($filename)) {
                    $return = include $filename;
                    self::_cachingConfigs($group, $return);
                    return $return;
                }
            } else {
                return $hasCachedConfigs;
            }
        }
        
        /**
         * group
         * 1.检查 group.php 配置文件, 存在则缓存
         * 2.调用self::_loadCachedConfigs()
         */
        if (! isset(self::$_cacheMain[$grouproot])) {
            $filename = self::_findFile($grouproot);
            if (! is_null($filename)) {
                $return = include $filename;
                self::_cachingConfigs($grouproot, $return);
            }
        }
        
        return self::_loadCachedConfigs($group);
    }
    
    /**
     * 在根目录下的 config 目录以及模块目录下的 configs 目录中查找配置文件
     * 
     * @param string $filename
     * @return string
     */
    private static function _findFile($filename)
    {
        $searchDir = array(ZEED_PATH_CONF);
        if (defined('ZEED_PATH_MODULE')) {
            array_unshift($searchDir, ZEED_PATH_MODULE . 'configs/');
        }
        
        return Zeed_Loader::findFile($filename . '.php', $searchDir);
    }
    
    /**
     * 
     * @param string $group
     * @return mixed
     */
    private static function _loadCachedConfigs($group)
    {
        if (strstr($group, '.')) {
            /**
             * group.foo
             * 1.检查$_cachePartial[group][foo]
             * 2.检查$_cacheMail[group][foo]
             */
            $hierarchical = explode('.', $group);
            $grouproot = array_shift($hierarchical);
            
            if (isset(self::$_cachePartial[$grouproot])) {
                $return = self::$_cachePartial[$grouproot];
                foreach ($hierarchical as $_h) {
                    $return = isset($return[$_h]) ? $return[$_h] : null;
                }
                
                if (! is_null($return)) {
                    return $return;
                }
            }
            if (isset(self::$_cacheMain[$grouproot])) {
                $return = self::$_cacheMain[$grouproot];
                foreach ($hierarchical as $_h) {
                    $return = isset($return[$_h]) ? $return[$_h] : null;
                }
                
                return $return;
            }
        } else {
            /**
             * group
             * 合并$_cacheMail[group]和$_cachePartial[group]
             */
            $grouproot = $group;
            $return = null;
            if (isset(self::$_cacheMain[$grouproot])) {
                $return = self::$_cacheMain[$grouproot];
            }
            if (isset(self::$_cachePartial[$grouproot])) {
                if (is_array($return)) {
                    $return = array_merge($return, self::$_cachePartial[$grouproot]);
                } else {
                    $return = self::$_cachePartial[$grouproot];
                }
            }
            
            
            return $return;
        }
        
        return null;
    }
    
    /**
     * 缓存已经加载的配置
     * group 缓存到 $_cacheMain
     * group.foo 缓存到 $_cachePartial
     * 
     * @param string $group
     * @param array $configs
     * @return void
     */
    private static function _cachingConfigs($group, $configs)
    {
        if (strstr($group, '.')) {
            $hierarchical = explode('.', $group);
            $grouproot = array_shift($hierarchical);
            
            $hierarchical = array_reverse($hierarchical);
            foreach ($hierarchical as $_h) {
                $tmp[$_h] = isset($tmp[$_h]) ? $tmp[$_h] : $configs;
            }
            $configs = $tmp;
            
            if (isset(self::$_cachePartial[$grouproot])) {
                self::$_cachePartial[$grouproot] = array_merge(self::$_cachePartial[$grouproot], $configs);
            } else {
                self::$_cachePartial[$grouproot] = $configs;
            }
        } else {
            $grouproot = $group;
            
            if (isset(self::$_cacheMain[$grouproot])) {
                self::$_cacheMain[$grouproot] = array_merge(self::$_cacheMain[$grouproot], $configs);
            } else {
                self::$_cacheMain[$grouproot] = $configs;
            }
        }
    
    }

}

// End ^ LF ^ UTF-8
