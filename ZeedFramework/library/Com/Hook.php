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
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-9-6
 * @version    SVN: $Id$
 */

class Com_Hook
{
    public static $data = array();

    /**
     * 执行钩子
     *
     * @param string $funcname 执行函数名
     * @param array $data 钩子参数
     *
     * @return void
     */
    public static function exec($funcname, $data = array())
    {
        $classname = self::normalizeHookName($funcname);

        try {
            @Zeed_Loader::loadClass($classname);
        }
        catch (Exception $e) {
            return;
        }

        if (class_exists($classname) && method_exists($classname, 'run')) {
            self::$data = $data;

            $result = false;

            try {
                $result = call_user_func_array(array($classname, 'run'), $data);
            }
            catch (Exception $e) {
            }

            /**
             * HOOK 执行失败，打入日志
             */
            if (false === $result) {
                Zeed_Log::instance()->log(array('tag' => "HOOK::{$funcname}::exec", 'data' => $data), Zeed_Log::ALERT);
            }
        }
    }

    /**
     * Normalize frontend and backend names to allow multiple words TitleCased
     *
     * @param  string $name  Name to normalize
     * @return string
     */
    public static function normalizeHookName($name)
    {
        $name = ucfirst($name);
        $name = str_replace(array('-', '_', '.'), ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '_', $name);
        $name = $name . 'Hook';
        return $name;
    }
}

// End ^ Native EOL ^ encoding
