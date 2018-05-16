<?php
/**
 * 生成二维码
 */

class Widget_JPush_Api_Push extends Widget_JPush_Abstract 
{
    // 配置参数
    private $_appkeys;
    private $_masterSecret;
    
    public $audience;
    public $notification;
    public $option;
    public $platform;
    
    
    /**
     * @return the $audience
     */
    public function __construct($appkeys, $masterSecret)
    {
        $this->_appkeys = $appkeys;
        $this->_masterSecret = $masterSecret;
    }
    
    /**
     * @return the $audience
     */
    public function getAudience()
    {
        return $this->audience;
    }

     /**
     * @return the $notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

 /**
     * @return the $option
     */
    public function getOption()
    {
        return $this->option;
    }

 /**
     * @return the $platform
     */
    public function getPlatform()
    {
        return $this->platform;
    }

 /**
     * @param field_type $audience
     */
    public function setAudience($audience)
    {
        $this->audience = $audience;
    }

 /**
     * @param field_type $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

 /**
     * @param field_type $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }

 /**
     * @param field_type $platform
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }

 /**
     * 发送 极光推送 请求
     * @param unknown_type $url 推送的请求url
     * @param unknown_type $param 推送的参数
     * @param unknown_type $header 推送的header信息
     * @return boolean|mixed
     */
    private function request_post($url="",$param="",$header="") {
        if (empty($url) || empty($param)) {
        return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        // 增加 HTTP Header（头）里的字段
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);//运行curl
     
        curl_close($ch);
        return $data;
    }
 
    /**
     * 推送信息的最后一步, 发送http请求
     */
    public function run()
    {
        // 极光推送的url
        $url = 'https://api.jpush.cn/v3/push';
        
        // 根据key 与 secret 获取适当的header
        $base64=base64_encode("$this->_appkeys:$this->_masterSecret");
        $header=array("Authorization: Basic {$base64}","Content-Type:application/json");
        
        $message_array = array();
        // 判断语句, 判断变量
        if (!($this->audience instanceof Widget_JPush_Api_Class_Audience)) {
            $this->audience = new Widget_JPush_Api_Class_Audience();
        } 
        $message_array["audience"] = $this->audience->toArray();
        
        // 判断语句, 判断变量
        if (!($this->notification instanceof Widget_JPush_Api_Class_Notification)) {
            $this->notification = new Widget_JPush_Api_Class_Notification();
        }
        $message_array["notification"]= $this->notification->toArray();
        
        // 判断语句, 判断变量
        if (!($this->platform instanceof Widget_JPush_Api_Class_Platform)) {
            $this->platform = new Widget_JPush_Api_Class_Platform();
        }
        $message_array["platform"] = $this->platform->toArray();
        
        // 判断语句, 判断变量
        if (!($this->option instanceof Widget_JPush_Api_Class_Options)) {
            $this->option = new Widget_JPush_Api_Class_Options();
        }
        $message_array["options"] = $this->option->toArray();
        
//         print_r(json_encode($message_array));die;
        $res = $this->request_post($url,json_encode($message_array),$header);
        $res_arr = json_decode($res, true);
//         print_r($res_arr);
        return $res_arr;
    }
    
    
    private static function example() {
        $push_obj = new Widget_JPush_Api_Push();
        $push_obj->setTitle("积分获取");
        $push_obj->setMessage("获取ceshi积分");
//         $push_obj->setTags(array("898", "898-2"));
        $push_obj->setAlias(array("393"));
//         $push_obj->setRegistrationid(array("258", "258-2"));
        $push_obj->run();die;
    }
 
}