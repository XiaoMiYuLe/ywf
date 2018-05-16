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
class Api_Cas_GetShare
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    protected static $_allowFields = array('userid');
	/* 根据用户手机号登录 */
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] === 0) {
            try {
                /* 检查用户是否存在 */
                $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['userid']}'");
                if (!$userExists) {
                    throw new Zeed_Exception('该用户不存在，请重新输入');
                }
                 
                /* 检查用户状态 */
                if($userExists[0]['status'] == 1 ){
                    throw new Zeed_Exception('该账号已禁用，请重新输入');
                }
                 
                $userExists = $userExists[0];
                
                $url = Zeed_Config::loadGroup('urlmapping');
                $img_url= $url['upload_cdn'].'/static/cas/img/'.'timg.jpg';
                $res['data']['img_url'] = $img_url;//图片地址
                $res['data']['content'] = '亿万福可以1000元起投，平均8%预期年化收益，财富增值无限，赶紧试试吧!';//分享内容
                $res['data']['title'] = '亿万福，让幸福像花儿般绽放';//分享标题
                $res['data']['link'] = $url['store_url_login'].'/cas/signup/index?user_code='.$userExists['user_code'];
                if(empty($res['data'])){
                    $res['data'] = array();
                }
            } catch (Exception $e) {
                $res['status'] = 1;
                $res['error'] = "错误信息：" . $e->getMessage();
                return $res;
            }
        }
        
        return $res;
    }
    
    
    /**
     * 验证参数
     */
    public static function validate ($params)
    {
        if (! isset($params['userid']) || ! $params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '用户id未提供';
            return self::$_res;
        }
        /* 组织数据 */
        $set = array();
        foreach (self::$_allowFields as $f) {
            $set[$f] = isset($params[$f]) ? $params[$f] : null;
        }
        self::$_res['data'] = $set;
        
        return self::$_res;
    }
}