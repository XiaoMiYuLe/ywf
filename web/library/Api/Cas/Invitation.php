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

/**
 * 邀请客户成为经纪人
 */
class Api_Cas_Invitation
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_allowFields = array('userid','to_userid');
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
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
    	        
    	        /* 检查客户是否存在 */
    	        $clientExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['to_userid']}'");
    	        if (!$clientExists) {
    	            throw new Zeed_Exception('该客户不存在，请重新输入');
    	        }
    	         
    	        /* 检查客户状态 */
    	        if($clientExists[0]['status'] == 1 ){
    	            throw new Zeed_Exception('该客户已禁用');
    	        }
    	        
    	        $rs = Cas_Model_User::instance()->update(array('is_invitaiton'=>1,'mtime'=>date("Y-m-d H:i:s")), "userid= '{$res['data']['to_userid']}'");
    	        
    	        if(empty($rs)){
    	            throw new Zeed_Exception('发送邀请失败');
    	        }
    	        
    	        $content1 = "尊敬的客户：您的推荐人邀请您加入财富经纪人，请登录app进行操作，感谢您的使用。";
    	        $gets = Sms_SendSms::testSingleMt1('86'.$clientExists[0]['phone'], $content1);
    	        
    	        $data = array(
    	            'type' => 'phone',
    	            'action'=>'5',
    	            'send_to'=>$clientExists[0]['phone'],
    	            'content'=>$content1,
    	            'ctime'=>date(DATETIME_FORMAT),
    	        );
    	        
    	        $ID = Cas_Model_Code::instance()->addForEntity($data);
    	        
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '发送邀请失败。错误信息：' . $e->getMessage();
        		return $res;
        	}
        }
        return $res;
    }
    
    /**
     * 验证参数
     */
    public static function validate($params)
    {
        /* 校验必填项 */
        if (! isset($params['userid']) || ! $params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '用户id未提供';
            return self::$_res;
        }
        
        if (! isset($params['to_userid']) || ! $params['to_userid']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '客户id未提供';
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

// End ^ Native EOL ^ encoding
