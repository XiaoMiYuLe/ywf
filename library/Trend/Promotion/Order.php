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
class Trend_Promotion_Order extends Trend_Promotion_Abstract
{
    /**
     * 优惠促销活动的价格处理 - 源自订单
     * 1、获取指定的订单信息，即订单中的商品信息；
     * 2、循环计算商品单品的优惠价格，得出按单品优惠之后的总价；
     * 3、读取设置，判断是否允许叠加全场活动；
     * 4、若第三条满足，则计算叠加全场活动之后的最终优惠价格，并返回相关的附加信息，比如换购商品；
     * 5、查询指定用户对应等级的优惠（比如满99免运费），并计算出最终订单总价；
     *
     * @param string $order_numbber 订单号
     * @param integer $userid 用户 ID
     * @return float
     */
    public static function order($userid)
    {
        // 总金额
        $totol_price = 0;
        /* 若没有指定用户 ID，则直接返回 */
        if (! $userid) {
            return null;
        }
        
        /* 获取用户的购物车信息 */
        $goods_cart = Bts_Model_Cart::instance()->fetchByFV('userid', $userid);
      
        /* 循环处理商品ids */
        foreach ($goods_cart as $grow) {
            $goods_ids[] = $grow['content_id'];
        }
        
        /* 根据活动计算订单金额*/
        $promotion_data = Promotion_Model_Goods::instance()->GetGoodsToPromotion($goods_ids);
        
        /* 循环计算商品单品的优惠价格，得出按单品优惠之后的总价 */
        foreach ($promotion_data as &$v) {
        
            /* 判断活动类型 */
            switch ($value['promotion_type']) {
                // 如果是满减活动，此处1只是DEMO 并非固定设置
                case 1:
                    $v['promotion_price'] = Trend_Promotion_Logical_Manjian::run($v['sku'], $value['promotion_type']);
                    $totol_price += $v['promotion_price'];
                    break;
                /* 读取设置，判断是否允许叠加全场活动，若允许，则计算叠加全场活动之后的最终价格 */
                case self::SETTING_GROUP_PROMOTION:
                    $tag = true;
                    break;
                default:
                    break;
            }
        }
        
        /* 全场活动tag */
        if ($tag) {
            // 查询全场活动
            
            // 检查当前全场活动的所属类文件是否存在
            
            // 调用活动所属类文件，并计算最终价格
        
        }
        
        return null;
    }
}

// End ^ Native EOL ^ UTF-8