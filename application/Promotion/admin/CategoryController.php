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

class CategoryController extends PromotionAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 活动分类管理
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
    	    if (! empty($key)) {
                $where[] = "title LIKE '%{$key}%'";
            }
    	
        	$order = 'hid ASC';
        	if ($ordername) {
        	    $order = $ordername . " " . $orderby;
        	}
    	    
        	$categories = Promotion_Model_Category::instance()->getAllCategoriesForListing($where, $order, $perpage, $offset);
        	$data['count'] = Promotion_Model_Category::instance()->getCount($where);
        	
        	/* 获取模板信息 */
        	if (! empty($categories)) {
        	    foreach ($categories as &$v) {
        	        $template = Promotion_Model_Template::instance()->fetchByPK($v['template_id']);
        	        if (! empty($template)) {
        	            $v['template_title'] = $template[0]['title'];
        	        }
        	    }
        	}
        	
            $data['categories'] = $categories ? $categories : array();
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
     * 添加分类
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        /* 获取所有模板信息 */
        $data['templates'] = Promotion_Model_Template::instance()->fetchAll()->toArray();
        
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
                if ($category_id = Promotion_Model_Category::instance()->addForEntity($set['data'])) {
                    /* 更新 hid */
                    $setUpdate = array('hid' => $set['data']['hid'] . ':' . str_pad($category_id, 4, 0, STR_PAD_LEFT));
                    Promotion_Model_Category::instance()->updateForEntity($setUpdate, $category_id);
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
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $category_id = $this->input->query('category_id');
        $category = Promotion_Model_Category::instance()->fetchByPk($category_id);
        if (null === $category || ! is_array($category)) {
            $this->setStatus(1);
            $this->setError('该分类不存在');
            return self::RS_SUCCESS;
        }
        $data['category'] = $category[0];
        
        /* 获取所有模板信息 */
        $data['templates'] = Promotion_Model_Template::instance()->fetchAll()->toArray();
        
        $data['category_id'] = $category_id;
        
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
                Promotion_Model_Category::instance()->updateForEntity($set['data'], $set['data']['category_id']);
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
                'parent_id' => $this->input->post('parent_id', 0),
                'title' => $this->input->post('title'),
                'template_id' => $this->input->post('template_id'),
                'rules_filename' => $this->input->post('rules_filename'));
    
        /* 数据验证 */
        if (empty($res['data']['title'])) {
            $res['status'] = 1;
            $res['error'] = '分类名称不能为空';
            return $res;
        }
        
        /* 构造 hid，同时判断，编辑状态下，不能转移至自身的子孙级分类下 */
        $hid = 0;
        if ($res['data']['parent_id'] > 0) {
            $parentCategory = Promotion_Model_Category::instance()->fetchByPK($res['data']['parent_id']);
            if (is_array($parentCategory) && count($parentCategory) > 0) {
                $hid = $parentCategory[0]['hid'];
            }
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
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
        
        /* 接收参数并做基础处理 */
        $category_id = $this->input->post('category_id');
        
        $category_id_arr = explode(',', $category_id);
        
        try {
            /* 若所选分类下包含有活动，则不可删除 */
            $articles = Promotion_Model_Content::instance()->fetchByFV('category_id', $category_id_arr);
            if (! empty($articles)) {
                throw new Zeed_Exception('您所选的分类中包含有活动，请先清理活动后再删除分类');
            }
            
            /* 若所选分类包含子孙级分类，则不可删除 */
            $categories_childdren = Promotion_Model_Category::instance()->fetchByFV('parent_id', $category_id_arr);
            if (! empty($categories_childdren)) {
                throw new Zeed_Exception('您所选的分类中包含子级分类，不可删除');
            }
            
            /* 执行删除 */
            Promotion_Model_Category::instance()->deleteByFV('category_id', $category_id_arr);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除分类失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setError('删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8