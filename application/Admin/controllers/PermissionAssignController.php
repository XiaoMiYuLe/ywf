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

class PermissionAssignController extends AdminAbstract
{
    public $groupinfo;
    public $groupPermissions;
    public $userinfo;
    public $userPermissions;
    public $userGroups;
    public $selfPermissions;
    
    public $appkey;
    public $allApps;
    public $permissions;
    
    /**
     * 查看用户/用户组的权限
     */
    public function index()
    {
        $groupid = $this->input->get('groupid');
        if ($groupid) {
            $this->groupinfo = AdminGroupModel::instance()->getGroupByGroupid($groupid);
            if (empty($this->groupinfo)) {
                exit('no group');
            }
            $this->groupPermissions = AdminUserPermissionModel::instance()->getPermissionDetails('group', $groupid);
            $this->addResult(self::RS_SUCCESS, 'php', 'PermissionAssign.Index.4Group.php');
            return self::RS_SUCCESS;
        }
        $username = $this->input->get('username');
        $this->userinfo = AdminUserModel::instance()->getUserByUsername($username);
        if (empty($this->userinfo)) {
            exit('no user');
        }
        $this->userPermissions = AdminUserPermissionModel::instance()->getAllPermissionDetailsByUsername($username);
        $this->selfPermissionids = AdminUserPermissionModel::instance()->getPermissionids('user', $username);
        $this->userGroups = AdminUserModel::instance()->getUserGroupDetail($username);
        $this->addResult(self::RS_SUCCESS, 'php', 'PermissionAssign.Index.4User.php');
        return self::RS_SUCCESS;
    }
    
    /**
     * 为用户/用户组授权
     */
    public function assign()
    {
        $groupid = $this->input->get('groupid');
        
        if ($groupid) {
            $this->groupinfo = GroupModel::instance()->fetchByPK($groupid);
            if (empty($this->groupinfo)) {
                exit('no group');
            }
            
            $this->groupPermissions = UserPermissionModel::instance()->getPermissionids('group', $groupid);
            $this->allApps = AppModel::instance()->getAllApps();
            
            $this->appkey = $this->input->query('appkey');
            if (empty($this->appkey)) {
                $this->appkey = $this->allApps[0]['appkey'];
            }
            
            $permissions = PermissionModel::instance()->getPermissionsByAppkey($this->appkey);
            $this->permissions = $this->buildPermissionArray($permissions);
            
            $this->addResult(self::RS_SUCCESS, 'php', 'PermissionAssign.Assign.4Group.php');
            return self::RS_SUCCESS;
        }
        
        $userid = $this->input->get('userid');
        $this->appkey = $this->input->query('appkey');
        
        $this->userinfo = UserModel::instance()->fetchByPK($userid);
        if (empty($this->userinfo)) {
            exit('no user');
        }
        
        $this->allApps = AppModel::instance()->getAllApps();
        if (empty($this->appkey)) {
            $this->appkey = $this->allApps[0]['appkey'];
        }
        
        // 个人权限
        $this->selfPermissionids = UserPermissionModel::instance()->getPermissionids('user', $this->userinfo[0]['username']);
        
        // 来源于组的权限
        $ug = UserModel::instance()->getUserGroupsByUsername($this->userinfo[0]['username']);
        if (! empty($ug)) {
            $this->groupPermissions = GroupModel::instance()->getGroupPermissions($ug);
        } else {
            $this->groupPermissions = array();
        }
        
        // 获取模块权限列表
        $permissions = PermissionModel::instance()->getPermissionsByAppkey($this->appkey);
        $p = array();
        if (count($permissions) > 0) {
           $p = $this->buildPermissionArray($permissions);
        }
        
        $this->permissions = $p;
        
        $this->addResult(self::RS_SUCCESS, 'php', 'PermissionAssign.Assign.4User.php');
        return self::RS_SUCCESS;
    }
    
    public function assignPermission()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        try {
            
            if (! $this->input->isAJAX()) {
                throw new Zeed_Exception('请求方式错误');
            }
            
            $ptype = $this->input->query('ptype');
            if ($ptype != 'group' && $ptype != 'user') {
                throw new Zeed_Exception('授权类型错误');
            }
            
            $parameter = trim($this->input->query('parameter'));
            if ($parameter == '') {
                throw new Zeed_Exception('请求参数缺失');
            }
            
            $permission_id = (int) $this->input->query('permission_id');
            if ($permission_id < 1) {
                throw new Zeed_Exception('权限ID错误');
            }
            
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError($e->getMessage());
            return self::RS_SUCCESS;
        }
        
        /* 加入 hid */
        $appkey = $this->input->query('appkey');
        
        /* 获取终极导航信息 */
        $where_nav = "link != ''";
        $navs = System_Model_Navigation::instance()->fetchByWhere($where_nav);
        
        /* 获取应用权限信息 */
        $url_pos = array();
        $where_app_permission = "appkey = '{$appkey}' AND permission_id = '{$permission_id}'";
        $app_permission = AppPermissionModel::instance()->fetchByWhere($where_app_permission);
        if (! empty($app_permission)) {
            foreach ($app_permission as $k => $v) {
                $url_pos[$k] = '/' . strtolower($v['module']);
                if (strtolower($v['controller']) != 'index' || strtolower($v['action']) != 'index') {
                    $url_pos[$k] .= '/' . lcfirst($v['controller']);
                }
                if (strtolower($v['action']) != 'index') {
                    $url_pos[$k] .= '/' . lcfirst($v['action']);
                } else {
                    $url_pos[$k] .= '/index';
                }
            }
        }
        
        /* 处理 hid 信息 */
        $nav_hid = array();
        if (! empty($navs)) {
            foreach ($navs as $v) {
                $link_arr = explode('?', $v['link']);
                $link_path = $link_arr[0];
                $level_count = substr_count($link_path, '/');
                
                /* 如果只有两层，补充第三层默认的index */
                if ($level_count < 3) {
                    $link_path = $link_path . '/index';
                }
                
                if (in_array($link_path, $url_pos)) {
                    $nav_hid[] = $v['hid'];
                }
            }
        }
        
        $nav_hid = implode(',', $nav_hid);
        /* 加入 hid @end */
        
        if ($ptype == 'group') {
            $parameter_info = current(GroupModel::instance()->fetchByPK($parameter));
            $parameter_name = $parameter_info['groupname'];
        } else {
            $parameter_info = current(UserModel::instance()->fetchByPK($parameter));
            $parameter_name = $parameter_info['username'];
            $parameter = $parameter_name;
        }
        
        if ($this->input->query('assign') == '1') {
            if (! UserPermissionModel::instance()->hasPermission($ptype, $parameter, $permission_id)) {
                UserPermissionModel::instance()->addUserPermission($ptype, $parameter, $permission_id, $nav_hid, $parameter_name);
            }
            return self::RS_SUCCESS;
        }
        
        UserPermissionModel::instance()->removeUserPermission($ptype, $parameter, $permission_id);
        return self::RS_SUCCESS;
    }
    
    /**
     * 生成权限组方法
     * 
     * @param array $permissions
     * @return Ambigous <multitype:, unknown>
     */
    private function buildPermissionArray($permissions)
    {
        $p = array();
        $groupname = false;
        $i = 0;

        if (empty($permissions)) {
            return $p;
        }
        
        foreach ($permissions as $pm) {
            if ($groupname != $pm['permission_group']) {
                if ($groupname !== false) {
                    $i++;
                }
                $groupname = $pm['permission_group'];
            }
            $p[$i]['permission_group'] = $groupname;
            $p[$i]['permissions'][] = $pm;
        }
        
        return $p;
    }
}

// End ^ LF ^ encoding
