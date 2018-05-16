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
 * @since      2016-03-25
 * @version    SVN: $
 */

class Cas_Entity_Record_Log extends Zeed_Object
{
    public $record_id;
    public $order_id;
    public $order_no;
    public $flow_asset;
    public $userid;
    public $money;
    public $status;
    public $ctime;
    public $interest_time;
    public $mtime;
    public $pay_type;
    public $type;

    /**
     * @return Cas_Entity_Record_Log
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8