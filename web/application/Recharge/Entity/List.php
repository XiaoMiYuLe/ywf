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

class Recharge_Entity_List extends Zeed_Object
{
    public $recharge_id;
    public $userid;
    public $phone;
    public $bank_name;
    public $bank_no;
    public $opening_bank;
    public $recharge_money;
    public $asset;
    public $platform_serial_number;
    public $recharge_status;
    public $ctime;

    /**
     * @return Recharge_Entity_List
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8