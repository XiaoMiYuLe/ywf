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
 * @since      2010-12-29
 * @version    SVN: $Id$
 */
class Bts_Entity_Order_Refund extends Zeed_Object {

    public $refund_id;
    public $order_id;
    public $refund_sn;
    public $item_id;
    public $order_number;
    public $price;
    public $reason;
    public $operator_type;
    public $operator_userid;
    public $ip;
    public $ctime;
    public $status;
    public $is_del;

    /**
     * @return Bts_Entity_Order_Items
     */
    public final static function newInstance() {
        return new self();
    }

}

// End ^ Native EOL ^ UTF-8