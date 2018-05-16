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

class BrandController extends GoodsAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 商品品牌列表
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
                $where = "brand_name LIKE '%{$key}%' OR brand_keywords LIKE '%{$key}%'";
            }
             
            $order = 'ctime DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
            
            $brands = Goods_Model_Brand::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Goods_Model_Brand::instance()->getCount($where);
             
            $data['brands'] = $brands ? $brands : array();
        }
        
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'brand.index');
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
        
        $this->addResult(self::RS_SUCCESS, 'php', 'brand.edit');
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
                if (! Goods_Model_Brand::instance()->addForEntity($set['data'])) {
                    throw new Zeed_Exception('添加品牌失败');
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加品牌失败 : ' . $e->getMessage());
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
    
        $brand_id = (int) $this->input->query('brand_id');
        
        $brand = Goods_Model_Brand::instance()->fetchByPK($brand_id);
        if (null === $brand || ! is_array($brand)) {
            $this->setStatus(1);
            $this->setError('查无此品牌。');
            return self::RS_SUCCESS;
        }
        $data['brand'] = $brand[0];
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'brand.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 编辑 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                Goods_Model_Brand::instance()->updateForEntity($set['data'], $set['data']['brand_id']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑品牌失败 : ' . $e->getMessage());
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
                'brand_id' => $this->input->post('brand_id', 0),
                'brand_name' => trim($this->input->post('brand_name')),
                'brand_name_en' => trim($this->input->post('brand_name_en')),
                'brand_url' => trim($this->input->post('brand_url')),
                'brand_desc' => trim($this->input->post('brand_desc')),
                'brand_logo' => $_FILES['brand_logo'],
        		'mtime' => date(DATETIME_FORMAT)
        );
        
        /* 数据验证 */
        if (empty($res['data']['brand_name'])) {
            $res['status'] = 1;
            $res['error'] = '品牌名称不能为空';
            return $res;
        }
        
        if (! $res['data']['brand_id']) {
            $res['data']['ctime'] = $res['data']['mtime'];
        }
        
        /* 处理图片上传 */
        try {
            $files = $res['data']['brand_logo'];
            if ($files['error'] === UPLOAD_ERR_OK) {
                $files_upload = Support_Attachment::upload($files);
                $res['data']['brand_logo'] = $files_upload['filepath'];
            } else {
                unset($res['data']['brand_logo']);
            }
        } catch (Zeed_Exception $e) {
            $res['status'] = 1;
            $res['error'] = '上传图片失败：' . $e->getMessage();
            return $res;
        }
        
        return $res;
    }
    
    /**
     * 删除
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
        
        $brand_id = $this->input->post('brand_id');
        if (is_string($brand_id)) {
            if (strpos($brand_id, ',')) {
                $brand_id = explode(',', $brand_id);
            } else {
                $brand_id = array((int) $brand_id);
            }
        }
        
        try {
            Goods_Model_Brand::instance()->deleteByFV('brand_id', $brand_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除属性失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8