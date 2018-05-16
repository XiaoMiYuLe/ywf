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

class Goods_Entity_Brand extends Zeed_Object
{
    public $brand_id;
    public $brand_name;
    public $brand_name_en;
	public $brand_url;
	public $brand_desc;
	public $brand_logo;
	public $brand_keywords;
	public $status;
	public $sort_order;
	public $ctime;
	public $mtime;
    
    /**
     * @return Goods_Entity_Brand
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8