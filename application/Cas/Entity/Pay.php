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

class Cas_Entity_Pay extends Zeed_Object
{
    public $id;
    public $userid;
    public $order_no;
    public $type;
    public $ctime;
    public $code;
    public $msg;
    public $numone;
    public $numtwo;
    public $numthree;
    public $pay_type;

    /**
     * @return Cas_Entity_Pay
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8