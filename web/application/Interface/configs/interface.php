<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-3-21
 * @version    SVN: $Id$
 */

$urlmapping = Zeed_Config::loadGroup('urlmapping');

$config['apps'] = array(
        'default' => array(
                'title' => '默认接口',
                'url' => $urlmapping['store_url'],
                'key' => 'ZEED-KEY',
                'secret' => 'O]dWJ,[*g)%k"?q~g6Co!`cQvV>>Ilvw',
                'timeout' => 60));

return $config;
