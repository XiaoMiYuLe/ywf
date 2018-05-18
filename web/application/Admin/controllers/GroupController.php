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

class GroupController extends AdminAbstract
{

    public $perpage = 20;

    public $count;

    public $page;

    /**
     * 用户组列表管理
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $parentid = $this->input->get('parentid', 0);
        
        $data['parentid'] = $parentid;
        $data['groups'] = GroupModel::instance()->getGroups();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'group.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加用户组
     */
    public function add ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $data['parent_groups'] = GroupModel::instance()->getGroups(0);
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'group.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加用户组 - 保存
     */
    public function addSave ()
    {
        $set = $this->_validateGroup();
        if ($set['status'] == 0) {
            try {
                GroupModel::instance()->addGroup($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add group failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 修改用户组
     */
    public function edit ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $groupid = (int) $this->input->query('groupid');
        $group = GroupModel::instance()->fetchByPK($groupid);
        if (null === $group || ! is_array($group)) {
            $this->setStatus(1);
            $this->setError('The group is not exist.');
            return self::RS_SUCCESS;
        }
        
        $data['group'] = $group[0];
        $data['parent_groups'] = GroupModel::instance()->getGroups(0);
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'group.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 修改用户组 - 保存
     */
    public function editSave ()
    {
        $set = $this->_validateGroup();
        if ($set['status'] == 0) {
            try {
                GroupModel::instance()->updateGroup($set['data'], $set['data']['groupid']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit group failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 设置用户组权限 -- 该功能没有完成
     */
    public function editPermission ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSavePermission();
            return self::RS_SUCCESS;
        }
        
        $groupid = (int) $this->input->query('groupid');
        $group = GroupModel::instance()->fetchByPK($groupid);
        if (null === $group || ! is_array($group)) {
            $this->setStatus(1);
            $this->setError('The group is not exist.');
            return self::RS_SUCCESS;
        }
        
        $data['group'] = $group[0];
        $data['permission'] = PermissionModel::instance()->getAllPermissions();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'group.edit.permission');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 保存用户组－校验
     */
    private function _validateGroup ()
    {
        $res = array(
                'status' => 0,
                'error' => null,
                'data' => null
        );
        
        $res['data'] = array(
                'groupid' => $this->input->post('groupid'),
                'parentid' => $this->input->post('parentid'),
                'groupname' => $this->input->post('groupname'),
                'description' => $this->input->post('description')
        );
        
        // 数据验证
        if (empty($res['data']['groupname'])) {
            $res['status'] = 1;
            $res['error'] = '请填写完所有带红色星号的内容';
            return $res;
        }
        
        return $res;
    }
    
    /**
     * ajax - 删除用户组
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isAJAX()) {
            $this->setStatus(1);
            $this->setError('请求方式错误');
            return self::RS_SUCCESS;
        }
        
        $groupid = (int) $this->input->query('groupid');
        $del_user = (int) $this->input->query('del_user', 0);
        
        if ($groupid < 1) {
            $this->setStatus(1);
            $this->setError('用户组ID错误');
            return self::RS_SUCCESS;
        }
        
        /* 获取用户组信息 */
        $group = current(GroupModel::instance()->fetchByPK($groupid));
        
        /* 删除用户组下的所有用户 */
        if ($del_user == 1) {
            $users = UserGroupModel::instance()->fetchByGroupid($groupid);
            if (is_array($users) && count($users) > 0) {
                foreach ($users as $v) {
                    // 删除用户
                    UserModel::instance()->removeUser($v['userid']);
                    // 删除用户对应的权限关系
                    UserPermissionModel::instance()->delete("ptype = 'user' AND parameter='{$v['username']}' AND note = '{$v['username']}'");
                }
            }
        }
        
        /* 删除用户组与用户对应关系 */
        UserGroupModel::instance()->removeUserGroup($groupid);
        
        /* 删除用户组对应的权限关系 */
        UserPermissionModel::instance()->delete("ptype = 'group' AND parameter='{$groupid}' AND note = '{$group['groupname']}'");
        
        /* 删除用户组 */
        GroupModel::instance()->removeGroup($groupid);
        
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8