<?php
/**
 * Creat by xiaopg 2015-05-22
 */
////////////////////////////////////////////////////////////////////
//                            _ooOoo_                             //
//                           o8888888o                            //
//                           88" . "88                            //
//                           (| ^_^ |)                            //
//                           O\  =  /O                            //
//                        ____/`---'\____                         //
//                      .'  \\|     |//  `.                       //
//                     /  \\|||  :  |||//  \                      //
//                    /  _||||| -:- |||||-  \                     //
//                    |   | \\\  -  /// |   |                     //
//                    | \_|  ''\---/''  |   |                     //
//                    \  .-\__  `-`  ___/-. /                     //
//                  ___`. .'  /--.--\  `. . ___                   //
//                ."" '<  `.___\_<|>_/___.'  >'"".                //
//              | | :  `- \`.;`\ _ /`;.`/ - ` : | |               //
//              \  \ `-.   \_ __\ /__ _/   .-` /  /               //
//        ========`-.____`-.___\_____/___.-`____.-'========       //
//                             `=---='                            //
//        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^      //
//            佛祖保佑       永无BUG        永不修改              //
//            本代码已经经过开光处理，绝无可能再产生BUG           //
////////////////////////////////////////////////////////////////////
class Widget_JPush_Api_Class_Options {
    
    /**
     * 关键字	 	 	含义	说明
        sendno	int	可选	推送序号	纯粹用来作为 API 调用标识，API 返回时被原样返回，以方便 API 调用方匹配请求与返回。
        time_to_live	int	可选	离线消息保留时长	推送当前用户不在线时，为该用户保留多长时间的离线消息，以便其上线时再次推送。默认 86400 （1 天），最长 10 天。设置为 0 表示不保留离线消息，只有推送当前在线的用户可以收到。
        override_msg_id	long	可选	要覆盖的消息ID	如果当前的推送要覆盖之前的一条推送，这里填写前一条推送的 msg_id 就会产生覆盖效果，即：1）该 msg_id 离线收到的消息是覆盖后的内容；2）即使该 msg_id Android 端用户已经收到，如果通知栏还未清除，则新的消息内容会覆盖之前这条通知；覆盖功能起作用的时限是：1 天。
        如果在覆盖指定时限内该 msg_id 不存在，则返回 1003 错误，提示不是一次有效的消息覆盖操作，当前的消息不会被推送。
        apns_production	boolean	可选	APNs是否生产环境	
        
        True 表示推送生产环境，False 表示要推送开发环境； 如果不指定则为推送生产环境。
        
        (消息) JPush 官方 API LIbrary (SDK) 默认设置为推送 “开发环境”。
        big_push_duration	int	可选	定速推送时长（分钟）	又名缓慢推送，把原本尽可能快的推送速度，降低下来，在给定的 n 分钟内，均匀地向这次推送的目标用户推送。最大值为 1440。未设置则不是定速推送。
     */
    
    public $sendno;
    public $time_to_live = 86400;
    public $override_msg_id;
    public $apns_production = "True";
    public $big_push_duration;
    
    private $options;
 /**
     * @return the $sendno
     */
    public function getSendno()
    {
        return $this->sendno;
    }

 /**
     * @return the $time_to_live
     */
    public function getTime_to_live()
    {
        return $this->time_to_live;
    }

 /**
     * @return the $override_msg_id
     */
    public function getOverride_msg_id()
    {
        return $this->override_msg_id;
    }

 /**
     * @return the $apns_production
     */
    public function getApns_production()
    {
        return $this->apns_production;
    }

 /**
     * @return the $big_push_duration
     */
    public function getBig_push_duration()
    {
        return $this->big_push_duration;
    }

 /**
     * @param field_type $sendno
     */
    public function setSendno($sendno)
    {
        $this->sendno = $sendno;
        $this->options["sendno"] = $sendno;
    }

 /**
     * @param number $time_to_live
     */
    public function setTime_to_live($time_to_live)
    {
        $this->time_to_live = $time_to_live;
        $this->options["time_to_live"] = $time_to_live;
    }

 /**
     * @param field_type $override_msg_id
     */
    public function setOverride_msg_id($override_msg_id)
    {
        $this->override_msg_id = $override_msg_id;
        $this->options["override_msg_id"] = $override_msg_id;
    }

 /**
     * @param boolean $apns_production
     */
    public function setApns_production($apns_production)
    {
        $this->apns_production = $apns_production;
        $this->options["apns_production"] = $apns_production ? "True":"False";
    }

 /**
     * @param field_type $big_push_duration
     */
    public function setBig_push_duration($big_push_duration)
    {
        $this->big_push_duration = $big_push_duration;
        $this->options["big_push_duration"] = $big_push_duration;
    }

    public function toArray() {
        $ret_array = array();
        // 判断语句, 判断变量
        if (count($this->options) > 0) {
            $ret_array = $this->options;
        }
    
        return $ret_array;
    }
    
}