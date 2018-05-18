<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-10-26
 * @version SVN: $Id$
 */
class Trend_Helper_Growth_Login
{
    private static $_user_lv_id = 5; // 定义用户默认级别为初级企业会员
    
    /**
     * 更新用户积分公用方法
     * 
     * @param unknown $data
     * @param unknown $value
     */
    private static function changeIncome($userid,$value,$remark = '')
    {
       
        // 取得对应用户资产
        $data = Bts_Model_Asset::instance()->getAssetByUserRagionId($userid, 2);
        
        if (empty($data)) {
            return ;
        }
        
        $data = $data[0];
        // 计算资产
        $data['credit'] += $value;
        // 计算资产排行榜，只增不减
        $data['credit_charts'] += $value;
        $data['charge_time'] = date(DATETIME_FORMAT);
        $asset_id = $data['asset_id'];
        unset($data['asset_id']);
        Bts_Model_Asset::instance()->update($data, 'asset_id='.$asset_id);
        
        // 插入日志表
        $set = array(
                     'userid'=>$data['userid'],
                     'admin_userid' => 0,
                     'type' =>3,
                     'region_id' => 2,
                     'value' => $value,
                     'after_value' => $data['credit'],
                     'cause' => '广告积分分成',
                     'remark' => $remark,
                     'ctime' => date(DATETIME_FORMAT));
        Bts_Model_Asset_Log::instance()->addForEntity($set);
        return $data;
    }
}

// End ^ Native EOL ^ UTF-8