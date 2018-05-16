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
 * 获取有效的代金券
 */
class Api_Order_GetUserfulVoucher
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
            	/*检测该用户是否有效*/
            	if (!$user = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
            		throw new Zeed_Exception("该用户不存在或已被冻结");
            	}
            	
            	/*查询有效的代金券*/
            	$now = date(DATETIME_FORMAT);
            	$voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("userid = {$res['data']['userid']} and voucher_status = 1 and valid_data > '{$now}'");
            	if (!$voucher) {
            		$data['is_status'] = 0;
            	} else {
            		$data['content'] = $voucher;
            		$data['is_status'] = 1;
            	}
            	$res['data'] = $data;
            	
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '查询代金券出错。错误信息：' . $e->getMessage();
                return $res;
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
    	if (!$params['userid'] || strlen($params['userid'])<1) {
    		self::$_res['status'] = 1;
    		self::$_res['error'] = '参数用户ID userid 未提供';
    		return self::$_res;
    	}
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
