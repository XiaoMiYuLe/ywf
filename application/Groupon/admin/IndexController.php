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

class IndexController extends GrouponAdminAbstract
{
    
    public $perpage = 20;
    
    /**
     * 单页列表
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        $type = $this->input->get('type');
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
            
            $where = "1 = 1";
            
            if (! empty($key)) {
                $key = mysql_real_escape_string($key);
                $where .= " AND (title LIKE '%{$key}%' or goods_name LIKE '%{$key}%')";
            }
            
            $notime = date("Y-m-d H:i:s", time());
            /* 未开始 */
            if($type == 1) {
                $where .= " AND start_time > '{$notime}'";
            }
            /* 开始 */
            if($type == 2) {
                $where .= " AND start_time < '{$notime}' AND end_time > '{$notime}'";
            }
            
            /* 结束 */
            if($type == 3) {
                $where .= " AND end_time < '{$notime}'";
            }
            
            $order = 'ctime DESC';
        
            $bulks = Groupon_Model_Bulk::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Groupon_Model_Bulk::instance()->getCount($where);
            
            $data['bulks'] = $bulks ? $bulks : array();
        }
        
        
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        $data['key'] = $key;
        $data['type'] = $type;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加单页
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $data['categorys'] = Groupon_Model_Category::instance()->fetchByWhere(null, null);
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加单页 - 保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $bulk_id = Groupon_Model_Bulk::instance()->addForEntity($set['data']);
                if (! $bulk_id) {
                    
                    Goods_Model_Content::instance()->update(array('promotion_type'=>2), "sku = '{$set['data']['sku']}'");
                    throw new Zeed_Exception('Add Bulk failed, please try again.');
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
     * 修改单页
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
    
        $bulk_id = (int) $this->input->query('bulk_id');
        $bulk = Groupon_Model_Bulk::instance()->fetchByPK($bulk_id);
        if (null === $bulk || ! is_array($bulk)) {
            $this->setStatus(1);
            $this->setError('The bulk is not exist.');
            return self::RS_SUCCESS;
        }
        
        $data['categorys'] = Groupon_Model_Category::instance()->fetchByWhere(null, null);
        
        if(! empty($data['categorys'])) {
            $category = explode(',', $bulk[0]['category_id']);
            foreach ($data['categorys'] as $k => $v) {
                if(in_array($v['category_id'], $category)) {
                    $data['categorys'][$k]['type'] = 1;
                }
            }
        }
        
        $data['bulk'] = $bulk[0];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改单页 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                Groupon_Model_Bulk::instance()->updateForEntity($set['data'], $set['data']['bulk_id']);
                Goods_Model_Content::instance()->update(array('promotion_type'=>2), "sku = '{$set['data']['sku']}'");
                
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit bulk failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存单页－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        $res['data'] = array(
                'bulk_id' => $this->input->post('bulk_id', 0),
                'title' => $this->input->post('title'),
                'category_id' => $this->input->post('category_id'),
                'number' => $this->input->post('number'),
                'start_time' => trim($this->input->post('start_time')),
                'end_time' => $this->input->post('end_time'),
                'inventory_sum' => $this->input->post('inventory'),
                'inventory' => $this->input->post('inventory'),
                'sku' => $this->input->post('sku'),
                'goods_name' => '',
                'price' => $this->input->post('price'),
                'integral' => (int)$this->input->post('integral') ? (int)$this->input->post('integral') : 0,
                'ctime' => date(DATETIME_FORMAT),
        );
        
        /* 通用数据验证 */
        if (empty($res['data']['title'])) {
            $res['status'] = 1;
            $res['error'] = '请选择名称';
            return $res;
        }
        
        if (empty($res['data']['category_id'])) {
            $res['status'] = 1;
            $res['error'] = '请选择标签';
            return $res;
        }
        
        if (empty($res['data']['start_time'])) {
            $res['status'] = 1;
            $res['error'] = '请选择开始时间';
            return $res;
        }
        
        if (empty($res['data']['end_time'])) {
            $res['status'] = 1;
            $res['error'] = '请选择结束时间';
            return $res;
        }
        
        if (empty($res['data']['inventory'])) {
            $res['status'] = 1;
            $res['error'] = '请输入库存';
            return $res;
        }
        
        if (empty($res['data']['price'])) {
            $res['status'] = 1;
            $res['error'] = '请输入价格';
            return $res;
        }
        
        if (empty($res['data']['sku'])) {
            $res['status'] = 1;
            $res['error'] = '请输入SKU';
            return $res;
        }
        
        if (empty($res['data']['inventory'])) {
            $res['status'] = 1;
            $res['error'] = '请输入库存';
            return $res;
        }
        
        $res['data']['category_id'] = implode(",", $res['data']['category_id']);
        
        // 判断是否参加过团购
        if(! $this->input->post('skutype')) {
            $where = "sku = '{$res['data']['sku']}' ";
            $goods = Goods_Model_Content::instance()->fetchByWhere($where, "content_id DESC", 1, 0);
            
            if(! empty($goods[0]['promotion_type'])) {
                $res['status'] = 1;
                $res['error'] = '同商品不能同事参加两个活动';
                return $res;
            }
        }
        
        // 判断商品
        $where = "sku = '{$res['data']['sku']}' AND is_del = 0 ";
        $goods = Goods_Model_Content::instance()->fetchByWhere($where, "content_id DESC", 1, 0);
        
        if(! empty($goods) ) {
            $res['data']['goods_name'] = $goods[0]['name'];
            $info = $goods[0];
            if($info['price'] < $res['data']['price']) {
                $res['status'] = 1;
                $res['error'] = '团购价格不能大于商品价格';
                return $res;
            }
            
            if($info['stock'] < $res['data']['inventory'] || $res['data']['number'] > $res['data']['inventory']) {
                $res['status'] = 1;
                $res['error'] = '库存不能大于商品库存并且大于N件起团';
                return $res;
            }
            
            if($res['data']['start_time'] > $res['data']['end_time']) {
                $res['status'] = 1;
                $res['error'] = '开始时间不能大于结束时间';
                return $res;
            }
        } else {
            $res['status'] = 1;
            $res['error'] = '无此商品';
            return $res;
        }
        
        return $res;
    }
    
    /**
     * 删除单页
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
            
            $info = Groupon_Model_Bulk::instance()->fetchByPK($id);
            
            Groupon_Model_Bulk::instance()->deleteByPK($id);
            Goods_Model_Content::instance()->update(array('promotion_type'=>0), "sku = '{$info[0]['sku']}'");
            
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('Drop bulk failed : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
    
}
// End ^ Native EOL ^ UTF-8