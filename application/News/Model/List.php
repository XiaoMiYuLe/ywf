<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Cas
 * @package    Cas_Model
 * @subpackage Cas_Model_User
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @version    SVN: $Id$
 */
class News_Model_List extends Zeed_Db_Model
{

    /**
     * @var string The table name.
     */
    protected $_name = 'list';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'news_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'news_';

    /**
     * @return News_Model_List
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

}

// End ^ LF ^ encoding
