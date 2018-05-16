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
class System_Model_Frontend_Menu_Group extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'frontend_menu_group';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'group_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'system_';

    
    /**
     * @return System_Model_Frontend_Menu_Group
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8