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

class Interest_Entity_Settlement extends Zeed_Object
{
    public $settlement_id;
    public $userid;
    public $order_no;
    public $settlement_money;
    public $stime;
    public $ctime;
    public $settlement_status;

    /**
     * @return Interest_Entity_Settlement
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8