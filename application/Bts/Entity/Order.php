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
 * @since      2016-03-05
 * @version    SVN: $
 */

class Bts_Entity_Order extends Zeed_Object
{
    public $order_id;
    public $order_no;
    public $goods_id;
    public $bank_id;
    public $goods_name;
    public $pay_type;
    public $goods_type;
    public $goods_pattern;
    public $buy_money;
    public $real_money;
    public $brokerage;
    public $yield;
    public $userid;
    public $username;
    public $phone;
    public $bank_no;
    public $bank_name;
    public $ctime;
    public $mtime;
    public $is_del;
    public $is_pay;
    public $rborderid;
    public $bts_yield;
    public $is_voucher;
    public $voucher;
    public $start_time;
    public $end_time;
    public $order_status;
    public $cash_time;
    public $principal_status;
    public $deal_status;
    public $pay_time;
    public $transfer_price;
    public $transfer_status;
    public $transfer_mindate;
    public $transfer_maxdate;
    public $counter_money;
    public $is_transfer_order;

    /**
     * @return Bts_Entity_Order
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8