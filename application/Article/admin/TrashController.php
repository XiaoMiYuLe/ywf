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

class TrashController extends ArticleAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 回收站
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
        $category = (int) $this->input->get('category', 0);
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
        	$offset = $page * $perpage;
        	$page = $page + 1;
        	
        	$where['status'] = "-1";
        	if ($category) {
        	    $where['category'] = $category;
        	}
        	if (! empty($key)) {
        		$where[] = "title LIKE '%{$key}%'";
        	}
        	
        	$order = 'ctime DESC';
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
        $this->addResult(self::RS_SUCCESS, 'php', 'trash.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 还原
     */
    public function restore()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $content_id = (int) $this->input->post('id');
        
        try {
            /* 获取文章内容 */
            if (! $article = Article_Model_Content::instance()->fetchByPK($content_id)) {
                throw new Zeed_Exception('查无此文章');
            }
        
            /* 执行还原 - 标记为发布状态 */
            $set = array('status' => 1);
            $where = "content_id in ({$content_id})";
            Article_Model_Content::instance()->update($set, $where);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('还原文章失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        return self::RS_SUCCESS;
    }
    
    /**
     * 彻底删除
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $content_id = $this->input->post('id');
        if (is_string($content_id)) {
            if (strpos($content_id, ',')) {
                $content_id = explode(',', $content_id);
            } else {
                $content_id = array((int) $content_id);
            }
        }
    
        try {
            if(!empty($content_id)){
                foreach ($content_id as $k=>&$v){
                    /* 获取文章内容 */
                    if (! $article = Article_Model_Content::instance()->fetchByPK($v)) {
                        throw new Zeed_Exception('查无此文章');
                    }
                    
                    /* 执行删除 */
                    Article_Model_Content_Detail::instance()->deleteByFV('content_id', $v); // 删除文章详情
                    Article_Model_Content::instance()->deleteByPK($v); // 删除文章主体
                }
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除文章失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8