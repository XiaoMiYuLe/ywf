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

/**
 * 应用程序操作所需要的用户权限
 */
class Com_Admin_Model_AppPermission extends Com_Admin_Permission_Model
{
    /*
     * @var string The table name.
     */
    protected $_name = 'app_permission';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = array('appkey','module','controller','action');
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    /**
     * 获取执行Action操作所需要的权限ID
     * @param string $appkey
     * @param string $module
     * @param string $controller
     * @param string $action
     * @return integer 
     */
    public function getActionPermission($appkey, $module, $controller, $action)
    {
        $rows = $this->find($appkey, $module, $controller, $action)->toArray();
        return (is_array($rows) && count($rows) > 0) ? $rows[0]['permission_id'] : null;
    }
    
    /**
     * @return Com_Admin_Model_AppPermission
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
