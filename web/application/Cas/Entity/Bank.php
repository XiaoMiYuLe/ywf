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
 * @since      2016-03-30
 * @version    SVN: $
 */

class Cas_Entity_Bank extends Zeed_Object
{
    public $bank_id;
    public $bind_id;
    public $bank_code;
    public $bank_no;
    public $order_no;
    public $userid;
    public $bank_name;
    public $subbank_name;
    public $cardholder;
    public $phonebankcard;
    public $cert_no;
    public $is_use;
    public $ctime;
    public $mtime;
    public $is_del;
    public $type;

    /**
     * @return Cas_Entity_Bank
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8