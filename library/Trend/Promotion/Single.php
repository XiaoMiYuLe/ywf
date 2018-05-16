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
class Trend_Promotion_Single extends Trend_Promotion_Abstract
{
    /**
     * 优惠促销活动的价格处理 - 源自商品单品
     *
     * @param string $content_id 商品 ID
     * @param integer $userid 用户 ID
     * @return float
     */
    public static function single($content_id, $userid = null)
    {
        return parent::saleoff($content_id, $userid);
    }
}

// End ^ Native EOL ^ UTF-8