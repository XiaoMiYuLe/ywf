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
class Trend_Promotion_Abstract
{
    /**
     * 定义设置的分组 ID
     */
    const SETTING_GROUP_PROMOTION = 4;
    /**
     * 计算商品单品的优惠价格，得出按单品优惠之后的总价（支持批量）
     * 1、若指定有用户 ID，则查询出用户的等级折扣信息，并计算出商品按用户等级折扣之后的价格；
     * 2、查询商品参加的优惠活动；
     * 3、调用活动对应的优惠规则，以按用户等级折扣之后的价格为基础，得出优惠之后的价格；
     * 4、返回优惠之后的总价；
     * 
     * @param integer|array $goods 商品 ID
     * @param integer $userid 用户 ID
     * @return float
     */
    public static function saleoff($goods, $userid = null)
    {
        /* 若商品参数为空，则直接返回 */
        if (empty($goods)) {
            return null;
        }
        
        /* 获取商品信息 */
        $goods_info = Goods_Model_Content::instance()->fetchByPK($goods);
        if (empty($goods_info)) {
            return null;
        }
            
        /* 获取用户信息，包括用户等级折扣信息 */
        
        /* 遍历商品，并计算商品优惠后的价格 */
        $goods_price = array();
        foreach ($goods_info as $v) {
            // 判断当前商品所参加的优惠活动（注意可能是商品 ID、分类、品牌）
            
            
            // 检查当前活动的所属类文件是否存在
            $class_name = "Trend_Promotion_" . $v;
            class_exists($class_name);
            
            // 调用活动所属类文件，计算优惠价格，并将该价格进行拼装
            
        }
        
        /* 计算商品优惠后的价格总和 */
        $goods_price = array_sum($goods_price);
        
        return $goods_price;
    }
}

// End ^ Native EOL ^ UTF-8