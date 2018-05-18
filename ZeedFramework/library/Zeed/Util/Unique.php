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
 * @since      2010-9-2
 * @version    SVN: $Id$
 */

class Zeed_Util_Unique
{
    
    /**
     * Unique Method
     *
     * @var array
     */
    public static $zeedStandardBackends = array(
            'increment',
            'hash');
    
    public function instance($method, $backend)
    {
        $memcached = Zeed_Config::loadGroup('cache.memcached');
    }
}

// End ^ Native EOL ^ encoding
