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

class Goods_Entity_Content extends Zeed_Object
{
    public $content_id;
    public $parent_id;
	public $category;
	public $brand_id;
	public $userid;
	public $name;
	public $sku;
	public $image_default;
	public $property_related;
	public $stock;
	public $weight;
	public $length;
	public $wide;
	public $height;
	public $price;
	public $price_market;
	public $price_cost;
	public $purchase_max;
	public $sales_volume;
	public $sales_amount;
	public $rank_average;
	public $serialize_specs;
	public $viewed;
	public $sort_order;
	public $pinned;
	public $promotion_type;
	public $is_shelf;
	public $is_spec;
	public $is_del;
	public $ctime;
	public $mtime;
    
    /**
     * @return Goods_Entity_Content
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8