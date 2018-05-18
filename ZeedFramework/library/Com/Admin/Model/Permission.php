<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 12, 2010
 * @version    SVN: $$Id$$
 */

class Com_Admin_Model_Permission extends Com_Admin_Permission_Model
{
    /*
     * @var string The table name.
     */
    protected $_name = 'permission';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'permission_id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    /**
     * @return Com_Admin_Model_Permission
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
