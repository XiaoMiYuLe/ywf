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

class Cas_Entity_User_Voucher extends Zeed_Object
{
    public $id;
    public $userid;
    public $voucher_id;
    public $order_id;
    public $voucher_status;
    public $valid_data;
    public $voucher_money;
    public $use_money;
    public $use_time;
    public $creat_time;
    public $start_data;
    public $type;
    public $order_money;
    public $increase_interest;
    public $is_manager;
    public $money_remarks;

    /**
     * @return Cas_Entity_User_Voucher
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8