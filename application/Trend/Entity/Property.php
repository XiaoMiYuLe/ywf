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

class Trend_Entity_Property extends Zeed_Object
{
    public $property_id;
	public $name;
	public $val_inputtype;
	public $label_name;
	public $placeholder;
	public $note;
	public $sort_order;
	public $is_spec;
	public $status;
    
    /**
     * @return Trend_Entity_Property
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8