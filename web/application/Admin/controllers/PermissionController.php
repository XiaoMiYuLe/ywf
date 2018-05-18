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

class PermissionController extends AdminAbstract
{
    public $perpage = 15;
    public $count;
    public $page;
    
    /**
     * 权限列表管理
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $app = $this->input->get('appkey', '');
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $page = $page + 1;
        $pagesize = (int) $this->input->get('pageSize');
        
        $page = $page > 0 ? $page : 1;
        $perpage = $pagesize ? $pagesize : $this->perpage;
        $offset = ($page - 1) * $perpage;
         
        $apps = AppModel::instance()->getAllApps();
        
        $where = $app ? "appkey='" . $app . "'" : null;
        $order = 'permission_id ASC';
        if ($ordername) {
            $order = $ordername . " " . $orderby;
        }
        
        $permissions = PermissionModel::instance()->getPermissions($where, $order, $perpage, $offset);
        $data['count'] = PermissionModel::instance()->getPermissionsCount($where);
        
        $data['app_now'] = $app;
        $data['apps'] = $apps;
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $data['permissions'] = $permissions ? $permissions : array();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'permission.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加权限
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $data['apps'] = AppModel::instance()->getAllApps();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'permission.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加权限 - 保存
     */
    public function addSave()
    {
        $set = $this->_validatePermission();
        if ($set['status'] == 0) {
            try {
                PermissionModel::instance()->addPermission($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add permission failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 修改权限
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $permission_id = (int) $this->input->query('permission_id');
        $permission = PermissionModel::instance()->fetchByPK($permission_id);
        if (null === $permission || ! is_array($permission)) {
            $this->setStatus(1);
            $this->setError('The permission is not exist.');
            return self::RS_SUCCESS;
        }
        $data['permission'] = $permission[0];
        
        $data['apps'] = AppModel::instance()->getAllApps();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'permission.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改权限 - 保存
     */
    public function editSave()
    {
        $set = $this->_validatePermission();
        if ($set['status'] == 0) {
            try {
                PermissionModel::instance()->updatePermission($set['data'], $set['data']['permission_id_now']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit permission failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存权限－校验
     */
    private function _validatePermission()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $res['data'] = array(
                'permission_id_now' => $this->input->post('permission_id_now', ''),
                'permission_id' => $this->input->post('permission_id'),
                'permission_name' => $this->input->post('permission_name'),
                'permission_group' => $this->input->post('permission_group'),
                'appkey' => $this->input->post('appkey'),
                'description' => $this->input->post('description'));
        
        /* 数据验证 */
        if (empty($res['data']['permission_id']) || empty($res['data']['permission_name']) || 
            empty($res['data']['permission_group']) || empty($res['data']['appkey'])) {
            $res['status'] = 1;
            $res['error'] = '请填写完所有带红色星号的内容';
            return $res;
        }
        
        /* 校验该权限ID是否已存在 - 仅在编辑状态下，权限ID未做改变时不做该判断 */
        if ($res['data']['permission_id_now'] != $res['data']['permission_id']) {
            $permission_info = PermissionModel::instance()->fetchByPK($res['data']['permission_id']);
            if (! empty($permission_info)) {
                $res['status'] = 1;
                $res['error'] = '权限ID已存在';
                return $res;
            }
        }
        
        return $res;
    }
}

// End ^ Native EOL ^ UTF-8