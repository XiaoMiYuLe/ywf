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
 * @since      2016-03-10
 * @version    SVN: $
 */

class Voucher_Entity_Content extends Zeed_Object
{
    public $voucher_id;
    public $voucher_money;
    public $use_money;
    public $valid_data;
    public $disabled;
    public $ctime;
    public $mtime;
    public $voucher_type;
    public $to_recommender;
    public $type;
    public $increase_interest;
    public $order_money;

    /**
     * @return Voucher_Entity_Content
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8