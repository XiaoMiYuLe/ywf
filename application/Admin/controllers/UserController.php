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
class UserController extends AdminAbstract
{

    public $perpage = 20;

    public $count;

    public $page;

    /**
     * 用户列表管理
     */
    public function index ()
    {
        $groupid = $this->input->get('groupid', 0);
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $key = trim($this->input->get('key'));
        
        $page = $page + 1;
        $perpage = $this->input->get('pageSize', $this->perpage);
        $offset = ($page - 1) * $perpage;
        
        $where = null;
        if ($key) {
            $where = "username like '%" . $key . "%' OR fullname like '%" . $key . "%'";
        }
        
        $order = 'userid DESC';
        if ($ordername) {
            $order = $ordername . " " . $orderby;
        }
        
        /* 组别处理 */
        $groups = GroupModel::instance()->getGroups();
        $dicGroups = array();
        foreach ($groups as $group) {
            $dicGroups[$group['groupid']] = $group['groupname'];
        }
        
        /* 查询用户处理 */
        if ($groupid != 0) {
            $users = UserModel::instance()->getUsersByGroup($where, $order, $perpage, $offset);
            $count = UserModel::instance()->getUsersCountByGroup($where);
        } else {
            $users = UserModel::instance()->getUsers($where, $order, $perpage, $offset);
            $count = UserModel::instance()->getUsersCount($where);
        }
        
        foreach ($users as &$user) {
            $strgroup = $user["groups"];
            $arrgroup = explode(",", $strgroup);
            $arrName = array();
            foreach ($arrgroup as $g) {
                if (isset($dicGroups[$g])) {
                    array_push($arrName, $dicGroups[$g]);
                }
            }
            $user["groups"] = implode(",", $arrName);
        }
        
        $data['users'] = $users ? $users : array();
        $data['count'] = $count;
        $data['groups'] = $groups;
        $data['groupid'] = $groupid;
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_SUCCESS, 'php', 'user.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加用户
     */
    public function add ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $data['groups'] = GroupModel::instance()->getGroups();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'user.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加用户 - 保存
     */
    public function addSave ()
    {
        $set = $this->_validateUser();
        if ($set['status'] == 0) {
            try {
                // 添加用户
                $userid = UserModel::instance()->addUser($set['data']);
                
                // 更新用户与用户组关系
                UserGroupModel::instance()->updateUserGroup($userid, $set['data']['groups'], $set['data']['username']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add user failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 修改用户
     */
    public function edit ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $userid = (int) $this->input->query('userid', null);
        $user = UserModel::instance()->fetchByPK($userid);
        if (null === $user || ! $user) {
            $this->setStatus(1);
            $this->setError('The user is not exist.');
            return self::RS_SUCCESS;
        }
        
        $user_groupids = UserGroupModel::instance()->fetchByPK($userid, array('groupid'));
        $groupids = array();
        if (! empty($user_groupids)) {
            foreach ($user_groupids as $v) {
                $groupids[] = $v['groupid'];
            }
        }
        
        $data['userid'] = $userid;
        $data['user'] = $user[0];
        $data['user_groupids'] = $groupids;
        $data['groups'] = GroupModel::instance()->getGroups();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'user.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 修改用户 - 保存
     */
    public function editSave ()
    {
        $set = $this->_validateUser();
        if ($set['status'] == 0) {
            try {
                /* 基础数据处理 */
                $userid = $set['data']['userid'];
                $username = $set['data']['username'];
                unset($set['data']['username']);
                
                if (empty($set['data']["password"])) {
                    unset($set['data']["password"], $set['data']["salt"]);
                }
                
                /* 修改admin_user表 */
                UserModel::instance()->updateUser($set['data'], $userid);
                
                // 更新用户与用户组关系
                UserGroupModel::instance()->updateUserGroup($userid, $set['data']['groups'], $username);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit user failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 保存用户－校验
     */
    private function _validateUser ()
    {
        $res = array(
                'status' => 0,
                'error' => null,
                'data' => null
        );
        
        $nowtime = time();
        
        $res['data'] = array(
                'userid' => $this->input->post('userid', ''),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'fullname' => $this->input->post('fullname'),
                'nickname' => $this->input->post('nickname'),
                'gender' => $this->input->post('gender'),
                'idcard' => $this->input->post('idcard'),
                'email' => $this->input->post('email'),
                'status' => $this->input->post('status'),
                'groups' => $this->input->post('groups'),
                'salt' => Zeed_Util::genRandomString(10), // 给salt附上随机字符串，用于密码的加密算法
                'mtime' => date(DATETIME_FORMAT, $nowtime)
        );
        
        /* 数据验证 */
        if (! $res['data']['username'] && (empty($res['data']['username']) || empty($res['data']['password']))) {
            $res['status'] = 1;
            $res['error'] = '请填写完所有带红色星号的内容';
            return $res;
        }
        
        /* 添加状态下，判断用户名是否存在 */
        if (empty($res['data']['userid'])) {
            $res['data']['ctime'] = date(DATETIME_FORMAT, $nowtime);
            $user_info = UserModel::instance()->fetchByUsername($res['data']['username']);
            if (! empty($user_info)) {
                $res['status'] = 1;
                $res['error'] = '用户名已存在';
            }
        }
        
        /* 密码处理 */
        if (! empty($res['data']["password"])) {
            $res['data']["password"] = Zeed_Encrypt::encode('Md5Md5', $res['data']["password"], $res['data']['salt']);
        }
        
        return $res;
    }
    
    /**
     * ajax - 删除用户
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isAJAX()) {
            $this->setStatus(1);
            $this->setError('请求方式错误');
            return self::RS_SUCCESS;
        }
    
        $userid = (int) $this->input->query('userid');
    
        if ($userid < 1) {
            $this->setStatus(1);
            $this->setError('用户ID错误');
            return self::RS_SUCCESS;
        }
        
        /* 获取用户信息 */
        $user = current(UserModel::instance()->fetchByPK($userid));
        
        /* 删除用户对应的权限关系 */
        UserPermissionModel::instance()->delete("ptype = 'user' AND parameter='{$user['username']}' AND note = '{$user['username']}'");
        
        /* 删除用户 */
        UserModel::instance()->removeUser($userid);
    
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8