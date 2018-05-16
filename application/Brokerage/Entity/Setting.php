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
 * @since      2016-03-08
 * @version    SVN: $
 */

class Brokerage_Entity_Setting extends Zeed_Object
{
    public $first_brokerage;
    public $second_brokerage;
    public $third_brokerage;

    /**
     * @return Brokerage_Entity_Setting
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8