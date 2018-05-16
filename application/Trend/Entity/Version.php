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
 * @since      2016-03-17
 * @version    SVN: $
 */

class Trend_Entity_Version extends Zeed_Object
{
    public $id;
    public $web_code;
    public $ios_code;
    public $android_code;
    public $guide_url;
    public $status;
    public $mtime;

    /**
     * @return Trend_Entity_Version
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8