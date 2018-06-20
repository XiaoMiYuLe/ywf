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

var_dump("test");
return array(
    /* 基础部分 */
    'Wap.CheckVersion' => array('class' => 'Api_Wap_CheckVersion'), // 检查版本
    'Trend.GetRegionByPid' => array('class' => 'Api_Trend_GetRegionByPid'), // 获取子级地区

    /* 广告模块 */
    'Advert.GetBoard' => array('class' => 'Api_Advert_GetBoard'),
    'Advert.GetPages' => array('class' => 'Api_Advert_GetPages'),
    'Advert.GetContent' => array('class' => 'Api_Advert_GetContent'),
    /* 消费模块 */
    'Bts.CartAdd' => array('class' => 'Api_Bts_Cart_Add'),
    'Bts.CartEdit' => array('class' => 'Api_Bts_Cart_Edit'),
    'Bts.CartDelete' => array('class' => 'Api_Bts_Cart_Delete'),
    'Bts.CartGetAll' => array('class' => 'Api_Bts_Cart_GetAll'),

    /*反馈模块*/
    'Feedback.Add' => array('class' => 'Api_Feedback_Add'),
    /* 用户模块 */
    'Cas.SendCode' => array('class' => 'Api_Cas_SendCode'), // 发送验证码
    'Cas.CheckCode' => array('class' => 'Api_Cas_CheckCode'), // 校验验证码
    'Cas.Signup' => array('class' => 'Api_Cas_Signup'), // 注册
    'Cas.Login' => array('class' => 'Api_Cas_Login'), // 登录
    'Cas.ForgotPassword' => array('class' => 'Api_Cas_ForgotPassword'), // 找回密码
    'Cas.UpdateAvatar' => array('class' => 'Api_Cas_UpdateAvatar'),// 修改头像
    'Cas.UpdateInfo' => array('class' => 'Api_Cas_UpdateInfo'), // 修改用户信息
    'Cas.ResetPassword' => array('class' => 'Api_Cas_ResetPassword'), // 重置密码
    'Cas.GetUserAvatar' => array('class' => 'Api_Cas_GetUserAvatar'), // 获取用户头像
    'Cas.GetUserInfo' => array('class' => 'Api_Cas_GetUserInfo'), // 获取用户信息
    /* 团购抢购模块 */
    'Groupon.GetCategory' => array('class' => 'Api_Groupon_GetCategory'),
    'Groupon.GetBulk' => array('class' => 'Api_Groupon_GetBulk'),
    'Groupon.GetGrab' => array('class' => 'Api_Groupon_GetGrab'),
    /* 评论模块 */
    'Comment.AddGoods' => array('class' => 'Api_Comment_Goods_Add'),
    'Comment.GetCategory' => array('class' => 'Api_Comment_GetCategory'),
    'Comment.GetListGoods' => array('class' => 'Api_Comment_Goods_GetList'),
    /*商品模块*/
    'Goods.GetBrand' => array('class' => 'Api_Goods_GetBrand'),
    'Goods.GetCategory' => array('class' => 'Api_Goods_GetCategory'),
    'Goods.GetGoodsContent' => array('class' => 'Api_Goods_GetGoodsContent'),
    'Goods.GetGoodsList' => array('class' => 'Api_Goods_GetGoodsList'),
    /* 优惠券模块 */
    'Coupon.FetchAll' => array('class' => 'Api_Coupon_FetchAll'), //获得所有有效的优惠券
    'Coupon.GetCoupon' => array('class' => 'Api_Coupon_GetCoupon'), // 用户领取优惠券
    'Coupon.FetchByUserid' => array('class' => 'Api_Coupon_FetchByUserid'), //用户中心展示优惠券
    /* 消费模块 */
    'Bts.Refund' => array('class' => 'Api_Bts_Order_Refund'),
    'Bts.Delete' => array('class' => 'Api_Bts_Order_Delete'),
    'Bts.ListOrder' => array('class' => 'Api_Bts_Order_List'),
    'Bts.FastCreat' => array('class' => 'Api_Bts_Order_FastCreate'),
    'Bts.Cancel' => array('class' => 'Api_Bts_Order_Cancel'),
    'Bts.CreatOrder' => array('class' => 'Api_Bts_Order_Create'),
    'Bts.Groupon' => array('class' => 'Api_Bts_Order_Groupon'),
);
// End ^ Native EOL ^ encoding
