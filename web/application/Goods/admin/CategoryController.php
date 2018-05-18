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

class CategoryController extends GoodsAdminAbstract
{
    public $perpage = 15;
    /**
     * 分类管理
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
            if ($key) {
                $where = 'category_name LIKE \'%' . $key . '%\'';
            }
        	$order = " hid ASC";
       	    $ordername && $order = $ordername . " " . $orderby;
    	    
        	$data['category'] = Goods_Model_Category::instance()->getAllCategoriesForListing($where, $order, $perpage, $offset);
        	$data['count'] = Goods_Model_Category::instance()->getCount($where);
        	
            $data['category'] = $data['category'] ? $data['category'] : array();
    	}
    	
    	$data['ordername'] = $ordername;
    	$data['orderby'] = $orderby;
    	$data['page'] = $page;
    	$data['perpage'] = $perpage;
    	
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'category.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    

    /**
     * 读取树形
     */
    public function getZTree()
    {
        $res = array();
        $parent_id = $this->input->post('id', 0);
        $categories = Goods_Model_Category::instance()->fetchByParentid($parent_id);
    
        foreach ($categories as $k => $v) {
            /* 统计分类下的商品数目 */
            $count_goods = Goods_Model_Content_Category::instance()->getCount("category_id = {$v['category_id']}");
            
            $res[$k]['id'] = $v['category_id'];
            $res[$k]['name'] = "[ID:{$v['category_id']}]" . $v['title'] . "({$count_goods})";
            $res[$k]['isParent'] = false;
            if ($v['category_id'] > 0 && count(Goods_Model_Category::instance()->fetchByParentid($v['category_id'])) > 0) {
                $res[$k]['isParent'] = true;
            }
        }
        echo @json_encode($res);
        exit;
    }
    
    /**
     * 分类查询
     */
    public function getCategory()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $searchtype = $this->input->get('searchtype');
        $searchcontent = $this->input->get('searchcontent');
    
        $listing = null;
        if ($searchtype == 1) {
            $listing = Goods_Model_Category::instance()->getByRegTitle($searchcontent);
        } else {
            if (is_numeric($searchcontent) && (int) $searchcontent > 0) {
                // 补齐二维数组，以便统一
                $result = Goods_Model_Category::instance()->getById($searchcontent);
                $listing = $result ? array(0 => $result) : array();
            }
        }
    
        $res['data'] = $listing;
        $this->setData($res);
        return self::RS_SUCCESS;
    }
    
    /**
     * 添加分类
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        /* 获取所有分类信息 */
        $data['categories'] = Goods_Model_Category::instance()->getAllCategoriesForSelect();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'category.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加分类 - 保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                if ($category_id = Goods_Model_Category::instance()->addForEntity($set['data'])) {
                    /* 更新 hid */
                    $setUpdate = array('hid' => $set['data']['hid'] . ':' . str_pad($category_id, 4, 0, STR_PAD_LEFT));
                    Goods_Model_Category::instance()->updateForEntity($setUpdate, $category_id);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加分类失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 编辑分类
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $category_id = $this->input->query('category_id');
        $category = Goods_Model_Category::instance()->fetchByPk($category_id);
        if (null === $category || ! is_array($category)) {
            $this->setStatus(1);
            $this->setError('该分类不存在');
            return self::RS_SUCCESS;
        }
        $data['category'] = $category[0];
        
        /* 获取所有项目信息 */
        $data['categories'] = Goods_Model_Category::instance()->getAllCategoriesForSelect();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'category.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改分类 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                Goods_Model_Category::instance()->updateForEntity($set['data'], $set['data']['category_id']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑分类失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存分类－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $res['data'] = array(
                'category_id' => $this->input->post('category_id', ''),
                'parent_id' => (int) $this->input->post('parent_id'),
                'category_name' => trim($this->input->post('category_name')),
                'description' => trim($this->input->post('description')),
                'sort_order' => (int) $this->input->post('sort_order'),
        		'mtime' => date(DATETIME_FORMAT)
        );
    
        /* 数据验证 */
        if (empty($res['data']['category_name'])) {
            $res['status'] = 1;
            $res['error'] = '分类名称不能为空';
            return $res;
        }
        
        if ($res['data']['parent_id'] === $res['data']['category_id']) {
            $res['status'] = 1;
            $res['error'] = '不可移至分类自身之下';
            return $res;
        }
        
        /* 构造 hid，同时判断，编辑状态下，不能转移至自身的子孙级分类下 */
        $hid = 0;
        if ($res['data']['parent_id'] > 0) {
            $parentCategory = Goods_Model_Category::instance()->fetchByPK($res['data']['parent_id']);
            if (is_array($parentCategory) && count($parentCategory) > 0) {
                $hid = $parentCategory[0]['hid'];
            }
        } else {
        	$res['data']['ctime'] = $res['data']['mtime'];
        }
        if ($res['data']['category_id'] > 0 && $res['data']['category_id'] != $res['data']['parent_id']) {
            if (strpos($hid, ":" . str_pad($res['data']['category_id'], 4, 0, STR_PAD_LEFT)) !== false) {
                $res['status'] = 1;
                $res['error'] = '不可移至自身的下级分类中';
                return $res;
            }
            $hid .= ':' . str_pad($res['data']['category_id'], 4, 0, STR_PAD_LEFT);
        }
        $res['data']['hid'] = $hid;
        
        return $res;
    }
    
    /**
     * 删除分类
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $category_id = (int) $this->input->post('category_id');
        
        if (! $category_id) {
            $res['status'] = 9;
            $res['error'] = '当前节点为根节点，不可删除';
        
            $this->setData($res);
            return self::RS_SUCCESS;
        }
        
        /* 删除分类 */
        $notice = Goods_Model_Category::instance()->deleteCategory($category_id);
        if ($notice == '1001') {
            $res['status'] = 1;
            $res['error'] = '当前ID不存在，删除失败，请联系管理员';
        } else if ($notice == '1002') {
            $res['status'] = 2;
            $res['error'] = '其下已有二级分类，不可删除当前分类';
        }
        
        $this->setData($res);
        return self::RS_SUCCESS;
    }
    
    /**
     * 拖拽时更新
     */
    public function updateForMove()
    {
        $ifmovetype = false;
        $res = array('status' => 0, 'error' => null, 'data' => null);
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isAJAX()) {
            $moveid = $this->input->post('moveid');
            $targetid = $this->input->post('targetid');
            $movetype = $this->input->post('movetype');
    
            if ($moveid == $targetid) {
                throw new Zeed_Exception('异常错误，不可移至自身节点');
            }
    
            $moveCategory = current(Goods_Model_Category::instance()->getById($moveid));
            $targetCategory = current(Goods_Model_Category::instance()->getById($targetid));
            $targetChildCategory = Goods_Model_Category::instance()->fetchByParentid($targetid);
            
            // 根据拖到的位置做不同处理
            switch ($movetype) {
                case "inner":
                    $ifmovetype = true;
                    $parent_id = $targetid;
                    $movehid = $targetCategory['hid'] . ":{$moveid}";
                    break;
                case "prev":
                    $ifmovetype = true;
                    $parent_id = $targetCategory['parent_id'];
                    $movehidstr = $targetCategory['hid'] . ":{$moveid}";
                    $movehid = str_replace(":{$targetid}:", ':', $movehidstr);
                    $targetSortOrder = $targetCategory['sort_order'] - 1;
                    $setOrder['sort_order'] = $targetCategory['sort_order'];
                    break;
                case "next":
                    $ifmovetype = true;
                    $parent_id = $targetCategory['parent_id'];
                    $movehidstr = $targetCategory['hid'] . ":{$moveid}";
                    $movehid = str_replace(":{$targetid}:", ':', $movehidstr);
                    $targetSortOrder = $targetCategory['sort_order'];
                    $setOrder['sort_order'] = $targetCategory['sort_order'] + 1;
                    break;
                default:
                    $ifmovetype = false;
                    break;
            }
        }
    
        if ($ifmovetype == false) {
            $res['status'] = 1;
            $res['error'] = '出错了，不要乱拖哦';
        } else {
            $set = array(
                    'parent_id' => $parent_id,
                    'hid' => $movehid,
                    'mtime' => date(DATETIME_FORMAT, time()));
    
            // 更新节点内容
            if ($movetype == 'inner') {
                if ($res['status'] == 0 && ! Goods_Model_Category::instance()->updateCategory($set, $moveid)) {
                    $res['status'] = 1;
                    $res['error'] = '更新节点内容失败';
                } else {
                    // 更新自身序号，若目标节点下无子节点，则直接将自身序号置为1，否则自增
                    if (! $targetChildCategory) {
                        Goods_Model_Category::instance()->updateCategory(array('sort_order' => 1), $moveid);
                    } else {
                        Goods_Model_Category::instance()->updateOrderById($moveid, $parent_id);
                    }
    
                    // 整理序号，更新同级节点（拖动节点初始位置）中位于拖动节点后的序号，全部减 1
                    Goods_Model_Category::instance()->updateOrderByPidAndOrder($moveCategory['parent_id'], $moveCategory['sort_order'], -1);
                }
            } else {
                if ($res['status'] == 0 && ! Goods_Model_Category::instance()->updateCategory($set, $moveid)) {
                    $res['status'] = 1;
                    $res['error'] = '更新节点内容失败';
                } else {
                    // 更新同级节点（拖动节点当前位置）中位于拖动节点后的序号，全部加 1
                    Goods_Model_Category::instance()->updateOrderByPidAndOrder($parent_id, $targetSortOrder, 1);
                    
                    // 更新同级节点（拖动节点初始位置）中位于拖动节点后的序号，全部减 1
                    Goods_Model_Category::instance()->updateOrderByPidAndOrder($moveCategory['parent_id'], $moveCategory['sort_order'], -1);
    
                    // 更新拖动节点自身序号
                    Goods_Model_Category::instance()->updateCategory($setOrder, $moveid);
                }
            }
    
            // 若拖动节点下有子节点，则一并更新其 hid
            if ($res['status'] == 0) {
                Goods_Model_Category::instance()->updateChildOrderByHid($moveCategory['hid'], $movehid);
            }
        }
    
        $this->setData($res);
        return self::RS_SUCCESS;
    }
    
    /**
     * 绑定属性 - 即可绑定单个属性，也可绑定属性分组
     */
    public function bindProperty()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->bindPropertySave();
            return self::RS_SUCCESS;
        }
        
        $category_id = (int) $this->input->get('category_id');
        
        /* 查询分类信息 */
        $category = Goods_Model_Category::instance()->fetchByPK($category_id);
        if (null === $category || ! is_array($category)) {
            $this->setStatus(1);
            $this->setError('该分类不存在');
            return self::RS_SUCCESS;
        }
        $data['category'] = $category[0];
        
        /* 查询已绑定的关系 */
        $properties = array();
        $property_category = Goods_Model_Property_Category::instance()->fetchByFV('category_id', $category_id);
        if (! empty($property_category)) {
            foreach ($property_category as $v) {
                if ($v['property_id']) {
                    $property_id = $v['property_id'];
                    $id_str = 'li_property_' . $property_id;
                    $property = Trend_Model_Property::instance()->fetchByPK($property_id);
                    if (! empty($property)) {
                        $property_name = $property[0]['label_name'];
                    }
                } else {
                    $property_id = $v['property_group_id'];
                    $id_str = 'li_property_group_' . $property_id;
                    $group = Trend_Model_Property_Group::instance()->fetchByPK($property_id);
                    if (! empty($group)) {
                        $property_name = $group[0]['property_group_name'];
                    }
                }
                
                $properties[] = array(
                	   'data_type' => $v['property_id'] ? 'p' : 'g',
                	   'id_str' => $id_str,
                	   'property_id' => $property_id,
                	   'property_name' => $property_name
                );
            }
        }
        
        $data['category_id'] = $category_id;
        $data['properties'] = $properties;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'category.bindproperty');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 绑定属性 - 保存
     */
    public function bindPropertySave()
    {
        /* 接收参数 */
        $category_id = (int) $this->input->post('category_id');
        $property_ids = trim($this->input->post('property_ids'));
        
        /* 若接收到的属性为空，则不做任何处理，直接返回 */
        if (! $property_ids) {
            return true;
        }
        
        try {
            /* 删除旧的绑定关系 */
            Goods_Model_Property_Category::instance()->deleteByFV('category_id', $category_id);
            
            /* 添加新的绑定关系 - 区别属性与分组 */
            $property_ids_arr = explode(',', $property_ids);
            foreach ($property_ids_arr as $k => $v) {
                if (substr($v, 0, 1) == 'p') {
                    $set_to = array(
                            'property_id' => substr($v, 1),
                            'property_group_id' => 0,
                            'category_id' => $category_id,
                            'sort_order' => $k + 1
                    );
                } else {
                    $set_to = array(
                            'property_id' => 0,
                            'property_group_id' => substr($v, 1),
                            'category_id' => $category_id,
                            'sort_order' => $k + 1
                    );
                }
                Goods_Model_Property_Category::instance()->addForEntity($set_to);
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('绑定失败 : ' . $e->getMessage());
            return false;
        }
        
        return true;
    }
    
    /**
     * 绑定品牌
     */
    public function bindBrand()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->bindBrandSave();
            return self::RS_SUCCESS;
        }
        
        $category_id = (int) $this->input->get('category_id');
        
        /* 查询分类信息 */
        $category = Goods_Model_Category::instance()->fetchByPK($category_id);
        if (null === $category || ! is_array($category)) {
            $this->setStatus(1);
            $this->setError('该分类不存在');
            return self::RS_SUCCESS;
        }
        $data['category'] = $category[0];
        
        /* 查询已绑定的关系 */
        $where = "category_id = {$category_id}";
        $order = "sort_order ASC";
        $brands_binded = Goods_Model_Brand_Category::instance()->fetchByWhere($where, $order);
        if (! empty($brands_binded)) {
            foreach ($brands_binded as &$v) {
                $v['brand_name'] = '';
                $brand = Goods_Model_Brand::instance()->fetchByPK($v['brand_id']);
                if (! empty($brand)) {
                    $v['brand_name'] = $brand[0]['brand_name'];
                }
            }
        }
        
        /* 获取所有品牌信息 */
        $data['brands'] = Goods_Model_Brand::instance()->fetchAll()->toArray();
        
        $data['category_id'] = $category_id;
        $data['brands_binded'] = $brands_binded;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'category.bindbrand');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 绑定属性 - 保存
     */
    public function bindBrandSave()
    {
        /* 接收参数 */
        $category_id = (int) $this->input->post('category_id');
        $brand_ids = trim($this->input->post('brand_ids'));
        
        /* 若接收到的品牌为空，则不做任何处理，直接返回 */
        if (! $brand_ids) {
            return true;
        }
        
        try {
            /* 删除旧的绑定关系 */
            Goods_Model_Brand_Category::instance()->deleteByFV('category_id', $category_id);
            
            /* 添加新的绑定关系 - 区别属性与分组 */
            $brand_ids_arr = explode(',', $brand_ids);
            foreach ($brand_ids_arr as $k => $v) {
                $set_to = array(
                        'brand_id' => $v,
                        'category_id' => $category_id,
                        'sort_order' => $k + 1
                );
                Goods_Model_Brand_Category::instance()->addForEntity($set_to);
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('绑定失败 : ' . $e->getMessage());
            return false;
        }
        
        return true;
    }
    
    /**
     * 分类
     * @return string
     */
    public function getCategoryParent()
    {
    	/* ajax 加载数据 */
    	if ($this->input->isAJAX()) {
    	    $this->addResult(self::RS_SUCCESS, 'json');
    	    /* 接收参数 */
    	    $page    = (int) $this->input->get('pageIndex', 0);
    	    $perpage = $this->input->get('pageSize', $this->perpage);
    	    $offset  = $page * $perpage;
    	    $page    = $page + 1;
    	    $parent_id = (int) $this->input->get('parent_id', 0);
            if ($parent_id) {
                $where = 'parent_id = ' . $parent_id;
            }
        	$order = " hid ASC";
        	$category = Goods_Model_Category::instance()->getAllCategoriesForListing($where, $order, $perpage, $offset);
        	if (!empty($category)) {
        	    $virtual = Goods_Model_Category_Virtual::instance()->fetchByWhere(array('parent_id'=>$parent_id),'sort_order asc');
        	    $virtual = Util_Custom::setArrayIndex(1, $virtual,'category_id');
        	    foreach ($category as $key=>$item) {
        	        $alias   = '';
        	        $is_sign = 0;
        	        $data_type = 'g';
        	        $url  = '';
        	        if (is_array($virtual[$item['category_id']])) {
        	            $alias = $virtual[$item['category_id']]['alias'];
        	            $url   = $virtual[$item['category_id']]['url'];
        	            $is_sign = 1;
        	            $data_type = 'p';
        	        }
        	        $category[$key]['is_sign']   = $is_sign;
        	        $category[$key]['alias']     = $alias;
        	        $category[$key]['url']       = $url;
        	        $category[$key]['data_type'] = $data_type;
        	    }
        	}
        	$data['count']    = Goods_Model_Category::instance()->getCount($where);
            $data['category'] = $category ? $category : array();
            $this->setData('data', $data);
            $this->addResult(self::RS_SUCCESS, 'php', 'category.index');
            return parent::multipleResult(self::RS_SUCCESS);
    	}
    }
}

// End ^ Native EOL ^ UTF-8