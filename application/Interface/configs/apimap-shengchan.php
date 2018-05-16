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
return array(
        /* 基础部分 */
        'Wap.CheckVersion' => array('class' => 'Api_Wap_CheckVersion'), // 检查版本
        'Trend.GetRegionByPid' => array('class' => 'Api_Trend_GetRegionByPid'), // 获取子级地区

        /* 广告模块 */
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
        'Cas.UpdateInfo'=>array('class'=>'Api_Cas_UpdateInfo'), // 添加联系方式
        'Cas.ResetPassword'=>array('class'=>'Api_Cas_ResetPassword'), // 重置密码
        'Cas.GetUserAvatar'=>array('class'=>'Api_Cas_GetUserAvatar'), // 获取用户头像
        'Cas.GetUserInfo'=>array('class'=>'Api_Cas_GetUserInfo'), // 获取用户信息
        'Cas.SetPaypwd'=>array('class'=>'Api_Cas_SetPaypwd'),//设定交易密码
        'Cas.ResetPaypwd'=>array('class'=>'Api_Cas_ResetPaypwd'),//修改交易密码
        'Cas.ForgotPaypwd'=>array('class'=>'Api_Cas_ForgotPaypwd'),//忘记交易密码
        'Cas.UpdateRecommender'=>array('class'=>'Api_Cas_UpdateRecommender'),//修改推荐人
        'Cas.GetAgentInfo'=>array('class'=>'Api_Cas_GetAgentInfo'),//经纪人信息
        'Cas.GetCustomReCord'=>array('class'=>'Api_Cas_GetCustomReCord'),//获取客户交易记录
        'Cas.GetUserMoneyDetail'=>array('class'=>'Api_Cas_GetUserMoneyDetail'),//获取用户金额页面信息
        'Cas.GetMytotalassets'=>array('class'=>'Api_Cas_GetMytotalassets'),//我的总资产
        'Cas.AddEcoman'=>array('class'=>'Api_Cas_AddEcoman'),//添加经纪人
        'Cas.MyBankcard'=>array('class'=>'Api_Cas_MyBankcard'),//我的银行卡
        'Cas.BankcardList'=>array('class'=>'Api_Cas_BankcardList'),//银行卡列表
        
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
        'Goods.GetGoodsDetail' => array('class' => 'Api_Goods_GetGoodsDetail'),
        'Goods.GetGoodsList' => array('class' => 'Api_Goods_GetGoodsList'),
        'Goods.GetHotGoods' => array('class' => 'Api_Goods_GetHotGoods'),
        'Goods.BuyRecord' => array('class' => 'Api_Goods_BuyRecord'),
        'Goods.ConfirmInvestment' => array('class' => 'Api_Goods_ConfirmInvestment'),
        'Goods.BuyRecordReal' => array('class' => 'Api_Goods_BuyRecordReal'),
        
        /*订单模块*/
        'Order.GetOrderList' => array('class' => 'Api_Order_GetOrderList'),
        'Order.MakeOrder' => array('class' => 'Api_Order_MakeOrder'),
        'Order.CancelOrder' => array('class' => 'Api_Order_CancelOrder'),
        'Order.GetUserfulVoucher' => array('class' => 'Api_Order_GetUserfulVoucher'),
        'Order.GetOrderDetail' => array('class' => 'Api_Order_GetOrderDetail'),
        
        /* 用户模块 */
        'Cas.SendCode' => array('class' => 'Api_Cas_SendCode'), // 发送验证码
        'Cas.Signup' => array('class' => 'Api_Cas_Signup'), // 注册
        'Cas.Login' => array('class' => 'Api_Cas_Login'), // 登录
        'Cas.ForgotPassword' => array('class' => 'Api_Cas_ForgotPassword'), // 找回密码
        'Cas.GetUserVoucher' => array('class' => 'Api_Cas_GetUserVoucher'),//获取优惠券列表
        'Cas.GetUserBrokerage' => array('class' => 'Api_Cas_GetUserBrokerage'),//获取用户佣金
        'Cas.GetRecordLog' => array('class' => 'Api_Cas_GetRecordLog'),//获取用户资金流水详情
        'Cas.GetClientList' => array('class' => 'Api_Cas_GetClientList'),//获取用户客户列表
        'Cas.GetEarnings' => array('class' => 'Api_Cas_GetEarnings'),//获取用户收益记录
        'Cas.GetJudgeVoucher' => array('class' => 'Api_Cas_GetJudgeVoucher'),//获取用户可用代金券
        'Cas.GetUserBalance' => array('class' => 'Api_Cas_GetUserBalance'),//获取用户账户余额
        'Cas.GetHelpList' => array('class' => 'Api_Cas_GetHelpList'),//获取帮助列表
        'Cas.GetHelpContent' => array('class' => 'Api_Cas_GetHelpContent'),//获取帮助详情
        'Cas.GetNewsList' => array('class' => 'Api_Cas_GetNewsList'),//获取消息列表
        'Cas.GetNewsContent' => array('class' => 'Api_Cas_GetNewsContent'),//获取消息内容
        'Cas.GetNewNews' => array('class' => 'Api_Cas_GetNewNews'),//获取首页消息
        'Cas.WithdrawDeposit' => array('class' => 'Api_Cas_WithdrawDeposit'),//记录用户提现
        'Cas.GetErCode' => array('class' => 'Api_Cas_GetErCode'),//获取二维码信息
        'Cas.GetWithdrawNumber' => array('class' => 'Api_Cas_GetWithdrawNumber'),//判断用户是否需要支付手续费
        
        'Trend.GetVersion' => array('class' => 'Api_Trend_GetVersion'),//版本信息接口
        
        
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
        
        /* 单页模块  */
        'System.GetAgreement' => array('class' => 'Api_System_GetAgreement'),
        
        /* 融宝相关接口模块  */
        'Pay.Debit' => array('class' => 'Api_Pay_Debit'),
        'Pay.Pay' => array('class' => 'Api_Pay_Pay'),
        'Pay.BindCard' => array('class' => 'Api_Pay_BindCard'),
        'Pay.Search' => array('class' => 'Api_Pay_Search'),
        'Pay.BindCard2' => array('class' => 'Api_Pay_BindCard2'),
        'Pay.ReSendSms' => array('class' => 'Api_Pay_ReSendSms'),
        'Pay.TestCallBack' => array('class' => 'Api_Pay_TestCallBack'),
        'Pay.Iscallback' => array('class' => 'Api_Pay_Iscallback'),
        'Pay.Iskamiok' => array('class' => 'Api_Pay_Iskamiok'),
        'Pay.BindCard2' => array('class' => 'Api_Pay_BindCard2'),
        'Pay.Certificate' => array('class' => 'Api_Pay_Certificate'),
         /*余额支付*/
        'Pay.BalancePaid' => array('class' => 'Api_Pay_BalancePaid'),
        /*电子合同*/
        'Cas.ElectronicContract'=>array('class'=>'Api_Cas_ElectronicContract'),
        /*借款合同*/
        'Cas.Aggrement'=>array('class'=>'Api_Cas_Aggrement'),
        /*分享接口*/
        'Cas.GetShare'=>array('class'=>'Api_Cas_GetShare'),
);
// End ^ Native EOL ^ encoding
