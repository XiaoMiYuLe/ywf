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
 * 获取用户明片
 */
class Api_Cas_GetUserAvatar
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		/* 检查用户是否存在 */
                $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['userid']}'");
                if (!$userExists) {
                    throw new Zeed_Exception('该用户不存在');
                }
                 
                /* 检查用户状态 */
                if($userExists[0]['status'] == 1 ){
                    throw new Zeed_Exception('该账号已禁用');
                }
                
                $userExists = current($userExists);

                $res['data']['avatar'] = Support_Image_Url::getImageUrl($userExists['avatar']);
                $res['data']['remarks'] = $userExists['remarks'];
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取名片失败。错误信息：' . $e->getMessage();
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
        if (! isset($params['userid']) || ! strlen($params['userid'])) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '用户id未提供';
            return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
