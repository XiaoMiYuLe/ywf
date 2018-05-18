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

class PropertyGroupController extends TrendAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 属性分组管理
     */
    public function index()
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    	
    	/* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        
    	/* ajax 加载数据 */
    	if ($this->input->isAJAX()) {
    	    $offset = $page * $perpage;
    	    $page = $page + 1;
    	
    	    $where = null;
        	/* 分类名 like 搜索分类 */
            if (! empty($key) && is_string($key)) {
                $key = mysql_real_escape_string($key);
                $where = 'property_group_name LIKE \'%' . $key . '%\'';
            }
    	
        	$order = "sort_order ASC";
        	$ordername && $order = $ordername . " " . $orderby;
    	    
        	$groups = Trend_Model_Property_Group::instance()->fetchByWhere($where, $order, $perpage, $offset);
        	$data['count'] = Trend_Model_Property_Group::instance()->getCount($where);
        	
            $data['groups'] = $groups ? $groups : array();
    	}
    	
    	$data['ordername'] = $ordername;
    	$data['orderby'] = $orderby;
    	$data['page'] = $page;
    	$data['perpage'] = $perpage;
    	
    	$this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'propertygroup.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $data = array();
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'propertygroup.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                if ($property_group_id = Trend_Model_Property_Group::instance()->addForEntity($set['data']) && $set['data']['property_ids']) {
                    /* 写入分组与属性的关联关系 */
                    $property_id_arr = explode(',', $set['data']['property_ids']);
                    foreach ($property_id_arr as $k => $v) {
                        $set_to = array(
                        	   'property_id' => $v,
                        	   'property_group_id' => $property_group_id,
                        	   'sort_order' => $k + 1
                        );
                        Trend_Model_Property_To_Group::instance()->addForEntity($set_to);
                    }
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加分组失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 编辑
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
    
        $property_group_id = (int) $this->input->query('property_group_id');
        
        $group = Trend_Model_Property_Group::instance()->fetchByPk($property_group_id);
        if (null === $group || ! is_array($group)) {
            $this->setStatus(1);
            $this->setError('该分组不存在');
            return self::RS_SUCCESS;
        }
        
        /* 获取分组与属性的关联关系 */
        $data['properties'] = Trend_Model_Property_To_Group::instance()->fetchByFV('property_group_id', $property_group_id);
        
        $data['group'] = $group[0];
        $data['property_group_id'] = $property_group_id;
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'propertygroup.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                Trend_Model_Property_Group::instance()->updateForEntity($set['data'], $set['data']['property_group_id']);
                
                /* 删除旧的关联关系 */
                Trend_Model_Property_To_Group::instance()->deleteByFV('property_group_id', $set['data']['property_group_id']);
                
                /* 写入新的关联关系 */
                if ($set['data']['property_ids']) {
                    $property_id_arr = explode(',', $set['data']['property_ids']);
                    foreach ($property_id_arr as $k => $v) {
                        $set_to = array(
                                'property_id' => $v,
                                'property_group_id' => $set['data']['property_group_id'],
                                'sort_order' => $k + 1
                        );
                        Trend_Model_Property_To_Group::instance()->addForEntity($set_to);
                    }
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑分组失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $res['data'] = array(
                'property_group_id' => (int) $this->input->post('property_group_id'),
                'property_group_name' => trim($this->input->post('property_group_name')),
                'sort_order' => (int) $this->input->post('sort_order'),
                'property_ids' => trim($this->input->post('property_ids')));
        
        /* 数据验证 */
        if (empty($res['data']['property_group_name'])) {
            $res['status'] = 1;
            $res['error'] = '分组名称不能为空';
            return $res;
        }
    
        return $res;
    }
    
    /**
     * 删除 - 仅删除分组及分组与属性的关联关系，不删除分组下的具体属性
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $group_id = $this->input->post('id');
        if (is_string($group_id)) {
            if (strpos($group_id, ',')) {
                $group_id = explode(',', $group_id);
            } else {
                $group_id = array((int) $group_id);
            }
        }
    
        try {
            /* 获取分组信息 */
            if (! Trend_Model_Property_Group::instance()->fetchByPK($group_id)) {
                throw new Zeed_Exception('查无此分组');
            }
            
            /* 执行删除 */
            Trend_Model_Property_To_Group::instance()->deleteByFV('property_group_id', $group_id); // 删除分组与属性的关联关系
            Trend_Model_Property_Group::instance()->deleteByFV('property_group_id', $group_id); // 删除分组
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除分组失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8