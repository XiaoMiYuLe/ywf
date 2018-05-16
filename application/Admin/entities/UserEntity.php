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
 * @since      2015-07-20
 * @version    SVN: $
 */

class UserEntity extends Zeed_Object
{
    public $userid;
    public $username;
    public $password;
    public $salt;
    public $email;
    public $nickname;
    public $fullname;
    public $gender;
    public $avatar;
    public $idcard;
    public $domain;
    public $ctime;
    public $mtime;
    public $status;
    public $is_online;
}

// End ^ Native EOL ^ UTF-8