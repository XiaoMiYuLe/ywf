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

class UserModel extends Com_Admin_Model_User
{
    /**
     * @var string The table name.
     */
    protected $_name = 'user';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'userid';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    /**
     * 分页获取用户
     * 
     * @param string $where
     * @param string $order
     * @param number $count
     * @param number $offset
     * @return array|boolean
     */
    public function getUsers($where = null, $order = null, $count = 20, $offset = 0)
    {
        $select = $this->getAdapter()->select()->from(array("u" => $this->getTable()))->limit($count, $offset);
        if ($where) {
            $select->having($where);
        }
        if ($order) {
            $select->order($order);
        } else {
            $select->order('userid ASC');
        }
        $select->joinLeft(array("g" => "admin_user_group"), "u.userid = g.userid group by u.userid", "group_concat(g.groupid) as groups");
        return $select->query()->fetchAll();
    }
    
    /**
     * 获取用户数量
     * 
     * @param string $where
     * @return integer
     */
    public function getUsersCount($where = null)
    {
        $select = $this->select()->from($this->getTable(), array("count_num" => "count(*)"));
        if ($where) {
            $select->where($where);
        }
        $row = $select->query()->fetch();
        return $row ? $row["count_num"] : 0;
    }
    
    /**
     * 根据组获取用户
     * 
     * @param $where
     * @param $order
     * @param $count
     * @param $offset
     */
    public function getUsersByGroup($where = null, $order = null, $count = 20, $offset = 0)
    {
        $select = $this->getAdapter()->select()->from(array("g" => "admin_user_group"))->limit($count, $offset);
        if ($where) {
            $select->where($where);
        }
        if ($order) {
            $select->order($order);
        } else {
            $select->order('userid ASC');
        }
        $rows = $select->query()->fetchAll();
        
        $users = array();
        foreach ($rows as $row) {
            $infos = $this->getAdapter()->select()->from(array("u" => $this->getTable()));
            $infos->join(array("g" => "admin_user_group"), "u.userid='{$row["userid"]}' AND u.userid = g.userid group by u.userid", "group_concat(g.groupid) as groups");
            $results = $infos->query()->fetchAll();
            array_push($users, $results[0]);
        }
        return $users;
    }
    
    /**
     * 根据组获取用户数量
     * 
     * @param unknown_type $where
     */
    public function getUsersCountByGroup($where)
    {
        $select = $this->getAdapter()->select()->from("admin_user_group", array('count_num' => "COUNT(*)"));
        if ($where) {
            $select->where($where);
        }
        $row = $select->query()->fetch();
        return $row ? $row["count_num"] : 0;
    }
    
    /**
     * 添加用户
     *
     * @param array $set
     * @return integer
     */
    public function addUser($set)
    {
        if ($set instanceof UserEntity) {
            $data = $set->toArray();
        } else {
            $entity = new UserEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->insert($data);
    }
    
    /**
     * 更新用户
     *
     * @param array $set
     * @param string $userid
     * @return integer
     */
    public function updateUser($set, $userid)
    {
        if ($set instanceof UserEntity) {
            $data = $set->toArray();
        } else {
            $entity = new UserEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('userid') . ' = ?', $userid);
        return $this->update($data,  $where);
    }
    
     /**
      *  通过用户名获取用户详细信息
      *
      * @param string $username
      * @return array|boolean
      */
     function fetchByUsername($username)
     {
         $select = $this->select()->from($this->getTable())->where("username = ?", $username);
         $row = $select->query()->fetch();
         return (is_array($row) && count($row) > 0) ? $row : null;
     }
     
     /**
      * 根据用户ID删除用户，支持批量
      *
      * @param integer|string|array $userids
      * @return integer|boolean
      */
     public function removeUser($userids)
     {
         if (is_array($userids) && count($userids) > 0) {
             $userids = implode(',', $userids);
             $where = "userid in ({$userids})";
         } elseif (is_string($userids)) {
             $where = "userid in ({$userids})";
         } else {
             $where = array('userid = ?' => $userids);
         }
         return $this->delete($where);
     }
     
    /**
     * @return UserModel
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ Native EOL ^ UTF-8