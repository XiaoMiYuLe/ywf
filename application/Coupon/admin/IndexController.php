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

class IndexController extends CouponAdminAbstract
{
    public $perpage = 15;

    /**
     * 优惠券列表
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        
        $status = (int) $this->input->get('status');
        $coupon_type = (int) $this->input->get('coupon_type');
        $coupon_type = $coupon_type ? $coupon_type : 1;
        $relation_type = (int) $this->input->get('relation_type');
     
        $valid_stime = $this->input->get('valid_stime');
        $valid_etime = $this->input->get('valid_etime');
        $grant_stime = $this->input->get('grant_stime');
        $grant_etime = $this->input->get('grant_etime');
        
        $keywords = trim($this->input->get('keywords'));

        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $where = 'coupon_category.is_del = 0 AND coupon_category.disabled = 0 AND coupon_type = ' . $coupon_type;
            
            if ($relation_type) {
                $where .= " AND relation_type = " . $relation_type;
            }
            if ($status != 2) {
                $where .= " AND status = " . $status;
            }
            if ($valid_stime) {
                $where .= " AND `valid_stime` >= '$valid_stime' ";
            }
            if ($valid_etime) {
                $where .= " AND `valid_etime` <= '$valid_etime' ";
            }
            if ($grant_stime) {
                $where .= " AND `grant_stime` >= '$grant_stime' ";
            }
            if ($grant_etime) {
                $where .= " AND `grant_etime` <= '$grant_etime' ";
            }
            if (! empty($keywords) && is_string($keywords)) {
                $where .= " AND coupon_name like '%" . $keywords . "%'";
            }
            $orderby = 'ctime DESC';
            
            $coupon = Coupon_Model_Category::instance()->fetchAvailableCoupon($where, $orderby, $perpage, $offset);
            $count = Coupon_Model_Category::instance()->countCouponList($where);
            $data['coupon'] = $coupon ? $coupon : array();
            $data['count'] = $count ? $count : array();
        }
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS,'php','index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加优惠券
     */
    public function add ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
    
        $coupon_type = (int) $this->input->get('coupon_type');
        $coupon_type = $coupon_type ? $coupon_type : 1;
        $data['coupon']['coupon_type'] = $coupon_type;
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 编辑优惠券
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS,'json');
         
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $coupon_id = (int) $this->input->query('coupon_id');
        if (! $coupon = Coupon_Model_Category::instance()->fetchCouponDetail($coupon_id)){
            echo '查无此优惠券数据，即将返回';
            echo '<script> history.go(-1) </script>';
        }
        $coupon['coupon_id'] = $coupon_id;
        $data['coupon'] = $coupon;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS,'php','index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 编辑 - 保存
     */
    private function editSave ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $set = $this->_validate();
        
        if ($set['status'] == 1){
            $this->setStatus($set['status']);
            $this->setError($set['error']);
            return false;
        }
        try {
            $set = $set['data'];
            if (! $set['coupon_id']){
                $set['coupon_id'] = Coupon_Model_Category::instance()->addForEntity($set);
                if ($set['coupon_type'] == 2) Coupon_Model_Relation::instance()->addForEntity($set);
            } else {
                $save['status'] = $set['status'];
                $save['rule'] = $set['rule'];
                $save['body'] = $set['body'];
                $save['mtime'] = $set['mtime'];
                Coupon_Model_Category::instance()->updateForEntity($save, $set['coupon_id']);
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('编辑失败 : ' . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 删除优惠券
     */
    public function delete ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if (! $this->input->isAJAX()) {
            echo '请勿非法操作';
            echo '<script> history.go(-1) </script>';
        }
        $coupon_id = (int) $this->input->query('coupon_id');
        try {
            if ( ! $coupon = Coupon_Model_Category::instance()->fetchByPK($coupon_id)) {
                throw new Zeed_Exception('查无此数据');
            }
            $set = array('is_del' => 1,'mtime' => date("Y-m-d H:i:s", time()));
            Coupon_Model_Category::instance()->updateForEntity($set, $coupon_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除优惠券失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }

    /**
     * AJAX调取分类
     */
    public function getOption ()
    {
        $this->addResult(self::RS_SUCCESS, json);
        
        if (! $this->input->isAJAX()) {
            echo '请勿非法操作';
            echo '<script> history.go(-1) </script>';
        }
        $type_id = (int) $this->input->query('id');
        try {
            $where = 'parent_id = 0 AND status = 1';
            if (! $categories  = Goods_Model_Category::instance()->fetchByWhere($where)) {
                throw new Zeed_Exception('查无分类信息');
            }
            $categories = $type_id == 2 ? $categories : null;
            $this->setData('data', $categories);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('获取失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        return self::RS_SUCCESS;
    }
    
    /**
     * 查看详情
     */
    public function detail ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        $coupon_id = (int)$this->input->get('coupon_id');
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $orderby = 'coupon_listing.ctime DESC';
        $keywords = $this->input->get('keywords');
    
        /* 基本信息 */
        if (! $this->input->isAJAX()) {
            if ($keywords) {
                echo '不支持回车键搜索，即将返回';
                echo '<script> history.go(-1) </script>';
            }
            if (! $coupon = Coupon_Model_Category::instance()->fetchCouponDetail($coupon_id)){
                echo '查无此优惠券数据，即将返回';
                echo '<script> history.go(-1) </script>';
            }
            /* 此处解决leftjoin时没有关联数据导致id变为null */
            if (empty($coupon['coupon_id']))$coupon['coupon_id'] = $coupon_id;
            $data['coupon'] = $coupon;
            
            /* 获取兑换信息 */
            $where = 'coupon_id = ' . $coupon_id;
            $data['exchanged_total'] = $coupon['exchanged_total'];
            $data['surplus'] = $coupon['total'] - $data['exchanged_total'];
            $data['used_total'] = Coupon_Model_Listing::instance()->countListingByWhere($where . ' AND cpns_status = 1');
            $data['unused_total'] = Coupon_Model_Listing::instance()->countListingByWhere($where . ' AND cpns_status = 0');
        }
        
        /* 用户兑换列表 */
        if ($this->input->isAJAX()) {
            $where = 'coupon_id = ' . $coupon_id . ' AND coupon_listing.is_del = 0';
            if ($keywords){
                $where .= " AND ( phone like '%" . $keywords . "%' || username like '%" . $keywords . "%')";
            } 
            $offset = $page * $perpage;
            $listing = Coupon_Model_Listing::instance()->fetchListingByWhere($where, $orderby, $perpage, $offset);
            $data['count'] = Coupon_Model_Listing::instance()->countListingByWhere($where);
            $data['listing'] = $listing ? $listing : array();
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    
    /**
     * 设置用户优惠券状态
     * @throws Zeed_Exception
     * @return string
     */
    public function disabled()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $cpns_id = (int) $this->input->post('cpns_id');
        if (! $this->input->isAJAX()) {
            echo '请勿非法操作';
            echo '<script> history.go(-1) </script>';
        }
        try {
            if (! $cpns = Coupon_Model_Listing::instance()->fetchByPK($cpns_id)){
                throw new Zeed_Exception('查无此信息');
            }
            $disabled = $cpns[0]['disabled'] == 0 ? 1 : 0;
            Coupon_Model_Listing::instance()->updateForEntity(array('disabled' => $disabled), $cpns_id);
            $data['disabled'] = $disabled;
        } catch (Exception $e) {
            $this->setStatus(1);
            $this->setError('获取信息失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        $this->setData('data', $data);
        return self::RS_SUCCESS;
    }
    
    /**
     * 删除优惠券
     */
    public function deleteUser ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if (! $this->input->isAJAX()) {
            echo '请勿非法操作';
            echo '<script> history.go(-1) </script>';
        }
        $cpns_id = (int) $this->input->query('cpns_id');
        try {
            if (! $cpns = Coupon_Model_Listing::instance()->fetchByPK($cpns_id)) {
                throw new Zeed_Exception('查无此数据');
            }
            $set = array('is_del' => 1,'mtime' => date("Y-m-d H:i:s", time()));
            Coupon_Model_Listing::instance()->updateForEntity($set, $cpns_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除用户优惠券信息失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
    

    /**
     * 保存－ 校验
     */
    private function _validate ()
    {
        $res = array(
                'status' => 0,
                'error' => null,
                'data' => null
        );
        try {
            $res['data'] = array(
                    'coupon_id' => $this->input->post('coupon_id'),
                    'coupon_name' => $this->input->post('coupon_name'),
                    'total' => (int) $this->input->post('total'),
                    'user_total' => (int) $this->input->post('user_total'),
                    'face_value' => (float) $this->input->post('face_value'),
                    'status' => 0,
                    'coupon_type' => (int) $this->input->post('coupon_type'),
                    'coupon_point' => (int) $this->input->post('coupon_point'),
                    'disabled' => 0,
                    'is_exchange' => 0,
                    'rule' => $this->input->post('rule'),
                    'body' => $this->input->post('body'),
                    'valid_stime' => $this->input->post('valid_stime'),
                    'valid_etime' => $this->input->post('valid_etime'),
                    'grant_stime' => $this->input->post('grant_stime'),
                    'grant_etime' => $this->input->post('grant_etime'),
                    'is_del' => 0,
                    'ctime' => date(('Y-m-d H:i:s'), time()),
                    'mtime' => date(('Y-m-d H:i:s'), time()),
                    'basic_price' => (float)$this->input->post('basic_price'),
                    'relation_type' => (int)$this->input->post('relation_type'),
                    'relation_content' => $this->input->post('relation_content')
            );
            
            if($res['data']['coupon_point'] || $res['data']['coupon_point'] === 0) $res['data']['is_exchange'] = 1;
            
            /* 新增状态 */
            if(! $res['data']['coupon_id']){
                
                /* 通用判断 */
                if(! $res['data']['coupon_name']){
                    throw new Zeed_Exception('请输入优惠券名称');
                }
                if(! $res['data']['face_value']){
                    throw new Zeed_Exception('请输入面额');
                }
                if(! $res['data']['total']){
                    throw new Zeed_Exception('请输入发放量');
                }
                if(! $res['data']['valid_stime'] || ! $res['data']['valid_etime']){
                    throw new Zeed_Exception('请输入有效期');
                }
                if(! $res['data']['grant_stime'] || ! $res['data']['grant_etime']){
                    throw new Zeed_Exception('请输入发放时间');
                }
                if(! $res['data']['coupon_type']){
                    throw new Zeed_Exception('请选择优惠券类型');
                }
                if( strtotime($res['data']['valid_stime']) >= strtotime($res['data']['valid_etime'])){
                    throw new Zeed_Exception('有效期开始时间不得大于结束时间');
                }
                if( strtotime($res['data']['grant_stime']) >= strtotime($res['data']['grant_etime'])){
                    throw new Zeed_Exception('发放开始时间不得大于结束时间');
                }
                if (strtotime($res['data']['grant_stime']) >= strtotime($res['data']['valid_stime'])){
                    throw new Zeed_Exception('发放开始时间不得大于有效期开始时间');
                }
                if (strtotime($res['data']['grant_etime']) >= strtotime($res['data']['valid_etime'])){
                    throw new Zeed_Exception('发放结束时间不得大于有效期结束时间');
                }
                
                /* 满减券判断 */
                if($res['data']['coupon_type'] == 2){
                    if (! $res['data']['basic_price']){
                       throw new Zeed_Exception('请设定使用规则');
                    }
                    if (! $res['data']['relation_type']){
                        throw new Zeed_Exception('请选择满减券类型');
                    }
                    if ( $res['data']['basic_price'] <= $res['data']['face_value']){
                        throw new Zeed_Exception('优惠券面额不可大于满额');
                    }
                    if ($res['data']['relation_type'] != 1){
                        if (! $res['data']['relation_content']){
                            throw new Zeed_Exception('请选择满减关联信息');
                        }
                    }
                }
            }
            
            /* 编辑状态 */
            if( $res['data']['coupon_id']){
                if($this->input->post('status') === null){
                    throw new Zeed_Exception('请设置优惠券状态');
                }
                $res['data']['status'] = (int) $this->input->post('status');
            }
        } catch (Zeed_Exception $e){
            $res['status'] = 1;
            $res['error'] = '数据验证失败  - '.$e->getMessage();
        }
        
        return $res;
    }
}