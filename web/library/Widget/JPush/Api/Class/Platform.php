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
class Widget_JPush_Api_Class_Platform {
    
    public $is_android = false;
    public $is_ios = false;
    public $is_winphone = false;
    
    /**
     * @return the $is_android
     */
    public function getIs_android ()
    {
        return $this->is_android;
    }

	/**
     * @return the $is_ios
     */
    public function getIs_ios ()
    {
        return $this->is_ios;
    }

	/**
     * @return the $is_winphone
     */
    public function getIs_winphone ()
    {
        return $this->is_winphone;
    }

	/**
     * @param boolean $is_android
     */
    public function setIs_android ($is_android)
    {
        $this->is_android = $is_android;
    }

	/**
     * @param boolean $is_ios
     */
    public function setIs_ios ($is_ios)
    {
        $this->is_ios = $is_ios;
    }

	/**
     * @param boolean $is_winphone
     */
    public function setIs_winphone ($is_winphone)
    {
        $this->is_winphone = $is_winphone;
    }

	public function toArray() {
        $ret_array = array();
        
        // 判断语句, 判断变量
        if ($this->is_android) {
            array_push($ret_array, "android");
        }
        
        if ($this->is_ios) {
            array_push($ret_array, "ios");
        }
        
        if ($this->is_winphone) {
            array_push($ret_array, "winphone");
        }
        
        // 判断语句, 判断变量
        if (count($ret_array) == 0) {
            $ret_array = "all";
        } 
        
        return $ret_array;
    }
}