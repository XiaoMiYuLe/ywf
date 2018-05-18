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
 * @since      2016-03-08
 * @version    SVN: $
 */

class Cas_Entity_User_Brokerage extends Zeed_Object
{
    public $brokerage_id;
    public $userid;
    public $order_id;
    public $username;
    public $user_grade;
    public $investment_amount;
    public $order_time;
    public $brokerage_ratio;
    public $expected_money;
    public $mtime;
    public $comment;
    public $brokerage_status;

    /**
     * @return Cas_Entity_User_Brokerage
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8