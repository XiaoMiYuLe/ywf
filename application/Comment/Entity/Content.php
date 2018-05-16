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

class Comment_Entity_Content extends Zeed_Object
{
    public $id;
	public $pid;
	public $category_id;
	public $to_id;
	public $to_order_item_id;
	public $title;
	public $content;
	public $rank_base;
	public $rank_logistics;
	public $rank_speed;
	public $user_type;
	public $userid;
	public $nums_reply;
	public $nums_agree;
	public $lastreply_userid;
	public $ip;
	public $is_agen;
	public $status;
	public $is_del;
	public $ctime;
	public $mtime;
}

// End ^ Native EOL ^ UTF-8