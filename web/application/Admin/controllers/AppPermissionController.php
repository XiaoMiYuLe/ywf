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
class AppPermissionController extends AdminAbstract
{

    public $perpage = 20;

    public $count;

    public $page;

    /**
     * 动作列表管理
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $app = $this->input->get('appkey', '');
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('page');
        
        $page = $page > 0 ? $page : 1;
        $perpage = $this->perpage;
        $offset = ($page - 1) * $perpage;
        $order = 'permission_id ASC';
        if ($ordername) {
            $order = $ordername . " " . $orderby;
        }
        $where = $app ? "a.appkey='{$app}'" : null;
        
        $apps = AppModel::instance()->getAllApps();
        $permissions = PermissionModel::instance()->getPermissionsByAppkey($app);
        
        $app_permissions = AppPermissionModel::instance()->getAppPermissions($where, $order, $perpage, $offset);
        $data['count'] = AppPermissionModel::instance()->getAppPermissionsCount($where);
        
        $data['app_now'] = $app;
        $data['apps'] = $apps;
        $data['permissions'] = $permissions;
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $data['app_permissions'] = $app_permissions ? $app_permissions : array();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'app.permission.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加动作
     */
    public function add ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $data['appkey_from_permission'] = $this->input->query('appkey', '');
        $data['permission_id_from_permission'] = $this->input->query('permission_id', '');
        
        $data['apps'] = AppModel::instance()->getAllApps();
        $data['permissions'] = PermissionModel::instance()->getAllPermissions();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'app.permission.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加动作 - 保存
     */
    public function addSave ()
    {
        $set = $this->_validateAppPermission();
        if ($set['status'] == 0) {
            try {
                foreach ($set['data']['action'] as $act) {
                    $act = trim($act);
                    if ($act) {
                        $set['data']["action"] = $act;
                        AppPermissionModel::instance()->addAppPermission($set['data']);
                    }
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add apppermission failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 修改动作
     */
    public function edit ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $appkey = $this->input->query('ap_appkey');
        $module = $this->input->query('ap_module');
        $controller = $this->input->query('ap_controller');
        $action = $this->input->query('ap_action');
        
        $where = "appkey='$appkey' AND module='$module' AND controller='$controller' AND action='$action'";
        $app_permission = AppPermissionModel::instance()->getAppPermissionByKeys($where);
        if (null === $app_permission || ! is_array($app_permission)) {
            $this->setStatus(1);
            $this->setError('The app_permission is not exist.');
            return self::RS_SUCCESS;
        }
        
        $data['app_permission'] = $app_permission;
        $data['apps'] = AppModel::instance()->getAllApps();
        $data['permissions'] = PermissionModel::instance()->getAllPermissions();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'app.permission.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 修改动作 - 保存
     */
    public function editSave ()
    {
        $set = $this->_validateAppPermission();
        if ($set['status'] == 0) {
            $keys = $set['data']["keys"];
            $keyArr = explode(",", $keys, 4);
            $where = "appkey='$keyArr[0]' AND module='$keyArr[1]' AND controller='$keyArr[2]' AND action='$keyArr[3]'";
            
            try {
                $set['data']['action'] = $set['data']['action'][0];
                AppPermissionModel::instance()->updateAppPermission($set['data'], $where);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit app_permission failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 保存动作－校验
     */
    private function _validateAppPermission ()
    {
        $res = array(
                'status' => 0,
                'error' => null,
                'data' => null
        );
        
        $res['data'] = array(
                'appkey' => $this->input->post('ap_appkey'),
                'module' => $this->input->post('ap_module'),
                'controller' => $this->input->post('ap_controller'),
                'action' => explode(',', $this->input->post('ap_action')),
                'permission_id' => $this->input->post('ap_permission_id'),
                'keys' => $this->input->post('keys')
        );
        
        /* 数据验证 */
        if (empty($res['data']['appkey']) || empty($res['data']['module']) || empty($res['data']['controller'])) {
            $res['status'] = 1;
            $res['error'] = '请填写完所有带红色星号的内容';
            return $res;
        }
        
        if (count($res['data']['action']) < 1 || empty($res['data']['permission_id'])) {
            $res['status'] = 1;
            $res['error'] = '请填写完所有带红色星号的内容';
            return $res;
        }
        
        return $res;
    }
}

// End ^ Native EOL ^ UTF-8