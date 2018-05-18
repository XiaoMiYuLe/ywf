<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Apr 23, 2010
 * @version    SVN: $Id: IndexController.php 8811 2010-12-04 03:32:38Z Cyrano $
 */

class NavigationController extends PanelAbstract
{
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $where = 'status != -1';
        $order = array('hid ASC', 'sort_order ASC');
        $navigations = System_Model_Navigation::instance()->getAllForSelect($where, $order);
        
        $data['navigations'] = $navigations ? $navigations : array();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'navigation.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加导航
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $where = null;
        $order = array('hid ASC', 'sort_order ASC');
        $data['navigations'] = System_Model_Navigation::instance()->getAllForSelect($where, $order);
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'navigation.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加导航 - 保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                unset($set['data']['navigation_id']);
                if ($navigation_id = System_Model_Navigation::instance()->addForEntity($set['data'])) {
                    // 更新 hid
                    $setUpdate = array('hid' => $set['data']['hid'] . ':' . str_pad($navigation_id, 4, 0, STR_PAD_LEFT));
                    System_Model_Navigation::instance()->updateForEntity($setUpdate, $navigation_id);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加导航失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 编辑导航
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $where = null;
        $order = array('hid ASC', 'sort_order ASC');
        $data['navigations'] = System_Model_Navigation::instance()->getAllForSelect($where, $order);
        
        $navigation_id = $this->input->query('navigation_id', 0);
        $navigation = System_Model_Navigation::instance()->fetchByPK($navigation_id);
        if (empty($navigation)) {
            $this->setStatus(1);
            $this->setError('该导航不存在');
            return self::RS_SUCCESS;
        }
        $data['navigation'] = $navigation[0];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'navigation.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改导航 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                /* 若修改时所选父导航为自身，则复制一份在其自身导航下，否则，正常修改 */
                if ($set['data']['navigation_id'] == $set['data']['parent_id']) {
                    $set['data']['navigation_id'] = 0;
                    if (! $navigation_id = System_Model_Navigation::instance()->addForEntity($set['data'])) {
                        throw new Zeed_Exception('编辑导航失败');
                    }
                    
                    // 更新 hid
                    $setUpdate = array('hid' => $set['data']['hid'] . ':' . str_pad($navigation_id, 4, 0, STR_PAD_LEFT));
                    System_Model_Navigation::instance()->updateForEntity($setUpdate, $navigation_id);
                } else {
                    System_Model_Navigation::instance()->updateForEntity($set['data'], $set['data']['navigation_id']);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑导航失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存导航－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $res['data'] = array(
                'navigation_id' => (int)$this->input->post('navigation_id', 0),
                'parent_id' => $this->input->post('parent_id'),
                'title' => $this->input->post('title'),
                'link' => $this->input->post('link'),
                'description' => $this->input->post('description'),
                'sort_order' => $this->input->post('sort_order'),
                'status' => $this->input->post('status'),
                'icon' => $this->input->post('icon'),
                'icon_bg' => $this->input->post('icon_bg'),
                'mtime' => date(DATETIME_FORMAT));
    
        /* 数据验证 */
        if (empty($res['data']['title'])) {
            $res['status'] = 1;
            $res['error'] = '分类名称不能为空';
            return $res;
        }
        
        if (! $res['data']['icon_bg']) {
            unset($res['data']['icon_bg']);
        }
        
        if (! $res['data']['navigation_id']) {
            $res['data']['ctime'] = $res['data']['mtime'];
        }
        
        /* 构造 hid */
        $hid = '0';
        if ($res['data']['parent_id'] > 0) {
            $parent = System_Model_Navigation::instance()->fetchByPK($res['data']['parent_id']);
            $hid = $parent[0]['hid'];
        }
        if ($res['data']['navigation_id'] > 0) {
            $hid .= ':' . str_pad($res['data']['navigation_id'], 4, 0, STR_PAD_LEFT);
        }
        
        /* 编辑状态，不可转移至自身的子孙级导航下 */
        if ($res['data']['navigation_id'] > 0 && $res['data']['navigation_id'] != $res['data']['parent_id']) {
            $navigation = System_Model_Navigation::instance()->fetchByPK($res['data']['navigation_id']);
            if ($hid != $navigation[0]['hid'] && strpos($hid, $navigation[0]['hid']) !== false) {
                $res['status'] = 1;
                $res['error'] = '不可移至自身的下级导航中';
                return $res;
            }
        }
        
        $res['data']['hid'] = $hid;
        
        return $res;
    }
    
    /**
     * 删除导航
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        try {
            
            if (! $this->input->isPOST()) {
                throw new Zeed_Exception('请勿非法操作');
            }
            
            $navigation_id = (int) $this->input->post('navigation_id', 0);
            
            $navigation = System_Model_Navigation::instance()->fetchByPK($navigation_id);
            if (empty($navigation)) {
                throw new Zeed_Exception('该导航不存在，删除失败');
            }
            
            $parent = System_Model_Navigation::instance()->fetchByFV('parent_id', $navigation_id);
            if (! empty($parent)) {
                throw new Zeed_Exception('其下已有二级导航，不可删除当前导航');
            }
            
            if (! System_Model_Navigation::instance()->deleteByPK($navigation_id)) {
                throw new Zeed_Exception('删除失败');
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setMsg($e->getMessage());
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
    
        $navigation_id = (int) $this->input->post('id');
    
        /* 获取文章内容 */
        if (! $navigation = System_Model_Navigation::instance()->fetchByPK($navigation_id)) {
            $this->setStatus(1);
            $this->setError('查无此导航');
            return self::RS_SUCCESS;
        }
    
        /* 执行审核 */
        if ($navigation[0]['status'] == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $this->changeStatus($navigation_id, $status);
    
        return self::RS_SUCCESS;
    }
    
    /**
     * 更新导航状态
     */
    private function changeStatus($navigation_id, $status = 0, $status_name = 'status')
    {
        try {
            $set = array($status_name => $status);
    
            if (is_string($navigation_id)) {
                if (strpos($navigation_id, ',')) {
                    $navigation_id = explode(',', $navigation_id);
                } else {
                    $navigation_id = array((int) $navigation_id);
                }
                $navigation_id = implode(',', $navigation_id);
            }
    
            $where = "navigation_id in ({$navigation_id})";
            System_Model_Navigation::instance()->update($set, $where);
    
            $data['status'] = $status;
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

// End ^ LF ^ encoding
