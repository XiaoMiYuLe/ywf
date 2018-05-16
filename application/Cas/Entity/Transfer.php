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
 * @since      2016-04-19
 * @version    SVN: $
 */

class Cas_Entity_Transfer extends Zeed_Object
{
    public $id;
    public $transfer_userid;
    public $buy_userid;
    public $transfer_order_no;
    public $buy_order_no;
    public $transfer_pay;
    public $ctime;
    public $pay_status;

    /**
     * @return Cas_Entity_Pay
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8