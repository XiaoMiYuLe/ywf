<?php
/**
 * Baidu API
 * 
 * Ip API包括：获取指定IP的位置信息和获取当前设备IP的地址信息
 * 
 * 警告：每个key每天支持100万次调用，超过限制不返回数据
 * 使用建议：IP定位的结果精度较差，主要应用获取省份或者城市的位置信息。移动平台的APP建议使用百度定位SDK
 * 
 * 官网地址：http://developer.baidu.com/map/ip-location-api.htm
 */

class Widget_Baidu_Api_Ip extends Widget_Baidu_Abstract
{
    /**
     * Request Url
     *
     * @var string
     */
    private static $_request_url = "http://api.map.baidu.com/location/ip";
    
    /**
     * params
     */
    private static $_res = array('status' => 1, 'error' => null, 'data' => null);

    /**
     * 根据IP获取位置信息
     * 
     * @param string $ak 用户密钥
     * @param string $ip ip不出现，或者出现且为空字符串的情况下，会使用当前访问者的IP地址作为定位参数
     * @param string $coor coor不出现时，默认为百度墨卡托坐标；coor=bd09ll时，返回为百度经纬度坐标
     * @return array
     */
    public static function getLocationByIp($ak, $ip = null, $coor = null)
    {
        $coor = $coor ? $coor : 'bd09ll';
        $request = 'ak=' . $ak . '&coor=' . $coor;
        if ($ip) {
            $request .= '&ip=' .$ip;
        }
        $result = parent::curl(self::$_request_url, 'GET', $request);
        
        self::resolution($result);
        return self::$_res;
    }
    
    /**
     * 对返回结果进行解析
     */
    protected static function resolution($result)
    {
        $status_mapping = array(
                0 => '查询成功',
                1 => '服务器内部错误',
                2 => '请求参数非法',
                3 => '权限校验失败',
                4 => '配额校验失败',
                5 => 'ak不存在或者非法',
                101 => '服务禁用',
                102 => '不通过白名单或者安全码不对',
                '2xx' => '无权限',
                '3xx' => '配额错误'
        );
        
        if ($result !== false) {
            $result = json_decode($result, true);
            self::$_res['status'] = $result['status'];
            self::$_res['error'] = $status_mapping[$result['status']];
            if ($result['status'] === 0) {
                self::$_res['status'] = 0;
                self::$_res['data'] = array(
                        'lng' => $result['content']['point']['x'], // 经度
                        'lat' => $result['content']['point']['y'], // 纬度
                        'adress' => $result['content']['address'], // 简要地址
                        'city' => $result['content']['address_detail']['city'], // 城市
                        'city_code' => $result['content']['address_detail']['city_code'], // 百度城市代码
                        'district' => $result['content']['address_detail']['district'], // 区县
                        'province' => $result['content']['address_detail']['province'], // 省份
                        'street' => $result['content']['address_detail']['street'], // 街道
                        'street_number' => $result['content']['address_detail']['street_number'] // 门址
                );
            }
        } else {
            self::$_res['error'] = '查询接口失败，请稍后重试';
        }
    }
}
