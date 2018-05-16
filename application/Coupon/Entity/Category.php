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
class Coupon_Entity_Category extends Zeed_Object
{

    public $coupon_id;
    public $coupon_name;
    public $total;
    public $face_value;
    public $status;
    public $coupon_type;
    public $coupon_point;
    public $disabled;
    public $is_exchange;
    public $rule;
    public $body;
    public $valid_stime;
    public $valid_etime;
    public $grant_stime;
    public $grant_etime;
    public $is_del;
    public $ctime;
    public $mtime;

    /**
     * @return Coupon_Entity_Category
     */
    public final static function newInstance()
    {
        return new self();
    }

}

// End ^ Native EOL ^ UTF-8