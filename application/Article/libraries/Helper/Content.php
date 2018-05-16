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

class Helper_Content
{
    /**
     * 判断文章是否存在
     *
     * @param integer $content_id
     * @return boolean
     */
    public static function exist($content_id = 0)
    {
        $content_id = (int) $content_id;
        if (Article_Model_Content::instance()->fetchByPK($content_id)) {
            return true;
        }
        return false;
    }
}
