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
 * @since      2011-3-21
 * @version    SVN: $Id$
 */

class Advert_Entity_Page extends Zeed_Object
{
	public $page_id;
	public $title;
	public $sort_order;
	public $ctime;

	/**
	 * @return Advert_Entity_Page
	 */
	public final static function newInstance()
	{
	    return new self();
	}
}