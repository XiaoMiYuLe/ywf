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

class Article_Entity_Content extends Zeed_Object
{
    public $content_id;
	public $parent_id;
	public $title;
	public $subtitle;
	public $alias;
	public $image;
	public $category;
	public $attachment;
	public $label;
	public $user_type;
	public $userid;
	public $ip;
	public $ctime;
	public $mtime;
	public $ptime;
	public $rev;
	public $status;
	public $pinned;
	public $recommended;
	public $count;
	public $link;
    
    /**
     * @return Article_Entity_Content
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8