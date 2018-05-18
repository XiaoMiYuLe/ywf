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

class UserPermissionModel extends Com_Admin_Model_Group
{
    /**
     * @var string The table name.
     */
    protected $_name = 'user_permission';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    /**
     * 添加拥有权限
     * @param $ptype
     * @param $parameter
     * @param $permission_id
     * @param $note
     */
    public function addUserPermission($ptype, $parameter, $permission_id, $navigation_hid, $note = NULL)
    {
        $set = array('ptype' => $ptype, 'parameter' => $parameter, 'permission_id' => $permission_id, 'navigation_hid' => $navigation_hid);
        if (! is_null($note)) {
            $set['note'] = $note;
        }
        
        return $this->insert($set);
    }
    
    /**
     * 删除拥有权限
     * @param $ptype
     * @param $parameter
     * @param $permission_id
     * @param $schemeid
     */
    public function removeUserPermission($ptype, $parameter, $permission_id, $schemeid = NULL)
    {
        $set = array('ptype = ?'=>$ptype, 'parameter = ?'=>$parameter, 'permission_id = ?'=>$permission_id);
        if (!is_null($schemeid)) {
            $set['schemeid = ?'] = $schemeid;
        }
        
        return $this->delete($set);
    }
    
    /**
     * 是否有记录拥有某权限
     * @param $ptype
     * @param $parameter
     * @param $permission_id
     * @param $schemeid
     * @return boolean
     */
    public function hasPermission($ptype, $parameter, $permission_id, $schemeid = NULL)
    {
        $set = array('ptype = ?'=>$ptype, 'parameter = ?'=>$parameter, 'permission_id = ?'=>$permission_id);
        if (!is_null($schemeid)) {
            $set['schemeid = ?'] = $schemeid;
        }
        
        return $this->fetchRow($set);
    }
    
    /**
     * 获取拥有的权限ID
     * @param $ptype
     * @param $parameter
     * @param $schemeid
     */
    public function getPermissionids($ptype, $parameter)
    {
        $select = $this->getAdapter()->select()->from($this->getTable(), array('permission_id'));
        $select->where($this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('admin_user_permission.ptype') . ' = ?', $ptype));
        $select->where($this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('admin_user_permission.parameter') . ' = ?', $parameter));
        
        $rows = $select->query()->fetchAll();
        
        $ids = array();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $row) {
                $ids[] = $row['permission_id'];
            }
        }
        return $ids;
    }
    
    /**
     * 获取拥有权限，包含权限详细
     * @param $ptype
     * @param $parameter
     * @param $schemeid
     */
    public function getPermissionDetails($ptype, $parameter, $schemeid = NULL)
    {
        $select = $this->getAdapter()->select()->from($this->getTable(),array());
        $select->where($this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('admin_user_permission.ptype') . ' = ?', $ptype));
        $select->where($this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('admin_user_permission.parameter') . ' = ?', $parameter));
        if (!is_null($schemeid)) {
            $select->where($this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('admin_user_permission.schemeid') . ' = ?', $schemeid));
        }
        $select->joinLeft('admin_permission', 'admin_user_permission.permission_id = admin_permission.permission_id', '*');
        $select->order('admin_permission.appkey ASC');
        $select->order('admin_permission.permission_group  ASC');
        return $select->query()->fetchAll();
    }
    
    /**
     * 获取用户所有权限，包含权限详细
     * @param $username
     * @param $schemeid
     */
    public function getAllPermissionDetailsByUsername($username, $schemeid = NULL)
    {
        
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('admin_user_permission.ptype') . ' = ?', 'user');
        $where .= ' AND '.$this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('admin_user_permission.parameter') . ' = ?', $username);
        
        $groups = AdminUserModel::instance()->getUserGroupsByUsername($username);
        if (!empty($groups)) {
            $where = '('.$where.') OR (';
            $where .= $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('admin_user_permission.ptype') . ' = ?', 'group');
            $where .= ' AND '.$this->getAdapter()->quoteIdentifier('admin_user_permission.parameter') . ' IN ('.implode(',', $groups).')';
            $where .= ')';
        }
        
        if (!is_null($schemeid)) {
            $where = '('.$where.') AND '.$this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('admin_user_permission.schemeid') . ' = ?', $schemeid);
        }
        
        $select = $this->getAdapter()->select()->from('admin_permission','*');
        $select->where($where);
        $select->joinInner('admin_user_permission', 'admin_user_permission.permission_id = admin_permission.permission_id', array());
        $select->order('admin_permission.appkey ASC');
        $select->order('admin_permission.permission_group  ASC');
        $select->group('admin_permission.permission_id');
        
        return $select->query()->fetchAll();
    }

    /**
     * @return UserPermissionModel
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
