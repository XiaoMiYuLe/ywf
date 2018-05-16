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

class Sk_Entity_Member extends Zeed_Object
{
    public $id;
    public $username;
    public $password;
    public $code;
    public $createtime;

    /**
     * @return Sk_Entity_Member
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8