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
class Trend_Promotion_Logical_Manjian extends Trend_Promotion_Abstract
{
    /**
     * 满减活动类型
     *
     * @param integer $content_id 商品 ID
     * @param integer $promotion_id 具体的活动 ID
     * @return float
     */
    public static function run($sku, $promotion_id)
    {
        /* 若没有指定商品或活动，则直接返回 */
        if (! $content_id || ! $promotion_id) {
            return 0;
        }
        
        /* 获取商品信息 */
        $goods = Goods_Model_Content::instance()->fetchByPK($content_id);
        if (empty($goods)) {
            return 0;
        }
        
        /* 获取活动规则信息 */
        $promotion = Promotion_Model_Content::instance()->fetchByPK($promotion_id);
        if (empty($promotion)) {
            return 0;
        }
        
        /* 根据规则计算优惠之后的商品价格 */
        
    }
}

// End ^ Native EOL ^ UTF-8