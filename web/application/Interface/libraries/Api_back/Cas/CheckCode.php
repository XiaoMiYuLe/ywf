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
class Api_Cas_CheckCode
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    protected static $_allowFields = array('send_to', 'action', 'code');

    /**
     * 接口运行方法
     *
     * @param string $params
     * @throws Zeed_Exception
     * @return string Ambigous multitype:number, multitype:number string ,
     *         unknown, multitype:>
     */
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] === 0) {
            try {
                /* 判断手机格式 */
                Util_Validator::test_mobile($res['data']['send_to'],"请填写正确的手机号码");
                
                //手机号是否注册
                if ($res['data']['action'] == 1) {
                    $user = Cas_Model_User::instance()->fetchByWhere("phone = '{$res['data']['send_to']}'");
                    if ($user) {
                        throw new Zeed_Exception('该手机号已被注册');
                    }
                }
                
                $where = " send_to = '{$res['data']['send_to']}' AND action = '{$res['data']['action']}' AND code = '{$res['data']['code']}'";
                $order = ' ctime desc';
                $code = Cas_Model_Code::instance()->fetchByWhere($where, $order, 1);
                
                if(!empty($code)){
                    if($code[0]['code']!=$res['data']['code']){
                        throw new Zeed_Exception('短信验证码不正确，请检查输入或重新获取');
                    }
                    
                    if($code[0]['code']==$res['data']['code']){
                        if (strtotime("-1800 seconds") > strtotime($code[0]['ctime'])) {
                            throw new Zeed_Exception('验证信息已失效，请重新发起。');
                        }
                    }
                }else{
                    throw new Zeed_Exception('短信验证码不正确，请检查输入或重新获取');
                }

                $res['error'] = "验证通过";

            } catch (Exception $e) {
                $res['status'] = 1;
                $res['error'] = "失败。错误信息：" . $e->getMessage();
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
        if (! isset($params['send_to']) || ! $params['send_to']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 send_to未提供';
            return self::$_res;
        }
        if (! isset($params['action']) || ! $params['action']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 action未提供';
            return self::$_res;
        }
        if (! isset($params['code']) || ! $params['code']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 code未提供';
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
