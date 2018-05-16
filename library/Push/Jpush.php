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
class Push_Jpush
{

    /**
     * 推送信息
     *
     * @param array $param 参数
     * @return array
     */
    public static function send($param)
    {

        /* 构造提交参数 */
        if (is_array($param)) {
            $param = json_encode($param);
        }

        /* 发送 */
        $config = self::_getPushConfig();
        /* 发送 */

        $res = self::request_post($config['push_url'], $param);
        return json_decode($res, true);
    }

    /**
     * 广播推送
     *
     * @param string $message 推送消息
     * @param null|string $title 推送标题
     * @param null|array $extras 扩展字段
     * @return array
     */
    public static function sendAll($message, $title = null, $extras = array())
    {
        /* 构造提交参数 */
        $param = array(
            'platform' => 'all',
            'audience' => 'all',
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
                    'badge' => 1,
                    'extras' => $extras
                )
            ),
            'message'=>array(
                "msg_content" => "Hi,JPush",
                "content_type" => "text",
                "title" => "msg",
                "extras" => array(
                    "key"=>"value",
                )
            ),
            'options' => array(
                'sendno' => rand(100000, 999999),
                'apns_production' => Zeed_Config::loadGroup('push.jpush.apns_production')
            )
        );
        $param = json_encode($param);

        $config = self::_getPushConfig();
        /* 发送 */
        $res = self::request_post($config['push_url'], $param);
        return json_decode($res, true);
    }

    /**
     * 根据registration_id推送
     *
     * @param array $registration_id 设备ID
     * @param string $message 推送消息
     * @param null|string $title 推送标题
     * @param null|array $extras 扩展字段
     * @return array
     */
    public static function sendRegistrationId($registration_id, $message, $title = null, $extras=array())
    {
        if (is_string($registration_id)) {
            $registration_id = explode(',', $registration_id);
        }
        $registrationIdParams["registration_id"] = $registration_id;

        /* 构造提交参数 */
        $param = array(
            'platform' => 'all',
            'audience' => $registrationIdParams,
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
            ),
            'message'=>array(
                "msg_content" => "Hi,JPush",
                "content_type" => "text",
                "title" => "msg",
                "extras" => array(
                    "key"=>"value",
                )
            ),
            'options' => array(
                'sendno' => rand(100000, 999999),
                'apns_production' => false,   //false 开发环境 true 生产环境
            )
        );
        $param = json_encode($param);

        $config = self::_getPushConfig();
        /* 发送 */
        $res = self::request_post($config['push_url'], $param);
        return json_decode($res, true);
    }

    /**
     * 根据tag标签推送
     *
     * @param array $tag 设备ID
     * @param string $message 推送消息
     * @param null|string $title 推送标题
     * @param null|array $extras 扩展字段
     * @return array
     */
    public static function sendTag($tag, $message, $title = null, $extras = array())
    {
        if (is_string($tag)) {
            $tag = explode(',', $tag);
        }
        $tagParams['tag'] = $tag;

        /* 构造提交参数 */
        $param = array(
            'platform' => 'all',
            'audience' => $tagParams,
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
            ),
            'options' => array(
                'sendno' => rand(100000, 999999),
                'apns_production' => Zeed_Config::loadGroup('push.jpush.apns_production')
            )
        );
        $param = json_encode($param);

        $config = self::_getPushConfig();
        /* 发送 */
        $res = self::request_post($config['push_url'], $param);
        return json_decode($res, true);
    }

    /**
     * 根据alias别名推送
     *
     * @param array $alias 设备ID
     * @param string $message 推送消息
     * @param null|string $title 推送标题
     * @param null|array $extras 扩展字段
     * @return array
     */
    public static function sendAlias($alias, $message, $title = null, $extras = array())
    {
        if (is_string($alias)) {
            $alias = explode(',', $alias);
        }
        $aliasParam['alias'] = $alias;

        /* 构造提交参数 */
        $param = array(
            'platform' => 'all',
            'audience' => $aliasParam,
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
            ),
            'options' => array(
                'sendno' => rand(100000, 999999),
                'apns_production' => Zeed_Config::loadGroup('push.jpush.apns_production')
            )
        );

        $param = json_encode($param);
        $config = self::_getPushConfig();
        /* 发送 */
        $res = self::request_post($config['push_url'], $param);
        return json_decode($res, true);
    }

    /**
     * 获取设备的tag和alias
     *
     * @param string $registration_id 设备ID
     * @return array
     */
    public static function getDevice($registration_id)
    {
        /* 构造提交参数 */
        $param = array();
        $param = json_encode($param);

        $config = self::_getPushConfig();
        $device_url = $config['device_url'] . '/' . $registration_id;
        /* 发送 */
        $res = self::request_post($device_url, $param, "GET");
        return json_decode($res, true);
    }

    /**
     * 设置tag和alias
     *
     * @param array $registration_id 设备ID
     * @param array $tags_add 标签添加
     * @param array $tags_remove 标签删除
     * @param array $alias 别名
     * @return array
     */
    public static function setDevice($registration_id, $tags_add = array(), $tags_remove = array(), $alias = null)
    {

        if (is_string($tags_add)) {
            $tags_add = explode(',', $tags_add);
        }

        if (is_string($tags_remove)) {
            $tags_remove = explode(',', $tags_remove);
        }

        /* 构造提交参数 */
        $param = array(
            "tags" => array(
                "add" => $tags_add,
                "remove" => $tags_remove
            ),
            "alias" => $alias
        );

        $param = json_encode($param);

        $config = self::_getPushConfig();
        $device_url = $config['device_url'] . '/' . $registration_id;

        /* 发送 */
        $res = self::request_post($device_url, $param, 'POST');
        return ! $res ? true : json_decode($res, true);
    }

    /**
     * 获取tag分类列表
     *
     * @return array
     */
    public static function getAllTags()
    {
        $config = self::_getPushConfig();
        $device_url = $config['tags_url'];

        /* 发送 */
        $res = self::request_post($device_url, null, "GET");
        return json_decode($res, true);
    }

    /**
     * 获取tag分类
     *
     * @param string $registration_id 设备Id
     * @return array
     */
    public static function getTags($registration_id)
    {
        $config = self::_getPushConfig();
        $device_url = $config['tags_url'] . "/{$registration_id}";

        /* 发送 */
        $res = self::request_post($device_url, null, "GET");
        return json_decode($res, true);
    }

    /**
     * 判断设备与标签的绑定
     *
     * @param string $tag 标签
     * @param string $registration_id
     * @return array
     */
    public static function checkTags($tag, $registration_id)
    {
        $config = self::_getPushConfig();
        $device_url = $config['tags_url'] . "/{$tag}/registration_ids/{$registration_id}";

        /* 发送 */
        $res = self::request_post($device_url, null, "GET");
        return json_decode($res, true);
    }

    /**
     * 设置标签
     *
     * @param string $tag
     * @param array|string $registration_ids_add
     * @param array|string $registration_ids_remove
     * @return array
     */
    public static function setTags($tag, $registration_ids_add = array(), $registration_ids_remove = array())
    {
        if (is_string($registration_ids_add)) {
            $registration_ids_add = explode(",", $registration_ids_add);
        }

        if (is_string($registration_ids_add)) {
            $registration_ids_remove = explode(",", $registration_ids_remove);
        }

        $config = self::_getPushConfig();
        $device_url = $config['tags_url'] . "/{$tag}";

        /* 构造提交参数 */
        $param = array(
            'registration_ids' => array(
                'add' => $registration_ids_add,
                'remove' => $registration_ids_remove
            ),
        );
        $param = json_encode($param);

        /* 发送 */
        $res = self::request_post($device_url, $param, "POST");
        return $res == 200 ? true : json_decode($res, true);
    }

    /**
     * 删除标签
     *
     * @param string $tag
     * @param array|string $platform
     * @return array
     */
    public static function removeTags($tag, $platform = 'android,ios')
    {
        $config = self::_getPushConfig();
        $device_url = $config['tags_url'] . "/{$tag}?platform={$platform}";

        /* 发送 */
        $res = self::request_post($device_url, null, "DELETE");
        return ! $res ? true : json_decode($res, true);
    }

    /**
     * 根据别名获取设备ID
     *
     * @param array $alias 别名
     * @return array
     */
    public static function getRegistrationIdByAlias($alias)
    {
        $config = self::_getPushConfig();
        $device_url = $config['alias_url'] . "/{$alias}";

        /* 发送 */
        $res = self::request_post($device_url, null, 'GET');
        return json_decode($res, true);
    }

    /**
     * 删除别名
     *
     * @param string $alias 别名
     * @return array
     */
    public static function removeAlias($alias)
    {

        $config = self::_getPushConfig();
        $device_url = $config['alias_url'] . "/{$alias}";

        /* 发送 */
        $res = self::request_post($device_url, null, 'DELETE');
        return ! $res ? true : json_decode($res, true);
    }

    /**
     * 模拟提交推送消息
     *
     * @param array $param 提交推送的参数
     * @param string $url 提交url
     * @return array
     */
    private static function request_post($url, $param = null, $method = null)
    {

        $pushConfig = self::_getPushConfig();
        $basevar = $pushConfig['appKey'] . ':' . $pushConfig['masterSecret'];
        $base64 = base64_encode($basevar); 
        
        $header = array("Authorization:Basic $base64","Content-Type:application/json","Connection: Keep-Alive");
        $postUrl = $url;
        $curlPost = $param;
        
//         Zeed_Benchmark::print_r($curlPost);exit;
        $method = $method ? $method : "POST";
        $ch = curl_init(); // 初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); // 抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, false); // 设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 要求结果为字符串且输出到屏幕上

        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true); // post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        } else if ($method == "GET") {
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // 增加 HTTP Header（头）里的字段
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        // 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $data = curl_exec($ch); // 运行curl
        curl_close($ch);
        return $data;
    }
    
    /**
     * 发送HTTP请求
     * @param $url string 请求的URL
     * @param $method int 请求的方法
     * @param null $body String POST请求的Body
     * @param int $times 当前重试的册数
     * @return array
     * @throws APIConnectionException
     */
    public function _request($url, $method, $body=null, $times=1) {
        $this->log("Send " . $method . " " . $url . ", body:" . $body . ", times:" . $times);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        // 设置User-Agent
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        // 连接建立最长耗时
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CONNECT_TIMEOUT);
        // 请求最长耗时
        curl_setopt($ch, CURLOPT_TIMEOUT, self::READ_TIMEOUT);
        // 设置SSL版本 1=CURL_SSLVERSION_TLSv1, 不指定使用默认值,curl会自动获取需要使用的CURL版本
        // curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 如果报证书相关失败,可以考虑取消注释掉该行,强制指定证书版本
        //curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
        // 设置Basic认证
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->appKey . ":" . $this->masterSecret);
        // 设置Post参数
        if ($method === self::HTTP_POST) {
            curl_setopt($ch, CURLOPT_POST, true);
        } else if ($method === self::HTTP_DELETE || $method === self::HTTP_PUT) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        if (!is_null($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
    
        // 设置headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ));
    
        // 执行请求
        $output = curl_exec($ch);
        // 解析Response
        $response = array();
        $errorCode = curl_errno($ch);
        if ($errorCode) {
            if ($errorCode === 28) {
                throw new APIConnectionException("Response timeout. Your request has probably be received by JPush Server,please check that whether need to be pushed again.", true);
            } else if ($errorCode === 56) {
                // resolve error[56 Problem (2) in the Chunked-Encoded data]
                throw new APIConnectionException("Response timeout, maybe cause by old CURL version. Your request has probably be received by JPush Server, please check that whether need to be pushed again.", true);
            } else if ($times >= $this->retryTimes) {
                throw new APIConnectionException("Connect timeout. Please retry later. Error:" . $errorCode . " " . curl_error($ch));
            } else {
                $this->log("Send " . $method . " " . $url . " fail, curl_code:" . $errorCode . ", body:" . $body . ", times:" . $times);
                $this->_request($url, $method, $body, ++$times);
            }
        } else {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header_text = substr($output, 0, $header_size);
            $body = substr($output, $header_size);
            $headers = array();
            foreach (explode("\r\n", $header_text) as $i => $line) {
                if (!empty($line)) {
                    if ($i === 0) {
                        $headers['http_code'] = $line;
                    } else if (strpos($line, ": ")) {
                        list ($key, $value) = explode(': ', $line);
                        $headers[$key] = $value;
                    }
                }
            }
            $response['headers'] = $headers;
            $response['body'] = $body;
            $response['http_code'] = $httpCode;
        }
        curl_close($ch);
        return $response;
    }
    

    private static function _getPushConfig()
    {
        $pushConfig = Zeed_Config::loadGroup('push');
        $pushType = $pushConfig['push_type'];

        $pushConfig = array(
            "appKey" => $pushConfig[$pushType]['appKeys'],
            "push_url" => $pushConfig[$pushType]['push_url'],
            "device_url" => $pushConfig[$pushType]['device_url'],
            "tags_url" => $pushConfig[$pushType]['tags_url'],
            "alias_url" => $pushConfig[$pushType]['alias_url'],
            "masterSecret" => $pushConfig[$pushType]['masterSecret'],
            "pushType" => $pushConfig[push_type]
        );
        return $pushConfig;
    }
}

// End ^ Native EOL ^ UTF-8