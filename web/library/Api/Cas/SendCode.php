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

class Api_Cas_SendCode
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    protected static $_allowFields = array('send_to','action');
    private static $_type = 'phone'; // 定义类型默认为短信
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
                /* 判断手机号格式 */
                Util_Validator::test_mobile($res['data']['send_to'],"请填写正确的手机号码");
                
                $res['data']['type'] = self::$_type;
				$res['data']['code'] = rand(100000,999999);
				$res['data']['ctime'] = date(DATETIME_FORMAT);
				/*手机号是否注册*/
				if ($res['data']['action'] == 1) {
					$user = Cas_Model_User::instance()->fetchByWhere("phone = '{$res['data']['send_to']}'");
					if ($user) {
						throw new Zeed_Exception('该手机号已被注册');
					}
 					//$gets = Sms_SendSms::testSingleMt($res['data']['send_to'], "您本次的注册验证码是：".$res['data']['code']."。此验证码只用于注册，请勿将验证码告知他人");
					$content1 = "尊敬的用户，您的验证码为".$res['data']['code']."，本验证码30分钟内有效，感谢您的使用。";
					$gets = Sms_SendSms::testSingleMt('86'.$res['data']['send_to'], $content1);
				} 
 				elseif ($res['data']['action'] == 2) {
 				    $user = Cas_Model_User::instance()->fetchByWhere("phone = '{$res['data']['send_to']}'");
 				    if (empty($user)) {
 				        throw new Zeed_Exception('当前手机号还未进行注册');
 				    }
 					//$gets = Sms_SendSms::testSingleMt($res['data']['send_to'],"确认码".$res['data']['code']."，30分钟内有效，用户找回登录密码。请勿向任何人泄露您的短信验证码。");
 				    $content2 = "尊敬的用户，您的验证码为".$res['data']['code']."，本验证码30分钟内有效，感谢您的使用。";
 				    $gets = Sms_SendSms::testSingleMt('86'.$res['data']['send_to'],$content2);
 				} elseif ($res['data']['action'] == 3) {
 					//$gets = Sms_SendSms::testSingleMt($res['data']['send_to'],"您的验证码是：".$res['data']['code']."，此验证码只用于重置交易密码，请勿将验证码告知他人");
 				    $content3 = "尊敬的用户，您的验证码为".$res['data']['code']."，本验证码30分钟内有效，感谢您的使用。";
 				    $gets = Sms_SendSms::testSingleMt('86'.$res['data']['send_to'],$content3);
 				} elseif ($res['data']['action'] == 4) {
 					//$gets = Sms_SendSms::testSingleMt($res['data']['send_to'],"您的验证码是：".$res['data']['code']."，此验证码只用于更改提现，请勿将验证码告知他人");
 				    $content4 = "尊敬的用户，您的验证码为".$res['data']['code']."，本验证码30分钟内有效，感谢您的使用。";
 				    $gets = Sms_SendSms::testSingleMt('86'.$res['data']['send_to'],$content4);
 				}
				
				
				$id = Cas_Model_Code::instance()->addForEntity($res['data']);
				if (!$id) {
					throw new Zeed_Exception('发送失败');
				}
				
            } catch (Exception $e) {
                $res['status'] = 1;
                $res['error'] = "获取验证码失败。错误信息：" . $e->getMessage();
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
        if (! isset($params['action']) || !$params['action']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 action未提供';
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
