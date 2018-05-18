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

class IndexController extends ArticleAdminAbstract
{
    public $perpage = 15;

    public function test(){
        $a = Api_Cas_SendCode::run();
        return $a;
    }
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        $parent_id = (int) $this->input->get('parent_id');
        $category = (int) $this->input->get('category');
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
        	$offset = $page * $perpage;
        	$page = $page + 1;
        	
        	$where[] = "status != -1";
        	if ($parent_id) {
        		$where['parent_id'] = $parent_id;
        	}
        	if ($category) {
        		$where['category'] = $category;
        	}
        	if (! empty($key)) {
        		$where[] = "title LIKE '%{$key}%'";
        	}
        	
        	$order = array(
                	    'recommended desc',
                	    'pinned desc',
                	    'content_id desc',
                	);
        	if ($ordername) {
        		$order = $ordername . " " . $orderby;
        	}
        
        	$contents = Article_Model_Content::instance()->fetchByWhere($where, $order, $perpage, $offset);
        	$data['count'] = Article_Model_Content::instance()->getCount($where);
        	
        	
        	/* 获取分类名称 */
        	if (! empty($contents)) {
        	    foreach ($contents as $k => &$v) {
        	        $category_arr = explode(',', $v['category']);
        	        $cols_category = array('title');
        	        $category = Article_Model_Category::instance()->fetchByFV('category_id', $category_arr, $cols_category);
        	        
        	        $category_name = array();
        	        if (! empty($category)) {
        	            foreach ($category as $kk => $vv) {
        	                $category_name[] = $vv['title'];
        	            }
        	        }
        	        $v['category_name'] = $category_name ? implode(',', $category_name) : '';
        	    }
        	}
        
        	$data['contents'] = $contents ? $contents : array();
        }
        
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        $data['parent_id'] = $parent_id;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加文章
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        /* 获取所有分类 - 下拉选项型 */
        $order_category = array('hid ASC', 'sort_order ASC');
        $data['categories'] = Article_Model_Category::instance()->getAllCategoriesForSelect($order_category);
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加文章 - 保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                /* 处理图片上传 */
                $files = $set['data']['image'];
                if ($files['name']) {
                    $files_upload = Support_Attachment::upload($files);
                    if ($files['error'] == UPLOAD_ERR_OK) {
                        $set['data']['image'] = $files_upload['filepath'];
                    } else {
                        throw new Zeed_Exception('好像发生一些意外错误呢');
                    }
                } else {
                    unset($set['data']['image']);
                }
                
                /* 写入文章主表 */
                if (! $content_id = Article_Model_Content::instance()->addForEntity($set['data'])) {
                    throw new Zeed_Exception('添加文章失败');
                }
                $set['data']['content_id'] = $content_id;
                
                /* 写入文章详情表 */
                Article_Model_Content_Detail::instance()->addForEntity($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加文章失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 修改文章
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        /* 接收参数 */
        $content_id = (int) $this->input->query('content_id');
        
        /* 获取文章主体信息 */
        if (! $content = Article_Model_Content::instance()->fetchByPK($content_id)) {
            $this->setStatus(1);
            $this->setError('查无此文章');
            return self::RS_SUCCESS;
        }
        $content = $content[0];
        
        /* 获取文章详情 */
        $content_detail = Article_Model_Content_Detail::instance()->fetchByFV('content_id', $content_id);
        
        /* 处理文章图片信息 */
        if ($content['image']) {
            $content['image'] = Support_Image_Url::getImageUrl($content['image']);
        }
        
        /* 合并文章信息 */
        if (! empty($content_detail)) {
            $content_detail_merge = array(
                    'body' => $content_detail[0]['body'],
                    'meta_title' => $content_detail[0]['meta_title'],
                    'meta_keywords' => $content_detail[0]['meta_keywords'],
                    'meta_description' => $content_detail[0]['meta_description']
            );
            $content = array_merge($content, $content_detail_merge);
        }
        
        /* 获取所有分类 - 下拉选项型 */
        $order_category = array('hid ASC', 'sort_order ASC');
        $data['categories'] = Article_Model_Category::instance()->getAllCategoriesForSelect($order_category);
        
        $data['content_id'] = $content_id;
        $data['content'] = $content;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改文章 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                /* 处理图片上传 */
                $files = $set['data']['image'];
                if ($files['name']) {
                    $files_upload = Support_Attachment::upload($files);
                    if ($files['error'] == UPLOAD_ERR_OK) {
                        $set['data']['image'] = $files_upload['filepath'];
                    } else {
                        throw new Zeed_Exception('好像发生一些意外错误呢');
                    }
                } else {
                    unset($set['data']['image']);
                }
                
                /* 更新文章主表 */
                Article_Model_Content::instance()->updateForEntity($set['data'], $set['data']['content_id']);
                
                /* 更新文章详情表 */
                if (Article_Model_Content_Detail::instance()->fetchByFV('content_id', $set['data']['content_id'])) {
                    Article_Model_Content_Detail::instance()->updateForEntity($set['data'], $set['data']['content_id']);
                } else {
                    Article_Model_Content_Detail::instance()->addForEntity($set['data']);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑文章失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存文章－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $res['data'] = array(
                'content_id' => (int)$this->input->post('content_id', 0),
                'category' => $this->input->post('category'),
                'title' => $this->input->post('title'),
                'subtitle' => $this->input->post('subtitle'),
                'alias' => $this->input->post('alias'),
                'image' => $_FILES['image'],
                'label' => $this->input->post('label'),
                'status' => $this->input->post('status'),
                'pinned' => $this->input->post('pinned'),
                'recommended' => $this->input->post('recommended'),
                'mtime' => date(DATETIME_FORMAT),
                'body' => $_POST['body'],
                'meta_title' => $_POST['meta_title'],
                'meta_keywords' => $_POST['meta_keywords'],
                'meta_description' => $_POST['meta_description'],
                'user_type' => 'admin',
                'ip' => Zeed_Util::clientIP(),
                'link'=>$this->input->post('link'),
        );
        
        
        /* 数据验证 */
        if (! $res['data']['category']) {
            $res['status'] = 1;
            $res['error'] = '请选择所属分类';
            return $res;
        }
        if (empty($res['data']['title'])) {
            $res['status'] = 1;
            $res['error'] = '请填写文章标题';
            return $res;
        }
        
        if(empty($res['data']['status'])){
            $res['data']['status'] = 0;
        }
        
        /* 处理添加时间 */
        if (! $res['data']['content_id']) {
            $res['data']['ctime'] = date(DATETIME_FORMAT);
        }
        
        /* 处理发布人信息 */
        $author = Com_Admin_Authorization::getLoggedInUser();
        $res['data']['userid'] = $author['userid'];
        
        return $res;
    }
    
    /**
     * 预览文章
     */
    public function preview()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        $content_id = (int) $this->input->get('id');
    
        try {
            if (! $article = Article_Model_Content::instance()->fetchByPK($content_id)) {
                throw new Zeed_Exception('查无此文章');
            }
    
            $article[0]['body'] = '';
            $article_body = Article_Model_Content_Detail::instance()->fetchByFV('content_id', $content_id);
            if (! empty($article_body)) {
                $article[0]['body'] = $article_body[0]['body'];
            }
    
            $data['article'] = $article[0];
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('预览失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.preview');
        return parent::multipleResult(self::RS_SUCCESS);
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
    
        $content_id = (int) $this->input->post('id');
        
        /* 获取文章内容 */
        if (! $article = Article_Model_Content::instance()->fetchByPK($content_id)) {
            $this->setStatus(1);
            $this->setError('查无此文章');
            return self::RS_SUCCESS;
        }
            
        /* 执行发布 */
        if ($article[0]['status'] == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $this->changeStatus($content_id, $status);
    
        return self::RS_SUCCESS;
    }
    
    /**
     * 推荐
     */
    public function recommended()
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    
    	if (! $this->input->isPOST()) {
    		$this->setStatus(1);
    		$this->setError('请勿非法操作');
    		return self::RS_SUCCESS;
    	}
    
    	$content_id = (int) $this->input->post('id');
    
    	/* 获取文章内容 */
    	if (! $article = Article_Model_Content::instance()->fetchByPK($content_id)) {
    		$this->setStatus(1);
    		$this->setError('查无此文章');
    		return self::RS_SUCCESS;
    	}
    
    	/* 执行推荐 */
    	if ($article[0]['recommended'] == 1) {
    		$recommended = 0;
    	} else {
    		$recommended = 1;
    	}
    	$this->changeRecommended($content_id,$recommended);
    	
    	return self::RS_SUCCESS;
    }
    
    /**
     * 扔进回收站
     */
    public function trash()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $content_id = trim($this->input->post('id'));
        $status = -1;
        
        $this->changeStatus($content_id, $status);
        
        return self::RS_SUCCESS;
    }
    
    /**
     * 更新文章状态
     */
    private function changeStatus($content_id, $status = 0)
    {
        try {
            $set = array('status' => $status);
            
            if (is_string($content_id)) {
                if (strpos($content_id, ',')) {
                    $content_id = explode(',', $content_id);
                } else {
                    $content_id = array((int) $content_id);
                }
                $content_id = implode(',', $content_id);
            }
            
            $where = "content_id in ({$content_id})";
            Article_Model_Content::instance()->update($set, $where);
            
            $data['status'] = $status;
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('更新文章状态失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setError('更新成功');
        $this->setData('data', $data);
        return self::RS_SUCCESS;
    }
    
    /**
     * 更新推荐状态
     */
    private function changeRecommended($content_id, $recommended = 0)
    {
    	try {
    		$set = array('recommended' => $recommended);
    
    		if (is_string($content_id)) {
    			if (strpos($content_id, ',')) {
    				$content_id = explode(',', $content_id);
    			} else {
    				$content_id = array((int) $content_id);
    			}
    			$content_id = implode(',', $content_id);
    		}
    
    		$where = "content_id in ({$content_id})";
    		Article_Model_Content::instance()->update($set, $where);
    
    		$data['recommended'] = $recommended;
    	} catch (Zeed_Exception $e) {
    		$this->setStatus(1);
    		$this->setError('更新文章状态失败 : ' . $e->getMessage());
    		return self::RS_SUCCESS;
    	}
    
    	$this->setError('更新成功');
    	$this->setData('data', $data);
    	return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8