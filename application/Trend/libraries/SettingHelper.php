<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2015-05-05
 * @version    SVN: $Id$
 */

class SettingHelper
{
    /**
     * 配置项--有效期
     * @param number $type
     * @param string $key
     */
    public static function getValidityList($type = 1,$key = '')
    {
        $array = array();
        $array[1] = array('id'=>1,'name'=>'1个月','month'=>1);
        $array[2] = array('id'=>2,'name'=>'3个月','month'=>3);
        $array[3] = array('id'=>3,'name'=>'6个月','month'=>6);
        $array[4] = array('id'=>4,'name'=>'9个月','month'=>9);
        $array[5] = array('id'=>5,'name'=>'1年','month'=>12);
        $array[6] = array('id'=>6,'name'=>'2年','month'=>24);
        $array[7] = array('id'=>7,'name'=>'3年','month'=>36);
        $array[0] = array('id'=>0,'name'=>'永久有效','month'=>0);
        if ($type == 1){
            return $array;
        }else{
            if(array_key_exists($key,$array)){
                return $array[$key];
            }else{
                return '--';
            }
        }
    }
    /**
     * 配置项--评价奖励
     * @param number $type
     * @param string $key
     */
    public static function getAwardCommentList($type = 1,$key = '')
    {
        $array = array();
        $array[0] = array('val'=>0,'name'=>'0');
        $array[10] = array('val'=>10,'name'=>'10');
        $array[20] = array('val'=>20,'name'=>'20');
        $array[30] = array('val'=>30,'name'=>'30');
        $array[40] = array('val'=>40,'name'=>'40');
        $array[50] = array('val'=>50,'name'=>'50');
        $array[100] = array('val'=>100,'name'=>'100');
        if ($type == 1) {
            return $array;
        } else {
            if(array_key_exists($key,$array)) {
                return $array[$key];
            } else {
                return '--';
            }
        }
    }
}