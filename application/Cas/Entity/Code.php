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
 * @since      2016-03-08
 * @version    SVN: $
 */

class Cas_Entity_Code extends Zeed_Object
{
    public $id;
    public $userid;
    public $type;
    public $action;
    public $send_to;
    public $code;
    public $ctime;
    public $content;

    /**
     * @return Cas_Entity_Code
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8