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
 * @since      2010-7-22
 * @version    SVN: $Id$
 */
abstract class Support_Sms_Sioo_Send extends Support_Sms_Send
{
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    
    /**
     * 希奥科技 - 及时通短信接口
     *
     * @param string $phone 发送对象手机号码
     * @param string $msg 要发送的内容
     * @return array
     */
    public static function run ($phone, $msg)
    {
        $url = "http://210.5.158.31/hy"; // 接口地址
        $params = array(
                'uid' => "80078",
                'auth' => "16b395ab64ba4c854c4d3da6f4dd1b7f", // 签权验证
                'mobile' => $phone, // 手机号码，同时发送给多个号码，号码间用逗号分隔
                'expid' => 0, // 拓展码无效(1-999)
                'msg' => iconv("UTF-8", "GB2312", $msg),
                'encode' => 'GB2312'
        );
        
        $res = file_get_contents($url . '?' . http_build_query($params, '', '&'));
        
        /* 成功返回结果例如：0,14888;截取成功时的结果0 */
        if (strpos($res, ',')) {
            $res = substr($res, 0, strpos($res, ','));
        }
        
        /* 仅供测试使用的硬代码，正常开发时，请删除该部分代码 */
        self::$_res['error'] = 'SUCESS';
        return self::$_res;
        /* 仅供测试使用的硬代码，正常开发时，请删除该部分代码 @end */
        
        /* 判断返回结果 */
        if ($res > 0 && ! empty($res)) {
            self::$_res['error'] = 'SUCESS';
        } else {
            switch ($res) {
                case -1:
                    $status = -1;
                    $error = "签权失败";
                    break;
                case -2:
                    $status = -2;
                    $error = "未检索到被叫号码";
                    break;
                case -3:
                    $status = -3;
                    $error = "被叫号码过多";
                    break;
                case -4:
                    $status = -4;
                    $error = "内容未签名";
                    break;
                case -5:
                    $status = -5;
                    $error = "内容过长";
                    break;
                case -6:
                    $status = -6;
                    $error = "余额不足";
                    break;
                case -7:
                    $status = -7;
                    $error = "暂停发送";
                    break;
                case -8:
                    $status = -8;
                    $error = "保留";
                    break;
                case -9:
                    $status = -9;
                    $error = "定时发送时间格式错误";
                    break;
                case -10:
                    $error = "下发内容为空";
                    break;
                case -11:
                    $status = -11;
                    $error = "账户无效";
                    break;
                case -12:
                    $status = -12;
                    $error = "Ip地址非法";
                    break;
                case -13:
                    $status = -13;
                    $error = "操作频率快";
                    break;
                case -14:
                    $status = -14;
                    $error = "操作失败";
                    break;
                case -15:
                    $status = -15;
                    $error = "拓展码无效(1-999)";
                    break;
                default:
                    $status = -999;
                    $error = "未知错误";
                    break;
            }
            self::$_res['status'] = $status;
            self::$_res['error'] = $error;
        }
        
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding