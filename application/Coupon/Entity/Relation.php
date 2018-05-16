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
class Coupon_Entity_Relation extends Zeed_Object
{

    public $coupon_id;
    public $basic_price;
    public $relation_type;
    public $relation_content;

    /**
     * @return Coupon_Entity_Relation
     */
    public final static function newInstance()
    {
        return new self();
    }

}

// End ^ Native EOL ^ UTF-8