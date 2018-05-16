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

class CategoryController extends ArticleAdminAbstract
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
    	    if (! empty($key)) {
                $where[] = "title LIKE '%{$key}%'";
            }
    	
        	$order = 'hid ASC';
        	if ($ordername) {
        	    $order = $ordername . " " . $orderby;
        	}
    	    
        	$categories = Article_Model_Category::instance()->getAllCategoriesForListing($where, $order, $perpage, $offset);
        	$data['count'] = Article_Model_Category::instance()->getCount($where);
        	
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
     * 读取树形
     */
    public function getZTree()
    {
        $res = array();
        $parent_id = $this->input->post('id', 0);
        $status = $this->input->query('status', 0);
        $categories = Article_Model_Category::instance()->fetchByParentid($parent_id);
    
        foreach ($categories as $k => $v) {
            /* 统计分类下的文章数目 */
            $where_count['category'] = $v['category_id'];
            if ($status == -1) {
                $where_count['status'] = -1;
            } else {
                $where_count[] = "status > -1";
            }
            
            $count_article = Article_Model_Content::instance()->getCount($where_count);
            
            $res[$k]['id'] = $v['category_id'];
            $res[$k]['name'] = "[ID:{$v['category_id']}]" . $v['title'] . "({$count_article})";
            $res[$k]['isParent'] = false;
            if ($v['category_id'] > 0 && count(Article_Model_Category::instance()->fetchByParentid($v['category_id'])) > 0) {
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
            $listing = Article_Model_Category::instance()->getByRegTitle($searchcontent);
        } else {
            if (is_numeric($searchcontent) && (int) $searchcontent > 0) {
                // 补齐二维数组，以便统一
                $result = Article_Model_Category::instance()->getById($searchcontent);
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
        $data['categories'] = Article_Model_Category::instance()->getAllCategoriesForSelect();
        
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
                if ($category_id = Article_Model_Category::instance()->addForEntity($set['data'])) {
                    /* 更新 hid */
                    $setUpdate = array('hid' => $set['data']['hid'] . ':' . str_pad($category_id, 4, 0, STR_PAD_LEFT));
                    Article_Model_Category::instance()->updateForEntity($setUpdate, $category_id);
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
        $category = Article_Model_Category::instance()->fetchByPk($category_id);
        if (null === $category || ! is_array($category)) {
            $this->setStatus(1);
            $this->setError('该分类不存在');
            return self::RS_SUCCESS;
        }
        $data['category'] = $category[0];
        
        /* 获取所有项目信息 */
        $data['categories'] = Article_Model_Category::instance()->getAllCategoriesForSelect();
        
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
                Article_Model_Category::instance()->updateForEntity($set['data'], $set['data']['category_id']);
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
                'category_id' => (int)$this->input->post('category_id', ''),
                'parent_id' => $this->input->post('parent_id'),
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'sort_order' => $this->input->post('sort_order'));
    
        /* 数据验证 */
        if (empty($res['data']['title'])) {
            $res['status'] = 1;
            $res['error'] = '分类名称不能为空';
            return $res;
        }
        
        if ($res['data']['parent_id'] == $res['data']['category_id'] && $res['data']['parent_id'] !=0) {
            $res['status'] = 1;
            $res['error'] = '不可移至分类自身之下';
            return $res;
        }
        
        /* 构造 hid，同时判断，编辑状态下，不能转移至自身的子孙级分类下 */
        $hid = 0;
        if ($res['data']['parent_id'] > 0) {
            $parentCategory = Article_Model_Category::instance()->fetchByPK($res['data']['parent_id']);
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
            /* 若所选分类下包含有文章，则不可删除 */
            $articles = Article_Model_Content::instance()->fetchByFV('category', $category_id_arr);
            if (! empty($articles)) {
                throw new Zeed_Exception('您所选的分类中包含有文章，请先清理文章后再删除分类');
            }
            
            /* 若所选分类包含子孙级分类，则不可删除 */
            $categories_childdren = Article_Model_Category::instance()->fetchByFV('parent_id', $category_id_arr);
            if (! empty($categories_childdren)) {
                throw new Zeed_Exception('您所选的分类中包含子级分类，不可删除');
            }
            
            /* 执行删除 */
            Article_Model_Category::instance()->deleteByFV('category_id', $category_id_arr);
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