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
class Api_Cas_ForgotPassword
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    protected static $_allowFields = array('phone', 'code', 'newpassword');

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
                Util_Validator::test_mobile($res['data']['phone'],"请填写正确的手机号码");
                
                //手机号是否注册
                    $user = Cas_Model_User::instance()->fetchByWhere("phone = '{$res['data']['phone']}'");
                    if (empty($user)) {
                        throw new Zeed_Exception('当前手机号还未进行注册');
                    }
                
                $where = " send_to = '{$res['data']['phone']}' AND action = 2";
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
                
                /* 新密码格式 */
                Util_Validator::test_pwd(trim($res['data']['newpassword']),"密码必须为6-16位，同时包含数字和字母两种,113");
                
                //重新加密
                $data['salt'] = Zeed_Util::genRandomString(10);
                $data['encrypt'] = 'Md5Md5';
                $data['password'] = self::encrypt($res['data']['newpassword'],$data['salt']);

                //重置密码
                $rs = Cas_Model_User::instance()->updateForEntity($data, $user[0]['userid']);
                if(empty($rs)){
                    throw new Zeed_Exception('重置密码失败');
                }else{
                    //成功删除验证码
                    $where_code = $where = " send_to = '{$user[0]['phone']}' AND action = 2 and code='{$res['data']['code']}'";
                    Cas_Model_Code::instance()->delete($where_code);
                } 
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
        if (! isset($params['phone']) || ! $params['phone']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 phone未提供';
            return self::$_res;
        }
        if (! isset($params['code']) || ! $params['code']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 code未提供';
            return self::$_res;
        }
        if (! isset($params['newpassword']) || ! $params['newpassword']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 resetpassword未提供';
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
    
    /*
     * 加密算法
     */
    public static function encrypt($str='',$salt='')
    {
        return MD5(MD5($str).$salt);
    }
    
    /*
     * 生成邀请码:随机生成数字字母组合，采用
     */
    public static function getRandomString($len, $chars=null)
    {
        if (is_null($chars)){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        }
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
}

// End ^ Native EOL ^ encoding
