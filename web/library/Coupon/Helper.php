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
 * @since      2011-5-24
 * @version    SVN: $Id$
 */

class Coupon_Helper
{
    /**
     * 用户领取优惠券
     * @param $userid
     * @param $coupon_id
     */
    public static function getCouponByUserid($userid, $coupon_id)
    {
        $fp = fopen(ZEED_PATH_CONF . '/CouponLocker/lock.txt', 'w+');
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            try {
                $coupon = Coupon_Model_Category::instance()->fetchByPK($coupon_id);
                if ($coupon[0]['exchanged_total'] >= $coupon[0]['total']) {
                    throw new Zeed_Exception('');                    
                }
                $set['coupon_id'] = $coupon_id;
                $set['coupon_status'] = 0;
                $set['is_del'] = 0;
                $set['disbaled'] = 0;
                $set['userid'] = $userid;
                $set['ctime'] = date(('Y-m-d H:i:s'), time());
                $set['mtime'] = date(('Y-m-d H:i:s'), time());
                $pk = Coupon_Model_Listing::instance()->addForEntity($set);
                $detail = Coupon_Model_Listing::instance()->fetchDetailForUser($pk);
                
                $save['exchanged_total'] = $coupon[0]['exchanged_total'] + 1;
                Coupon_Model_Category::instance()->update($save, "coupon_id = " . $coupon_id);
            } catch (Exception $e) {
                flock($fp, LOCK_UN);
                return false;
            }
            flock($fp, LOCK_UN);
        } else {
            return false;
        }
        fclose($fp);
        return $detail;
    }
}

// End ^ Native EOL ^ encoding
