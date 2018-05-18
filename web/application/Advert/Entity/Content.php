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
 * @since      2016-03-04
 * @version    SVN: $
 */

class Advert_Entity_Content extends Zeed_Object
{
    public $content_id;
    public $type;
    public $title;
    public $image;
    public $link_url;
    public $sort_order;
    public $count;
    public $status;
    public $ctime;
    public $mtime;

    /**
     * @return Advert_Entity_Content
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8