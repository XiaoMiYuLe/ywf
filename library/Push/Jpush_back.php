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
    public static function sendRegistrationId($registration_id, $message, $title = null, $extras = null)
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
        $header = array("Authorization:Basic $base64", "Content-Type:application/json");
        $postUrl = $url;
        $curlPost = $param;
        $method = $method ? $method : "POST";
        $ch = curl_init(); // 初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); // 抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); // 设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上

        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1); // post提交方式
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