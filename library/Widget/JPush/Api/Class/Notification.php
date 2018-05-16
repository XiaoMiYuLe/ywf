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
class Widget_JPush_Api_Class_Notification {
    
    public $alert;
    public $title;
    public $extras;
    public $status;
    // adnroid
    /** android
     * 关键字	 	 	含义	说明
     alert	string	必须	通知内容	这里指定了，则会覆盖上级统一指定的 alert 信息；内容可以为空字符串，则表示不展示到通知栏。这里不指定则上级 notification 必须指定。
     title	string	可选	通知标题	如果指定了，则通知里原来展示 App名称的地方，将展示成这个字段。
     builder_id	int	可选	通知栏样式ID	Android SDK 可设置通知栏样式，这里根据样式 ID 来指定该使用哪套样式。
     extras	JSON Object	可选	扩展字段。	这里自定义 JSON 格式的 Key/Value 信息，以供业务使用。
     */
    public $builder_id;
    
    // ios
    /**
     * 关键字	 	 	含义	说明
        alert	string	必须	通知内容	这里指定了，将会覆盖上级统一指定的 alert 信息；内容为空则不展示到通知栏。支持 emoji 表情。这里不指定则上级 notification 必须指定。
        sound	string	可选	通知提示声音	
        
        如果无此字段，则此消息无声音提示；有此字段，如果找到了指定的声音就播放该声音，否则播放默认声音,如果此字段为空字符串，iOS 7 为默认声音，iOS 8 为无声音。
        
        (消息) 说明：JPush 官方 API Library (SDK) 会默认填充声音字段。提供另外的方法关闭声音。
        badge	string	可选	应用角标	
        
        如果不填，表示不改变角标数字；否则把角标数字改为指定的数字；为 0 表示清除。
        新增支持 "+1" 功能，详情参考：http://blog.jpush.cn/ios_apns_badge_plus/
        
        (消息) 说明：JPush 官方 API Library (SDK) 会默认填充 badge 值为 "+1"。提供另外的方法不变更 badge 值。
        content-available	boolean	可选	推送唤醒	推送的时候携带"content-availiable":true 说明是 Background Remote Notification，如果不携带此字段则是普通的Remote Notification。详情参考：Background Remote Notification
        category	string	可选	 	
        
        设置APNs payload中的"category"字段值。
        
        (消息) 说明：ios8才支持该字段。
        extras	JSON Object	可选	扩展字段	这里自定义 Key/value 信息，以供业务使用。
     */
    public $sound = 'default';
    public $badge = 1;
    public $content_available;
    public $category;
    
    private $message = array();
    private $ios = array();
    private $android = array();
    
    /**
     * @return the $status
     */
    public function getStatus()
    {
    	return $this->status;
    }
 /**
     * @return the $alert
     */
    public function getAlert()
    {
        return $this->alert;
    }

 /**
     * @return the $title
     */
    public function getTitle()
    {
        return $this->title;
    }

 /**
     * @return the $extras
     */
    public function getExtras()
    {
        return $this->extras;
    }

 /**
     * @return the $builder_id
     */
    public function getBuilder_id()
    {
        return $this->builder_id;
    }

 /**
     * @return the $sound
     */
    public function getSound()
    {
        return $this->sound;
    }

 /**
     * @return the $badge
     */
    public function getBadge()
    {
        return $this->badge;
    }

 /**
     * @return the $content_available
     */
    public function getContent_available()
    {
        return $this->content_available;
    }

 /**
     * @return the $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param field_type $alert
     */
    public function setStatus($status)
    {
    	$this->status = $status;
    	$this->message["status"] = $status;
    }
    
 /**
     * @param field_type $alert
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;
        $this->message["alert"] = $alert;
    }

 /**
     * @param field_type $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->android["title"] = $title;
    }

 /**
     * @param field_type $extras
     * array("key1":"value1", "key2":"value2"....)
     */
    public function setExtras($extras)
    {
        $this->extras = $extras;
        $this->android["extras"] = $extras;
        $this->ios['extras'] = $extras;
    }

 /**
     * @param field_type $builder_id
     */
    public function setBuilder_id($builder_id)
    {
        $this->builder_id = $builder_id;
        $this->android["builder_id"] = $builder_id;
    }

 /**
     * @param field_type $sound
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
        $this->ios['sound'] = $sound;
    }

 /**
     * @param field_type $badge
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
        $this->ios['badge'] = $badge;
    }

 /**
     * @param boolean $content_available
     */
    public function setContent_available($content_available)
    {
        $this->content_available = $content_available;
        $this->ios['content_available'] = $content_available;
    }

 /**
     * @param field_type $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
        $this->ios['category'] = $category;
    }

    public function toArray() {
        $ret_array = array();
        // 判断语句, 判断变量
        
        $this->ios['sound'] = $this->sound;
        $this->ios['badge'] = $this->badge;
        
        $ret_array["ios"] = array_merge($this->ios, $this->message);
        
        // 判断语句, 判断变量
        if (count($this->android) > 0) {
            $ret_array["android"] = array_merge($this->android, $this->message);
        }

        // 判断语句, 判断变量
        if (count($this->ios) == 0 && count($this->android) == 0) {
            $ret_array = $this->message;
        }
        
        // 判断语句, 判断变量
        if (count($ret_array) == 0) {
           $ret_array = $this->message;
        }
        
        return $ret_array;
    }
    
}