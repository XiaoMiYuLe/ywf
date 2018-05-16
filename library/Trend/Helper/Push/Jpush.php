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
 * @since 2011-10-26
 * @version SVN: $Id$
 */
 
/**
 * 极光推送
 */
class Trend_Helper_Push_Jpush
{
    protected static $_url = 'https://api.jpush.cn/v3/push';
    protected static $_appkeys = '5064de06323cb17255b8a700';
    protected static $_masterSecret = 'd8355fefe0f8f56b98137f81';
 
    /**
     * 推送信息
     * 
     * @param string $title 推送标题
     * @param string $message 推送消息
     * @param string|array $registration_id 设备号
     * @param array $extras 扩展字段
     * @return array
     */
    public static function send($title, $message, $registration_id, $extras = null)
    {
        /* 确认推送设备 */
        if ($registration_id == 'all') { // 广播，推送到所有设备
            $audience = 'all';
        } else { // 推送到指定的设备号
            $audience = array(
                    'registration_id' => is_array($registration_id) ? $registration_id : explode(',', $registration_id)
            );
        }
        
        /* 构造提交参数 */
        $param = array(
                'platform' => 'all',
                'audience' => $audience,
                'notification' => array(
                        'android' => array(
                                'alert' => $message,
                                'title' => $title,
                                'builder_id' => 1,
                                'extras' => $extras
                        ),
                        'ios' => array(
                                'alert' => $message,
                                'sound' => 'default',
                                'title' => $title,
                                'badge' => '+1',
                                'extras' => $extras
                        )
                )
        );
        $param = json_encode($param);
        
        /* 发送 */
        $res = self::request_post($param);
        return json_decode($res, true);
    }
    
    /**
     * 模拟提交推送消息
     * 
     * @param array $param 提交推送的参数
     * @return array
     */
    private static function request_post($param = null)
    {
        if (empty($param)) {
            return false;
        }
        
        $basevar = self::$_appkeys . ':' . self::$_masterSecret;
        $base64 = base64_encode($basevar);
        $header = array("Authorization:Basic $base64", "Content-Type:application/json");
    
        $postUrl = self::$_url;
        $curlPost = $param;
    
        $ch = curl_init(); // 初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); // 抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); // 设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); // post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        
        // 增加 HTTP Header（头）里的字段
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        // 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        
        $data = curl_exec($ch); // 运行curl
         
        curl_close($ch);
        
        return $data;
    }
}

// End ^ Native EOL ^ UTF-8