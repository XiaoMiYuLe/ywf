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

class MenuGroupController extends AdminAbstract
{
    /**
     * 前台菜单分组列表
     */
    public function index()
    {
        $data['groups'] = Admin_Model_Frontend_Menu_Group::instance()->fetchAll()->toArray();
        
        $this->setData('data', $data);
        
        $this->addResult(self::RS_SUCCESS, 'php', 'menugroup.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加菜单分组
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
    
        $this->addResult(self::RS_SUCCESS, 'php', 'menugroup.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加菜单分组 - 保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                if (! Admin_Model_Frontend_Menu_Group::instance()->addForEntity($set['data'])) {
                    throw new Zeed_Exception('Add group failed, please try again.');
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
     * 保存菜单分组－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $res['data'] = array('group_name' => $this->input->post('group_name'));
    
        /* 数据验证 */
        if (empty($res['data']['group_name'])) {
            $res['status'] = 1;
            $res['error'] = '分组名称不能为空';
            return $res;
        }
    
        return $res;
    }
    
    /**
     * 删除分组
     * 删除分组，并删除分组下的单页（包括清除数据库和删除文件）
     * 删除前，弹框提示
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $id = (int) $this->input->post('id');
    
        try {
            $menugroup = Admin_Model_Frontend_Menu_Group::instance()->fetchByPK($id);
            if ($menugroup) {
                Admin_Model_Frontend_Menu_Group::instance()->deleteByPK($id);
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('Drop group failed : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8