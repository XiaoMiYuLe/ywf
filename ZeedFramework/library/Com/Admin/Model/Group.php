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
 * @since      Nov 9, 2010
 * @version    SVN: $$Id$$
 */

class Com_Admin_Model_Group extends Com_Admin_Permission_Model
{
    /*
     * @var string The table name.
     */
    protected $_name = 'group';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'groupid';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    public function getGroupByGroupid($groupid)
    {
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('groupid') . ' = ?', $groupid);
        $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE ' . $where;
        $row = $this->getAdapter()->query($sql)->fetch();
        unset($sql);
        return (is_array($row) && count($row) > 0) ? $row : null;
    }
    
    /**
     * 获取用户组的所有权限
     * @param array|integer $groups
     */
    public function getGroupPermissions($groups)
    {
        if (is_array($groups)) {
            $val = implode(',', array_map('intval', $groups));
            $where = $this->getAdapter()->quoteIdentifier('parameter') . ' IN( ' . $val . ' )';
        } else {
            $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('parameter') . ' = ?', (int)$groups);
        }
        $where .= ' AND ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('ptype') . ' = ?', 'group');
        
        $select = $this->getAdapter()->select()->from('admin_user_permission', array('permission_id'))->where($where);
        $rows = $select->query()->fetchAll();
        $ps = array();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $row) {
                $ps[] = $row['permission_id'];
            }
        }
        return $ps;
    }
    
    /**
     * @return Com_Admin_Model_Group
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
