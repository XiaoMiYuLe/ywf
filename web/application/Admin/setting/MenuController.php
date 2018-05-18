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
 * @since      2010-12-6
 * @version    SVN: $Id$
 */

class MenuController extends AdminAbstract
{
    /**
     * 前台菜单列表
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $group_id = $this->input->get('group_id', 1);
        
        $where = "if_show != -1 AND group_id = {$group_id}";
        $order = array('hid ASC', 'sort_order ASC');
        $menus = System_Model_Frontend_Menu::instance()->getAllForSelect($where, $order);
        
        $groups = System_Model_Frontend_Menu_Group::instance()->fetchAll()->toArray();
        
        $data['group_id'] = $group_id;
        $data['groups'] = $groups;
        $data['menus'] = $menus ? $menus : array();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'menu.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加菜单
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
    
        $data['groups'] = System_Model_Frontend_Menu_Group::instance()->fetchAll()->toArray();
        $data['apps'] = AppModel::instance()->getAllApps();
        $data['menus'] = System_Model_Frontend_Menu::instance()->fetchByFV('pid', 0);
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'menu.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加菜单 - 保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                if ($menu_id = System_Model_Frontend_Menu::instance()->addForEntity($set['data'])) {
                    // 更新 hid
                    $setUpdate = array('hid' => $set['data']['hid'] . ':' . str_pad($menu_id, 4, 0, STR_PAD_LEFT));
                    System_Model_Frontend_Menu::instance()->updateForEntity($setUpdate, $menu_id);
                } else {
                    throw new Zeed_Exception('Add menu failed, please try again.');
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError($e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 修改菜单
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');
    
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
    
        $menu_id = (int) $this->input->query('menu_id');
        
        $menu = System_Model_Frontend_Menu::instance()->fetchByPK($menu_id);
        if (empty($menu)) {
            $this->setStatus(1);
            $this->setError('The menu is not exist.');
            return self::RS_SUCCESS;
        }
        
        $data['menu'] = $menu[0];
        $data['apps'] = AppModel::instance()->getAllApps();
        $data['groups'] = System_Model_Frontend_Menu_Group::instance()->fetchAll()->toArray();
        $data['menus'] = System_Model_Frontend_Menu::instance()->fetchByFV('pid', 0);
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'menu.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改菜单 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                /* 若修改时所选父导航为自身，则复制一份在其自身导航下，否则，正常修改 */
                if ($set['data']['menu_id'] == $set['data']['pid']) {
                    $set['data']['menu_id'] = 0;
                    if (! $menu_id = System_Model_Frontend_Menu::instance()->addForEntity($set['data'])) {
                        throw new Zeed_Exception('编辑导航失败');
                    }
                
                    // 更新 hid
                    $setUpdate = array('hid' => $set['data']['hid'] . ':' . str_pad($menu_id, 4, 0, STR_PAD_LEFT));
                    System_Model_Frontend_Menu::instance()->updateForEntity($setUpdate, $menu_id);
                } else {
                    System_Model_Frontend_Menu::instance()->updateForEntity($set['data'], $set['data']['menu_id']);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit menu failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存菜单－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $res['data'] = array(
                'menu_id' => $this->input->post('menu_id', 0),
                'group_id' => $this->input->post('group_id'),
                'pid' => $this->input->post('pid'),
                'menu' => $this->input->post('menu'),
                'link_type' => $this->input->post('link_type'),
                'appkey' => $this->input->post('appkey'),
                'folder' => $this->input->post('folder'),
                'url' => $this->input->post('url'),
                'if_show' => $this->input->post('if_show'),
                'sort_order' => $this->input->post('sort_order'),
                'target' => $this->input->post('target'),
                'icon' => $this->input->post('icon'),
                'icon_bg' => $this->input->post('icon_bg')
        );
        
        /* 数据验证 */
        if (empty($res['data']['menu'])) {
            $res['status'] = 1;
            $res['error'] = '导航名称不能为空';
            return $res;
        }
        
        if (! $res['data']['icon_bg']) {
            unset($res['data']['icon_bg']);
        }
        
        /* 构造 hid */
        $hid = '0';
        if ($res['data']['pid'] > 0) {
            $parent = System_Model_Frontend_Menu::instance()->fetchByPK($res['data']['pid']);
            $hid = $parent[0]['hid'];
        }
        if ($res['data']['menu_id'] > 0) {
            $hid .= ':' . str_pad($res['data']['menu_id'], 4, 0, STR_PAD_LEFT);
        }
        $res['data']['hid'] = $hid;
        
        return $res;
    }
    
    /**
     * 删除菜单
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $menu_id = (int) $this->input->post('menu_id');
    
        try {
            $menu = System_Model_Frontend_Menu::instance()->fetchByPK($menu_id);
            if ($menu) {
                System_Model_Frontend_Menu::instance()->removeMenu($menu_id);
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('Drop menu failed : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
    
    /**
     * 发布 - 更改导航状态
     */
    public function publish()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $menu_id = (int) $this->input->post('id');
    
        /* 获取文章内容 */
        if (! $menu = System_Model_Frontend_Menu::instance()->fetchByPK($menu_id)) {
            $this->setStatus(1);
            $this->setError('查无此导航');
            return self::RS_SUCCESS;
        }
    
        /* 执行审核 */
        if ($menu[0]['if_show'] == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $this->changeStatus($menu_id, $status);
    
        return self::RS_SUCCESS;
    }
    
    /**
     * 更新导航状态
     */
    private function changeStatus($menu_id, $status = 0, $status_name = 'if_show')
    {
        try {
            $set = array($status_name => $status);
    
            if (is_string($menu_id)) {
                if (strpos($menu_id, ',')) {
                    $menu_id = explode(',', $menu_id);
                } else {
                    $menu_id = array((int) $menu_id);
                }
                $menu_id = implode(',', $menu_id);
            }
    
            $where = "menu_id in ({$menu_id})";
            System_Model_Frontend_Menu::instance()->update($set, $where);
    
            $data['if_show'] = $status;
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('更新状态失败 : ' . $e->getMessage());
            return false;
        }
    
        $this->setError('更新成功');
        $this->setData('data', $data);
        return true;
    }
}

// End ^ Native EOL ^ UTF-8