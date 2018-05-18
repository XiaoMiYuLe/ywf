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

class PermissionModel extends Com_Admin_Model_Group
{
    /**
     * @var string The table name.
     */
    protected $_name = 'permission';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'permission_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    /**
     * 获取所有权限
     *
     * @return multitype:
     */
    public function getAllPermissions()
    {
        return $this->fetchAll(null, $this->_primary)->toArray();
    }
    
    /**
     * 获取APP所有的权限列表
     * 
     * @param $appkey
     */
    public function getPermissionsByAppkey($appkey)
    {
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('appkey') . ' = ?', $appkey);
        return $this->fetchAll($where, 'permission_group ASC')->toArray();
    }
    
    /**
     * 分页获取权限
     * 
     * @param string $where
     * @param string $order
     * @param number $count
     * @param number $offset
     * @return array|boolean
     */
    public function getPermissions($where = null, $order = null, $count = 20, $offset = 0)
    {
        $select = $this->select()->from($this->getTable())->limit($count, $offset);
        if ($where) {
            $select->where($where);
        }
        if ($order) {
            $select->order($order);
        } else {
            $select->order('permission_id ASC');
        }
    
        return $select->query()->fetchAll();
    }
    
    /**
     * 获取权限数量
     * 
     * @param string $where
     * @return integer
     */
    public function getPermissionsCount($where = null)
    {
        $select = $this->select()->from($this->getTable(), array("count_num" => "count(*)"));
        if ($where) {
            $select->where($where);
        }
        $row = $select->query()->fetch();
        return $row ? $row["count_num"] : 0;
    }
    
    /**
     * 添加权限
     * 
     * @param array $set
     * @return integer
     */
    public function addPermission($set)
    {
        if ($set instanceof PermissionEntity) {
            $data = $set->toArray();
        } else {
            $entity = new PermissionEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->insert($data);
    }
    /**
     * 更新权限
     * 
     * @param array $set
     * @param integer $permissionid
     * @return integer
     */
    public function updatePermission($set, $permissionid)
    {
        if ($set instanceof PermissionEntity) {
            $data = $set->toArray();
        } else {
            $entity = new PermissionEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('permission_id') . ' = ?', $permissionid);
        return $this->update($data, $where);
    }
    
    /**
     * @return PermissionModel
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8