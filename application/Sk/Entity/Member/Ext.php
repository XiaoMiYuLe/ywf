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
 * @since      2016-04-01
 * @version    SVN: $
 */

class Sk_Entity_Member_Ext extends Zeed_Object
{
    public $id;
    public $uid;
    public $level_id;
    public $group_id;
    public $parent_id;
    public $province_id;
    public $city_id;
    public $county_id;
    public $paypassword;
    public $firstname;
    public $lastname;
    public $gender;
    public $age;
    public $fullname;
    public $realname;
    public $nickname;
    public $idcard;
    public $idcard_icon;
    public $bank_mobile;
    public $bank_name;
    public $company_name;
    public $company_icon;
    public $icon;
    public $email;
    public $phone;
    public $mobile;
    public $qq;
    public $reg_ip;
    public $reg_time;
    public $login_time;
    public $login_times;
    public $last_logintime;
    public $modify_time;
    public $birthday;
    public $status;
    public $points;
    public $content;
    public $number;
    public $is_v_email;
    public $is_v_mobile;
    public $is_v_realname;
    public $is_v_planners;
    public $createtime;

    /**
     * @return Sk_Entity_Member_Ext
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8