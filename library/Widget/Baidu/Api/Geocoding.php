<?php
/**
 * Baidu API
 * 
 * Geocoding API包括：地址解析和逆地址解析功能。
 * 
 * 地址解析是指，由详细到街道的结构化地址得到百度经纬度信息，且支持名胜古迹、标志性建筑名称直接解析返回百度经纬度。
 * 例如：“北京市海淀区中关村南大街27号”地址解析的结果是“lng:116.31985,lat:39.959836”，
 * “百度大厦”地址解析的结果是“lng:116.30815,lat:40.056885”。
 * 用法举例：http://api.map.baidu.com/geocoder?address=地址&output=输出格式类型&key=用户密钥&city=城市名
 * 
 * 逆地址解析是指，由百度经纬度信息得到结构化地址信息。
 * 例如：“lat:31.325152,lng:120.558957”逆地址解析的结果是“江苏省苏州市虎丘区塔园路318号”。
 * 用法举例：http://api.map.baidu.com/geocoder?location=纬度,经度&output=输出格式类型&key=用户密钥
 * 
 * 官网地址：http://developer.baidu.com/map/geocoding-api.htm
 */

class Widget_Baidu_Api_Geocoding extends Widget_Baidu_Abstract
{
    /**
     * Request Url
     *
     * @var string
     */
    private static $_request_url = "http://api.map.baidu.com/geocoder";
    
    /**
     * params
     */
    private static $_res = array('status' => 1, 'error' => null, 'data' => null);

    /**
     * 根据结构化地址信息获取经纬度信息
     * 
     * @param string $address 地址
     * @param string $key 用户在百度申请注册的key
     * @param string $city 地址所在的城市名
     * @param string $output 结果输出格式，json或xml，默认为xml
     * @return array
     */
    public static function getGpsByAddress($address, $key, $city = null, $output = null)
    {
        $output = $output ? $output : 'json';
        $request = 'address=' . $address . '&key=' . $key . '&output=' . $output . '&city=' . $city;
        $result = parent::curl(self::$_request_url, 'GET', $request);
        
        self::resolutionForGps($result);
        return self::$_res;
    }
    
    /**
     * 根据经纬度信息获取结构化地址信息
     * 
     * @param string $location 纬度经度信息，格式：纬度,经度。比如：31.307238,121.520633
     * @param string $key 用户在百度申请注册的key
     * @param string $output 结果输出格式，json或xml，默认为xml
     * @return array
     */
    public static function getAddressByGps($location, $key, $output = null)
    {
        $output = $output ? $output : 'json';
        $request = 'location=' . $location . '&key=' . $key . '&output=' . $output;
        $result = parent::curl(self::$_request_url, 'GET', $request);
        
        self::resolutionForAddress($result);
        return self::$_res;
    }
    
    /**
     * 根据结构化地址信息获取经纬度信息 - 对返回结果进行解析
     */
    protected static function resolutionForGps($result)
    {
        $status_mapping = array('OK' => '查询成功', 'INVILID_KEY' => '非法密钥', 'INVALID_PARAMETERS' => '非法参数');
        
        if ($result !== false) {
            $result = json_decode($result, true);
            self::$_res['error'] = $status_mapping[$result['status']];
            if ($result['status'] == 'OK') {
                self::$_res['status'] = 0;
                self::$_res['data'] = array(
                        'lng' => $result['result']['location']['lng'], // 经度，longitude
                        'lat' => $result['result']['location']['lat'], // 纬度，latitude
                        'precise' => $result['result']['precise'], // 是否精确查找（1为精确查找，0为不精确查找）
                        'confidence' => $result['result']['confidence'], // 可信度
                        'level' => $result['result']['level'] // 级别
                );
            }
        } else {
            self::$_res['error'] = '查询接口失败，请稍后重试';
        }
    }
    
    /**
     * 根据经纬度信息获取结构化地址信息 - 对返回结果进行解析
     */
    protected static function resolutionForAddress($result)
    {
        $status_mapping = array('OK' => '查询成功', 'INVILID_KEY' => '非法密钥', 'INVALID_PARAMETERS' => '非法参数');
    
        if ($result !== false) {
            $result = json_decode($result, true);
            self::$_res['error'] = $status_mapping[$result['status']];
            if ($result['status'] == 'OK') {
                self::$_res['status'] = 0;
                self::$_res['data'] = array(
                        'formatted_address' => $result['result']['formatted_address'], // 详细地址
                        'business' => $result['result']['business'], // 周围商圈
                        'city' => $result['result']['addressComponent']['city'], // 城市名称
                        'district' => $result['result']['addressComponent']['district'], // 区县名称
                        'province' => $result['result']['addressComponent']['province'], // 省份名称
                        'street' => $result['result']['addressComponent']['street'], // 街道名称
                        'street_number' => $result['result']['addressComponent']['street_number'], // 门牌号码
                        'cityCode' => $result['result']['cityCode'], // 城市代码
                );
            }
        } else {
            self::$_res['error'] = '查询接口失败，请稍后重试';
        }
    }
}
