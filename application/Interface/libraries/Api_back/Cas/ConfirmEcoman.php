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
 * 确认经纪人加入状态
 */
class Api_Cas_ConfirmEcoman
{

    /**
     * 返回参数
     */
    protected static $_res = array(
            'status' => 0,
            'error' => '',
            'data' => ''
    );

    /**
     * 接口运行方法
     *
     * @param string $params            
     * @throws Zeed_Exception
     * @return string Ambigous multitype:number, multitype:number string ,
     *         unknown, multitype:>
     */
    public static function run ($params = null)
    {
        // 执行参数验证
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
                
                $res['data']['is_invitaiton'] = $userExists['is_invitaiton'];
                $res['data']['is_ecoman'] = $userExists['is_ecoman'];
               
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '加入经纪人出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            
        }
        return $res;
    }

    /**
     * 验证参数
     *
     * @param array $params            
     * @throws Zeed_Exception
     */
    public static function validate ($params)
    {
    	/*校验参数*/
        if (! isset($params['userid']) || !$params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 userid 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
