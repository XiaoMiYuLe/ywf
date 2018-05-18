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
 * @since      2016-03-23
 * @version    SVN: $
 */

class Goods_Entity_List extends Zeed_Object
{
    public $goods_id;
    public $goods_name;
    public $all_fee;
    public $spare_fee;
    public $start_time;
    public $is_now;
    public $is_new;
    public $end_time;
    public $deal_date;
    public $deal_way;
    public $yield;
    public $goods_pattern;
    public $goods_type;
    public $goods_status;
    public $financial_period;
    public $debtor_name;
    public $debtor_card;
    public $low_pay;
    public $high_pay;
    public $increasing_pay;
    public $goods_broratio;
    public $goods_detail;
    public $is_del;
    public $ctime;
    public $mtime;
    public $buy_num;
    public $principal_way;
    public $redeem_status;
    public $principal_status;
    public $deal_status;
    public $comment;
    public $safety;
    public $is_hot;
    public $is_interest;
    public $is_voucher;
    public $is_manager;
    public $sort;
    public $is_transfer;
    public $distance_order;
    public $distance_cash;
    public $rate_max;
    public $rate_min;
    public $counter_fee;

    /**
     * @return Goods_Entity_List
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8