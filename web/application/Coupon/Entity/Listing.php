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
class Coupon_Entity_Listing extends Zeed_Object
{

    public $cpns_id;
    public $coupon_id;
    public $cpns_status;
    public $is_del;
    public $disabled;
    public $userid;
    public $ctime;
    public $mtime;

    /**
     * @return Coupon_Entity_Listing
     */
    public final static function newInstance()
    {
        return new self();
    }

}

// End ^ Native EOL ^ UTF-8