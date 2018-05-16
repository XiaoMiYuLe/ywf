<?php
/**
 * Baidu API
 * 
 * Place API包括：矩形区域关键字检索、周边区域关键字检索和城市内关键字检索
 * 
 * 警告：每个key支持每天1000次的调用，超过限制不返回数据
 * 使用建议：若需要频繁使用该接口，可配置多个key，轮询调用
 * 
 * 官网地址：http://developer.baidu.com/map/place-api.htm
 */

class Widget_Baidu_Api_Place extends Widget_Baidu_Abstract
{
    /**
     * Request Url
     *
     * @var string
     */
    private static $_request_url = "http://api.map.baidu.com/place/search";
    
    /**
     * params
     */
    private static $_res = array('status' => 1, 'error' => null, 'data' => null);

    /**
     * 矩形区域检索
     * 
     * @param string $query 检索关键字，不支持多个关键字
     * @param string $bounds 检索矩形区域，格式：lat,lng(左下角坐标),lat,lng(右上角坐标)
     * @param string $key 用户在百度申请注册的key
     * @param string $output 结果输出格式，json或xml，默认为xml
     * @return array
     */
    public static function searchForBound($query, $bounds, $key, $output = null)
    {
        $output = $output ? $output : 'json';
        $request = 'query=' . $query . '&bounds=' . $bounds . '&key=' . $key . '&output=' . $output;
        $result = parent::curl(self::$_request_url, 'GET', $request);
        
        self::resolution($result);
        return self::$_res;
    }
    
    /**
     * 周边区域检索
     * 
     * @param string $query 检索关键字，不支持多个关键字
     * @param string $location 周边检索中心点，不支持多个点。格式：纬度,经度。比如：31.307238,121.520633
     * @param string $radius 周边检索半径，单位米
     * @param string $key 用户在百度申请注册的key
     * @param string $output 结果输出格式，json或xml，默认为xml
     * @return array
     */
    public static function searchForRadius($query, $location, $radius, $key, $output = null)
    {
        $output = $output ? $output : 'json';
        $request = 'query=' . $query. '&location=' . $location . '&radius=' . $radius . '&key=' . $key . '&output=' . $output;
        $result = parent::curl(self::$_request_url, 'GET', $request);
        
        self::resolution($result);
        return self::$_res;
    }
    
    /**
     * 指定城市内检索
     * 
     * @param string $query 检索关键字，不支持多个关键字
     * @param string $region 检索城市名称，可以是xxx市或xxx县
     * @param string $key 用户在百度申请注册的key
     * @param string $output 结果输出格式，json或xml，默认为xml
     * @return array
     */
    public static function searchForRegion($query, $region, $key, $output = null)
    {
        $output = $output ? $output : 'json';
        $request = 'query=' . $query . '&region=' . $region . '&key=' . $key . '&output=' . $output;
        $result = parent::curl(self::$_request_url, 'GET', $request);
        
        self::resolution($result);
        return self::$_res;
    }
    
    /**
     * 对返回结果进行解析
     */
    protected static function resolution($result)
    {
        $status_mapping = array('OK' => '查询成功', 'INVILID_KEY' => '非法密钥', 'INVALID_PARAMETERS' => '非法参数');
        
        if ($result !== false) {
            $result = json_decode($result, true);
            self::$_res['error'] = $status_mapping[$result['status']];
            if ($result['status'] == 'OK') {
                self::$_res['status'] = 0;
                self::$_res['data'] = $result['results'];
            }
        } else {
            self::$_res['error'] = '查询接口失败，请稍后重试';
        }
    }
}
