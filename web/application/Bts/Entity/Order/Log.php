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
class Bts_Entity_Order_Log extends Zeed_Object {

    public $log_id;
    public $admin_userid;
    public $type;
    public $order_id;
    public $order_number;
    public $content;
    public $remark;
    public $ip;
    public $ctime;

    /**
     * @return Bts_Entity_Order_Items
     */
    public final static function newInstance() {
        return new self();
    }

}

// End ^ Native EOL ^ UTF-8