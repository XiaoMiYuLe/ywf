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
 * 确认投资状态
 */
class Api_Goods_ConfirmInvestment
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
                
                
                
                //绑卡的状态
                if(!empty($userExists['bank_id'])){
                    $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$userExists['bank_id']}' and is_use=1 and is_del=0");
                    if(!empty($bank_info)){
                           $data['is_tiecard'] = 1;
                    }else{
                        $data['is_tiecard'] = 0;
                    }
                }else{
                    $data['is_tiecard'] = 0;
                }
                
                //交易密码
                if (!empty($userExists['pay_pwd'])) {
                   $data['is_pay_pwd'] = 1;
                }else{
                    $data['is_pay_pwd'] = 0;
                }
                
                //推荐人是否为经纪人
				if ($userExists['parent_id']) {
					$cas = Cas_Model_User::instance()->fetchByWhere("userid = {$userExists['parent_id']} and is_ecoman=1");
					if (!empty($cas)) {
					    $data['parentis_ecoman'] = 1;
					} else {
					    $data['parentis_ecoman'] = 0;
					}
                }else{
                    $data['parentis_ecoman'] = 2;
                }
                
                //自己是否为经纪人
                $data['is_ecoman'] = (int)$userExists['is_ecoman'];
                
                //是否有上限
                if(!empty($userExists['parent_id'])){
                    $data['parent'] = 1;
                }else{
                    $data['parent'] = 0;
                }
                
                $data['userid'] = $userExists['userid'];
                $data['is_buy'] = $userExists['is_buy'];
                
                $res['data'] = $data;
               
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取产品详情出错。错误信息：' . $e->getMessage();
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
            self::$_res['error'] = '参数 goods_id 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
