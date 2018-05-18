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

class Goods_Entity_Content_Trash extends Zeed_Object
{
    public $content_id;
	public $category_id;
	public $brand_id;
	public $userid;
	public $name;
	public $bn;
	public $image_default;
	public $stock;
	public $weight;
	public $price;
	public $price_market;
	public $price_cost;
	public $data;
	public $is_del;
	public $ctime;
    
    /**
     * @return Goods_Entity_Content_Trash
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8