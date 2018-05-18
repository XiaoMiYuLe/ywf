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

class Helper_Category
{
    /**
     * 判断分类是否存在
     *
     * @param integer $category_id
     * @return boolean
     */
    public static function exist($category_id = 0)
    {
        $category_id = (int) $category_id;
        if (Article_Model_Category::instance()->fetchByPK($category_id)) {
            return true;
        }
        return false;
    }
}
