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
class AppPermissionModel extends Com_Admin_Model_Group
{
    /**
     * @var string The table name.
     */
    protected $_name = 'app_permission';

    /**
     * @var array Primary key.
     */
    protected $_primary = array(
            'appkey',
            'module',
            'controller',
            'action'
    );

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';

    /**
     * 分页获取动作
     *
     * @param string $where            
     * @param string $order            
     * @param number $count            
     * @param number $offset            
     * @return array boolean
     */
    public function getAppPermissions ($where = null, $order = null, $count = 20, $offset = 0)
    {
        $select = $this->getAdapter()
            ->select()
            ->from(array(
                "a" => $this->getTable()
        ))
            ->limit($count, $offset);
        if ($where) {
            $select->where($where);
        }
        if ($order) {
            $select->order($order);
        } else {
            $select->order('permission_id ASC');
        }
        $select->join(array(
                'p' => 'admin_permission'
        ), "p.permission_id=a.permission_id", "p.permission_name as permission_name");
        return $select->query()->fetchAll();
    }

    /**
     * 获取动作数量
     *
     * @param string $where            
     * @return integer
     */
    public function getAppPermissionsCount ($where = null)
    {
        $select = $this->select()->from(array(
                "a" => $this->getTable()
        ), array(
                "count_num" => "count(*)"
        ));
        if ($where) {
            $select->where($where);
        }
        $row = $select->query()->fetch();
        return $row ? $row["count_num"] : 0;
    }

    /**
     * 根据关键字段获取动作详情
     *
     * @param string $where            
     * @return array boolean
     */
    public function getAppPermissionByKeys ($where)
    {
        $select = $this->select()
            ->from($this->getTable())
            ->where($where);
        return $select->query()->fetch();
    }

    /**
     * 添加动作
     *
     * @param array $set            
     * @return integer
     */
    public function addAppPermission ($set)
    {
        if ($set instanceof AppPermissionEntity) {
            $data = $set->toArray();
        } else {
            $entity = new AppPermissionEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->insert($data);
    }

    /**
     * 更新动作
     *
     * @param array $set            
     * @param string $where            
     * @return integer
     */
    public function updateAppPermission ($set, $where)
    {
        if ($set instanceof AppPermissionEntity) {
            $data = $set->toArray();
        } else {
            $entity = new AppPermissionEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->update($data, $where);
    }

    /**
     * @return AppPermissionModel
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

