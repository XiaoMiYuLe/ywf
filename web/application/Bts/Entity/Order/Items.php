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
class Bts_Entity_Order_Items extends Zeed_Object {

    public $item_id;
	public $order_id;
	public $content_id;
    public $sku;
    public $goods_name;
    public $goods_image;
    public $goods_weight;
    public $goods_length;
    public $goods_wide;
	public $goods_height;
	public $description;
	public $buy_price;
	public $buy_num;
	public $is_comment;
	public $is_package;
	public $ctime;
	
    /**
     * @return Bts_Entity_Order_Items
     */
    public final static function newInstance() {
        return new self();
    }

}

// End ^ Native EOL ^ UTF-8