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

class UserGroupModel extends Com_Admin_Model_Group
{
    /**
     * @var string The table name.
     */
    protected $_name = 'user_group';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'userid';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    /**
     * 根据用户组ID获取记录
     *
     * @param integer $groupid
     * @return array|boolean
     */
    public function fetchByGroupid($groupid)
    {
        $select = $this->select()->from($this->getTable())->where("groupid = ?", $groupid);
        $rows = $select->query()->fetchAll();
        return (is_array($rows) && count($rows) > 0) ? $rows : null;
    }
    
    /**
     * 更新用户与用户组关系
     *
     * @param integer $userid
     * @param array $groupids
     * @return integer
     */
    public function updateUserGroup($userid, $groupids, $username = null)
    {
        $this->getAdapter()->delete($this->getTable(), "userid=" . $userid);
        if (is_array($groupids) && count($groupids) > 0) {
            foreach ($groupids as $groupid) {
                $set = array("userid" => $userid, "groupid" => $groupid, "username" => $username);
        
                if ($set instanceof UserGroupEntity) {
                    $data = $set->toArray();
                } else {
                    $entity = new UserGroupEntity();
                    $data = $entity->fromArray($set)->toArray();
                }
        
                $this->insert($data);
            }
        }
        return true;
    }
    
    /**
     *  通过用户ID获取信息
     *
     * @param integer $userid
     * @return array|boolean
     */
    public function fetchByUserid($userid)
    {
        $select = $this->select()->from($this->getTable(), 'groupid')->where("userid = ?", $userid);
        $rows = $select->query()->fetchAll();
        return (is_array($rows) && count($rows) > 0) ? $rows : null;
    }
    
    /**
     * 根据用户组ID删除用户与用户组对应关系
     * 
     * @param integer $groupid
     * @return integer|boolean
     */
    public function removeUserGroup($groupid)
    {
        $where = array('groupid = ?' => $groupid);
        return $this->delete($where);
    }
     
    /**
     * @return UserGroupModel
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8