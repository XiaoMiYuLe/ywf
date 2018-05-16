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
class Store_Entity_Content_Log extends Zeed_Object
{

    public $log_id;
    public $store_id;
    public $opreator_type;
    public $opreator_id;
    public $operator_name;
    public $content;
    public $remark;
    public $ip;
    public $ctime;

    /**
     * @return Store_Entity_Content_Log
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8