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
 * @since      2011-4-28
 * @version    SVN: $Id$
 */
class Trend_Model_Redis extends Zeed_Db_Model_Redis
{

    /**
     *
     * @return Trend_Model_Redis
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ Native EOL ^ UTF-8