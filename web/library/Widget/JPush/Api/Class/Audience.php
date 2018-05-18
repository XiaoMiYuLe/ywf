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
class Widget_JPush_Api_Class_Audience {
    
    private $tag = array();
    private $tag_and = array(); // 起交集, 即满足拥有了所有的tag, 才会收到消息
    
    private $alias = array();
    
    private $registration_id = array();
 /**
     * @return the $tag
     */
    public function getTag()
    {
        return $this->tag;
    }

 /**
     * @return the $tag_and
     */
    public function getTag_and()
    {
        return $this->tag_and;
    }

 /**
     * @return the $alias
     */
    public function getAlias()
    {
        return $this->alias;
    }

 /**
     * @return the $registration_id
     */
    public function getRegistration_id()
    {
        return $this->registration_id;
    }

 /**
     * @param field_type $tag
     */
    public function setTag($tag)
    {
        // 判断语句, 判断变量
        if (is_array($tag)) {
            // 循环方法, 获取单个array 变量
            foreach ($tag as $key => $value) {
                // 判断语句, 判断变量
                if (is_numeric($value)) {
                    array_push($this->tag, $value);
                } else if (is_string($value) && strlen($value) <= 40) {
                    array_push($this->tag, $value);
                }
            }
        } else {
            array_push($this->tag, $tag);
        }
    }

 /**
     * @param field_type $tag_and
     */
    public function setTag_and($tag_and)
    {
        // 判断语句, 判断变量
        if (is_array($tag_and)) {
            // 循环方法, 获取单个array 变量
            foreach ($tag_and as $key => $value) {
                // 判断语句, 判断变量
                if (is_numeric($value)) {
                    array_push($this->tag_and, $value);
                } else if (is_string($value) && strlen($value) <= 40) {
                    array_push($this->tag_and, $value);
                }
            }
        } else {
            array_push($this->tag_and, $tag_and);
        }
    }

 /**
     * @param field_type $alias
     */
    public function setAlias($alias)
    {
        // 判断语句, 判断变量
        if (is_array($alias)) {
            // 循环方法, 获取单个array 变量
            foreach ($alias as $key => $value) {
                // 判断语句, 判断变量
                if (is_numeric($value)) {
                    array_push($this->alias, $value);
                } else if (is_string($value) && strlen($value) <= 40) {
                    array_push($this->alias, $value);
                }
            }
        } else {
            array_push($this->alias, $alias);
        }
    }

 /**
     * @param field_type $registration_id
     */
    public function setRegistration_id($registration_id)
    {
        // 判断语句, 判断变量
        if (is_array($registration_id)) {
            // 循环方法, 获取单个array 变量
            foreach ($registration_id as $key => $value) {
                // 判断语句, 判断变量
                if (is_numeric($value)) {
                    array_push($this->registration_id, $value);
                } else if (is_string($value) && strlen($value) <= 40) {
                    array_push($this->registration_id, $value);
                }
            }
        } else {
            array_push($this->registration_id, $registration_id);
        }
    }
    
    public function toArray() {
        $ret_array = array();
        // 判断语句, 判断变量
        if (count($this->tag) > 0) {
            $ret_array["tag"] = $this->tag;
        }
        
        // 判断语句, 判断变量
        if (count($this->tag_and) > 0) {
            $ret_array["tag_and"] = $this->tag_and;
        }
        
        // 判断语句, 判断变量
        if (count($this->alias) > 0) {
            $ret_array["alias"] = $this->alias;
        }
        
        // 判断语句, 判断变量
        if (count($this->registration_id) > 0) {
            $ret_array["registration_id"] = $this->registration_id;
        }
        
    // 判断语句, 判断变量
        if (count($ret_array) == 0) {
            $ret_array =  "all";
        }
        
        return $ret_array;
    }
}