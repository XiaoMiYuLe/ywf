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

class GroupModel extends Com_Admin_Model_Group
{
    /**
     * @var string The table name.
     */
    protected $_name = 'group';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'groupid';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    /**
     * 获取用户组
     * 
     * @param integer $parentid
     * @return multitype:
     */
    public function getGroups($parentid = null)
    {
        if (null === $parentid) {
            $where = null;
        } else {
            $where = "parentid=" . $parentid;
        }
        $order = $this->_primary . " desc";
        return $this->fetchAll($where, $order)->toArray();
    }
    
    /**
     * 添加用户组
     *
     * @param array $set
     * @return integer
     */
    public function addGroup($set)
    {
        if ($set instanceof GroupEntity) {
            $data = $set->toArray();
        } else {
            $entity = new GroupEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->insert($data);
    }
    
    /**
     * 更新用户组
     *
     * @param array $set
     * @param string $groupid
     * @return integer
     */
    public function updateGroup($set, $groupid)
    {
        if ($set instanceof GroupEntity) {
            $data = $set->toArray();
        } else {
            $entity = new GroupEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('groupid') . ' = ?', $groupid);
        return $this->update($data,  $where);
    }
    
    /**
     * 根据用户组ID删除用户组
     *
     * @param integer $groupid
     * @return integer|boolean
     */
    public function removeGroup($groupid)
    {
        $where = array('groupid = ?' => $groupid);
        return $this->delete($where);
    }
    
    /**
     * @return GroupModel
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8