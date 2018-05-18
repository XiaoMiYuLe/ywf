<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */
class TestController extends IndexAbstract
{
    /**
     * 接口测试
     */
    public function index()
    {
        $m = $this->input->get('m');
        $code = $this->input->get('code');
        $curl = 'http://sjq.yumzeed.lo.trac.cn/api';
        $method = 'POST';
        $secret = 'O]dWJ,[*g)%k"?q~g6Co!`cQvV>>Ilvw';

        $app = 'Cas'; // 修改这里 - 模块名
        switch ($m) {
            case "sendcode":
                $class = 'SendCode';
                $sign = md5($app . $class . $secret);
                $request = array(
                    'app' => $app,
                    'class' => $class,
                    'sign' => $sign,
                    'action' => 'register',
                    'sendto' => '13917253627'
                );

                break;
            case "signup":
                $class = 'Signup';
                $sign = md5($app . $class . $secret);
                $request = array(
                    'app' => $app,
                    'class' => $class,
                    'sign' => $sign,
                    'simple' => '2',
                    'username' => '13917253627',
                    'password' => '123456',
                    'code' => $code
                );
                break;
            case "login":
                $class = 'Login';
                $sign = md5($app . $class . $secret);
                $request = array(
                    'app' => $app,
                    'class' => $class,
                    'sign' => $sign,
                    'simple' => '2',
                    'username' => '13917253627',
                    'password' => '123456',
                    'code' => $code
                );
                break;
            case "forgotpassword":
                $class = 'ForgotPassword';
                $sign = md5($app . $class . $secret);
                $request = array(
                    'app' => $app,
                    'class' => $class,
                    'sign' => $sign,
                    'phone' => '13917253627',
                    'password' => '123456',
                    'repassword' => '123456',
                    'code' => $code
                );
                break;
            case "restpassword":
                $class = 'ResetPassword';
                $sign = md5($app . $class . $secret);
                $request = array(
                    'app' => $app,
                    'class' => $class,
                    'sign' => $sign,
                    'token' => 'f42faf8086725c7a72c3e281c5137d9d',
                    'password' => '123456',
                    'old_password' => '1234567',
                    'repassword' => '123456'
                );
                break;
            case "updateinfo":
                $class = 'UpdateInfo';
                $sign = md5($app . $class . $secret);
                $request = array(
                    'app' => $app,
                    'class' => $class,
                    'sign' => $sign,
                    'token' => 'f42faf8086725c7a72c3e281c5137d9d',
                    'nickname' => 'George',
                    'address' => '地址'
                );
                break;
            case "getuserinfo":
                $class = 'GetUserInfo';
                $sign = md5($app . $class . $secret);
                $request = array(
                    'app' => $app,
                    'class' => $class,
                    'sign' => $sign,
                    'token' => 'f42faf8086725c7a72c3e281c5137d9d',
                );
                break;
            case "getuseravatar":
                $class = 'GetUserAvatar';
                $sign = md5($app . $class . $secret);
                $request = array(
                    'app' => $app,
                    'class' => $class,
                    'sign' => $sign,
                    'token' => 'f42faf8086725c7a72c3e281c5137d9d',
                );
                break;
            case "checkcode":
                $class = 'CheckCode';
                $sign = md5($app . $class . $secret);
                $request = array(
                    'app' => $app,
                    'class' => $class,
                    'sign' => $sign,
                    'token' => 'f42faf8086725c7a72c3e281c5137d9d',
                    'sendto' => '13917253627',
                    'action' => 'register'
                );
                break;
            case "push":
                $extra = json_encode(array("id" => "1"));
//                var_dump($extra);
//                var_dump(Push_Jpush::send('test','test','all',$extra));
                var_dump(Push_Jpush::sendTag('tag,sss', '测试－沈嘉麒', '标题'));
                exit;
                break;
            case "getdevice":
                var_dump(Push_Jpush::getDevice("1"));
                exit;
                break;
            case "setdevice":
                var_dump(Push_Jpush::setDevice("1"));
                exit;
                break;
            default :
                break;
        }
//        $class = 'Signup'; // 修改这里 - 类名

//        $request = array(
//            'app' => $app,
//            'class' => $class,
//            'sign' => $sign,
//
//            /* 修改这里 - 下方为接口所要传递的参数 */
//            /* 发送验证码 */
//            'action'=>'register',
//            'sendto'=>'13917254647',
//            /* 注册 */
//            'simple' => '2',
//            'username' => '13917254647',
//            'password'=>'123456',
//            'code'=>'531213'
//
//                 'simple' => '2',
//                'sendto' => 'test123',
//                'phone' => '13874875488',
//                'password' => '123456',
//                 'repassword' => '123456',
//                'code' => '778513',
//                 'email' => '5867544@qq.com',
//                 'idcard' => '123456789012345678',
//                 'realname' => '简单',
//                 'company_name' => '简单科技有限公司',
//        );
        $method = $method ? $method : 'GET';
        $userAgent = 'TRAVELLER HTTP CLIENT ';
        $ch = curl_init();

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $curl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        } else {
            $curl .= '?' . $request;
            curl_setopt($ch, CURLOPT_URL, $curl);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $result = curl_exec($ch);
        curl_close($ch);

//     	$tt = json_decode($result, true);

        Zeed_Benchmark::print_r($result);

    }
}

// End ^ Native EOL ^ UTF-8