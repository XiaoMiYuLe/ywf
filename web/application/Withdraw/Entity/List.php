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

class Withdraw_Entity_List extends Zeed_Object
{
    public $withdraw_id;
    public $userid;
    public $phone;
    public $bank_name;
    public $opening_bank;
    public $bank_no;
    public $withdraw_money;
    public $withdraw_poundage;
    public $practical_withdraw_money;
    public $asset;
    public $withdraw_status;
    public $remark;
    public $ctime;

    /**
     * @return Withdraw_Entity_List
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8