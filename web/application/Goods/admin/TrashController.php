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

class TrashController extends GoodsAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 商品回收站
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
        $category_id = (int) $this->input->get('category', 0);
        $brand_id = (int) $this->input->get('brand_id', 0);
        $is_shelf = (int) $this->input->get('is_shelf', -1);
        $price_min = $this->input->get('price_min', 0);
        $price_max = $this->input->get('price_max', 0);
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
        	$offset = $page * $perpage;
        	$page = $page + 1;
        	
        	$where['is_del'] = 1;
        	$where['parent_id'] = 0;
        	if ($category_id) {
        	    $where['category'] = $category_id;
        	}
        	if ($brand_id) {
        	    $where['brand_id'] = $brand_id;
        	}
        	if ($is_shelf > -1) {
        	    $where['is_shelf'] = $is_shelf;
        	}
        	if ($price_min > 0) {
        	    $where[] = 'price >= ' . $price_min;
        	}
        	if ($price_max > 0) {
        	    $where[] = 'price <= ' . $price_max;
        	}
        	if (! empty($key)) {
        		$where[] = "(name LIKE '%{$key}%' or sku LIKE '%{$key}%')";
        	}
        	
        	$order = 'ctime DESC';
        	if ($ordername) {
        		$order = $ordername . " " . $orderby;
        	}
        	
        	$contents = Goods_Model_Content::instance()->fetchByWhere($where, $order, $perpage, $offset);
        	$data['count'] = Goods_Model_Content::instance()->getCount($where);
        	
        	/* 处理一些基本信息 */
        	$config = Zeed_Storage::instance()->getConfig();
        	if (! empty($contents)) {
        	    foreach ($contents as &$v) {
        	        // 处理图片路径问题
        	        $v['image_default'] = $config['url_prefix_b'] . $v['image_default'];
        	        
        	        // 处理分类名称
        	        $category = Goods_Model_Category::instance()->fetchByPK($v['category'], array('category_name'));
        	        if (! empty($category)) {
        	            $v['category_name'] = $category[0]['category_name'];
        	        }
        	        
        	        // 处理品牌
        	        $brand = Goods_Model_Brand::instance()->fetchByPK($v['brand_id'], array('brand_name'));
        	        if (! empty($brand)) {
        	            $v['brand_name'] = $brand[0]['brand_name'];
        	        }
        	    }
        	}
        	
        	$data['contents'] = $contents ? $contents : array();
        }
        
        /* 获取所有分类信息 */
        $data['categories'] = Goods_Model_Category::instance()->getAllCategoriesForSelect();
        
        /* 获取所有品牌信息 */
        $data['brands'] = Goods_Model_Brand::instance()->fetchAll();
		
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        $data['category_id'] = $category_id;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'trash.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 彻底删除
     * 支持 AJAX 和 GET 请求删除
     * 支持参数: content_id(int, array, 逗号分割)
     * 删除: 将商品所有信息序列化存储到 trash 表, 删除 Content 及相关资源
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 获取参数，并校验商品归属权 */
        $content_ids = $this->validateContentid();
        
        if (! empty($content_ids)) {
            ContentHelper::deleteContentByContentid($content_ids);
            /* 删除 Content 及相关资源 */
            foreach ($content_ids as $content_id) {
                Goods_Model_Content::instance()->deleteByPK($content_id);
                Goods_Model_Content::instance()->deleteByFV('parent_id', $content_id);
                Goods_Model_Content_Detail::instance()->deleteByPK($content_id);
                Goods_Model_Property::instance()->deleteByFV('content_id', $content_id);
                Goods_Model_Attachment::instance()->deleteByFV('content_id', $content_id);
                Goods_Model_Related::instance()->deleteByFV('content_id', $content_id);
            }
        }
        
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8