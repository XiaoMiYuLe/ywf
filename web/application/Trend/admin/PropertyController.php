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

class PropertyController extends TrendAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 属性管理
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
        $status = (int) $this->input->get('status', '-1');
        
    	/* ajax 加载数据 */
    	if ($this->input->isAJAX()) {
    	    $offset = $page * $perpage;
    	    $page = $page + 1;
    	
    	    $where = null;
        	if ($status != -1) {
        	    $where['status'] = $status;
        	}
            if (! empty($key)) {
                $where[] = "label_name LIKE '%{$key}%' OR note LIKE '%{$key}%'";
            }
    	
        	$order = "sort_order ASC";
        	$ordername && $order = $ordername . " " . $orderby;
    	    
        	$properties = Trend_Model_Property::instance()->fetchByWhere($where, $order, $perpage, $offset);
        	$data['count'] = Trend_Model_Property::instance()->getCount($where);
        	
        	/* 获取属性值 */
        	if (! empty($properties)) {
        	    foreach ($properties as &$v) {
        	        $where_pv = "property_id = {$v['property_id']}";
        	        $order_pv = "sort_order ASC";
        	        $property_values = Trend_Model_Property_Value::instance()->fetchByWhere($where_pv, $order_pv);
        	        
        	        if (! empty($property_values)) {
        	            $pv = array();
        	            foreach ($property_values as $vv) {
        	                $pv[] = $vv['property_value'];
        	            }
        	            $v['property_value'] = implode(',', $pv);
        	        }
        	    }
        	}
        	
            $data['properties'] = $properties ? $properties : array();
    	}
    	
    	$data['ordername'] = $ordername;
    	$data['orderby'] = $orderby;
    	$data['page'] = $page;
    	$data['perpage'] = $perpage;
    	
    	$this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'property.index');
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
        $this->addResult(self::RS_SUCCESS, 'php', 'property.edit');
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
                if ($property_id = Trend_Model_Property::instance()->addForEntity($set['data'])) {
                    foreach ($set['data']['p_val'] as $k => $v) {
                        /* 处理规格图片 */
                        $property_image_filepath = '';
                        $property_image = $_FILES['p_property_image_' . $k];
                        if ($property_image['name']) {
                            $property_image_upload = @Support_Attachment::upload($property_image);
                            if ($property_image_upload['error'] == UPLOAD_ERR_OK) {
                                $property_image_filepath = $property_image_upload['filepath'];
                            }
                        }
                        
                        /* 组织规格值待入库数据 */
                        $set_params = array(
                                'property_id' => $property_id,
                                'property_value' => $set['data']['p_val'][$k],
                                'property_image' => $property_image_filepath,
                                'sort_order' => $set['data']['p_sort_order'][$k],
                                'is_default' => $set['data']['p_is_default'][0] == $k ? 1 : 0
                        );
                        
                        /* 执行入库 */
                        Trend_Model_Property_Value::instance()->addForEntity($set_params);
                    }
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加属性失败 : ' . $e->getMessage());
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
    
        $property_id = (int) $this->input->query('property_id');
        
        /* 获取属性信息 */
        $property = Trend_Model_Property::instance()->fetchByPk($property_id);
        if (null === $property || ! is_array($property)) {
            $this->setStatus(1);
            $this->setError('该属性不存在');
            return self::RS_SUCCESS;
        }
        
        /* 获取该属性对应的属性值信息 */
        $data['property_values'] = Trend_Model_Property_Value::instance()->fetchByFV('property_id', $property_id);
        
        $data['property'] = $property[0];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'property.edit');
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
                /* 更新属性主表 */
                Trend_Model_Property::instance()->updateForEntity($set['data'], $set['data']['property_id']);
                
                /* 更新属性值表 */
                foreach ($set['data']['p_val'] as $k => $v) {
                    /* 处理规格图片 */
                    $property_image_filepath = '';
                    $property_image = $_FILES['p_property_image_' . $k];
                    if ($property_image['name']) {
                        $property_image_upload = @Support_Attachment::upload($property_image);
                        if ($property_image_upload['error'] == UPLOAD_ERR_OK) {
                            $property_image_filepath = $property_image_upload['filepath'];
                        }
                    }
                    
                    /* 组织规格值待入库数据 */
                    $set_params = array(
                            'property_value' => $set['data']['p_val'][$k],
                            'sort_order' => $set['data']['p_sort_order'][$k],
                            'is_default' => $set['data']['p_is_default'][0] == $k ? 1 : 0
                    );
                    if ($property_image_filepath) {
                        $set_params['property_image'] = $property_image_filepath;
                    }
                    
                    /* 执行入库 */
                    Trend_Model_Property_Value::instance()->updateForEntity($set_params, $set['data']['p_property_value_id'][$k]);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑属性失败 : ' . $e->getMessage());
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
                'property_id' => (int) $this->input->post('property_id'),
                'label_name' => trim($this->input->post('label_name')),
                'note' => trim($this->input->post('note')),
                'sort_order' => (int) $this->input->post('sort_order'),
                'is_spec' => (int) $this->input->post('is_spec'),
                'status' => (int) $this->input->post('status'),
                'p_val' => $this->input->post('p_val'),
                'p_sort_order' => $this->input->post('p_sort_order'),
                'p_is_default' => $this->input->post('p_is_default'),
                'p_property_value_id' => $this->input->post('p_property_value_id'));
        
        /* 数据验证 */
        if (empty($res['data']['label_name'])) {
            $res['status'] = 1;
            $res['error'] = '属性名称不能为空';
            return $res;
        }
        if (! count($res['data']['p_val'])) {
            $res['status'] = 1;
            $res['error'] = '请至少填写一组属性值';
            return $res;
        }
    
        return $res;
    }
    
    /**
     * 删除 - 逻辑删除，将属性及对应属性值置为不启用状态
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $property_id = $this->input->post('id');
        
        try {
            /* 获取分组信息 */
            if (! Trend_Model_Property::instance()->fetchByPK($property_id)) {
                throw new Zeed_Exception('查无此属性');
            }
            
            /* 执行删除 */
            $set = array('status' => 0);
            $where = "property_id in ({$property_id})";
            Trend_Model_Property::instance()->update($set, $where); // 删除属性主表
            Trend_Model_Property_Value::instance()->update($set, $where); // 删除属性值
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除属性失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
    
    /**
     * 添加一个属性值记录
     */
    public function insertPropertyValue()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $property_id = (int) $this->input->post('property_id');
    
        try {
            /* 执行添加 */
            $set = array('property_id' => $property_id);
            $data['property_value_id'] = Trend_Model_Property_Value::instance()->addForEntity($set);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('添加属性值失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setError('添加成功');
        $this->setData('data', $data);
        return self::RS_SUCCESS;
    }
    
    /**
     * 删除一个属性值
     */
    public function deletePropertyValue()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $property_value_id = (int) $this->input->post('property_value_id');
    
        try {
            /* 执行删除 */
            Trend_Model_Property_Value::instance()->deleteByPK($property_value_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除属性值失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
    
    /**
     * 发布
     */
    public function publish()
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    
    	if (! $this->input->isPOST()) {
    		$this->setStatus(1);
    		$this->setError('请勿非法操作');
    		return self::RS_SUCCESS;
    	}
    
    	$property_id = (int) $this->input->post('id');
    
    	/* 获取内容 */
    	if (!$vaule = Trend_Model_Property::instance()->fetchByPK($property_id)) {
    		$this->setStatus(1);
    		$this->setError('查无此数据');
    		return self::RS_SUCCESS;
    	}
    
    	/* 执行发布 */
    	if ($vaule[0]['status'] == 1) {
    		$status = 0;
    	} else {
    		$status = 1;
    	}
    	$set = array('status' => $status);
    	Trend_Model_Property::instance()->updateForEntity($set, $property_id);
    	
    	return self::RS_SUCCESS;
    }
    
    /**
     * 开启规格
     */
    public function spec()
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    
    	if (! $this->input->isPOST()) {
    		$this->setStatus(1);
    		$this->setError('请勿非法操作');
    		return self::RS_SUCCESS;
    	}
    
    	$property_id = (int) $this->input->post('id');
    
    	/* 获取内容 */
    	if (! $vaule = Trend_Model_Property::instance()->fetchByPK($property_id)) {
    		$this->setStatus(1);
    		$this->setError('查无此数据');
    		return self::RS_SUCCESS;
    	}
    
    	/* 执行发布 */
    	if ($vaule[0]['is_spec'] == 1) {
    		$status = 0;
    	} else {
    		$status = 1;
    	}
    	$set = array('is_spec' => $status);
    	Trend_Model_Property::instance()->updateForEntity($set, $property_id);
    	 
    	return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8