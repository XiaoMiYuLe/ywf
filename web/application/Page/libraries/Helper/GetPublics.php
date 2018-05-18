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
 * @category   Cas
 * @package    Cas_Nickname
 * @subpackage Cas_Nickname
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @version    SVN: $Id: Nickname.php 11108 2011-08-09 02:33:42Z nroe $
 */

class Helper_GetPublics
{
    /**
     * 获取单页公共头尾部分
     */
    public static function run()
    {
        $publics = Page_Model_Config::instance()->fetchByFV('name', array('public_header', 'public_footer'));
        
        $rows = array();
        if (! empty($publics)) {
            foreach ($publics as $v) {
                $rows[$v['name']] = $v['value'];
            }
        }
        
        return $rows;
    }
}
