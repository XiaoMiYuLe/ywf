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
 * @since      2016-03-20
 * @version    SVN: $
 */

class Cas_Entity_User extends Zeed_Object
{
    public $userid;
    public $parent_id;
    public $bank_id;
    public $username;
    public $phone;
    public $idcard;
    public $password;
    public $pay_pwd;
    public $salt;
    public $encrypt;
    public $asset;
    public $freeze_money;
    public $is_buy;
    public $ctime;
    public $mtime;
    public $user_code;
    public $is_ecoman;
    public $user_remark;
    public $contacts_person;
    public $contacts_phone;
    public $zip_code;
    public $address;
    public $status;
    public $read_time;
    public $earnings;
    public $is_invitaiton;
    public $remarks;
    public $audit_time;
    public $is_market;
    public $article_read_time;
    public $reg_type;

    /**
     * @return Cas_Entity_User
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8