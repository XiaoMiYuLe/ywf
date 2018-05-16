<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-1-14
 * @version    SVN: $Id$
 */

class Trend_Entity_Attachment extends Zeed_Object
{
    public $attachmentid;
    public $userid;
    public $title;
    public $description;
    public $label;
    public $mediatype;
    public $mimetype;
    public $suffix;
    public $image_width;
    public $image_height;
    public $ctime;
    public $mtime;
    public $filepath;
    public $filesize;
    public $hashcode;
    public $status;
    public $rev;
    
    /**
     * @return Trend_Entity_Attachment
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8