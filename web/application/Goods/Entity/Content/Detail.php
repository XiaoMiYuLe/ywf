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

class Goods_Entity_Content_Detail extends Zeed_Object
{
    public $content_id;
	public $memo;
	public $body;
	public $meta_title;
	public $meta_keywords;
	public $meta_description;
	public $tag;
	public $property;
	public $spec;
	public $attachment;
	public $related;
    
    /**
     * @return Goods_Entity_Content_Detail
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8