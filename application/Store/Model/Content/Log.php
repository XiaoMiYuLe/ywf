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

class Store_Model_Content_Log extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'content_log';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'log_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'store_';
    
    
    /**
     * @return Store_Model_Content_Free
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8