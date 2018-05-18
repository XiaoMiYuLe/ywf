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

class IndexController extends GoodsAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 商品后台首页
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        /* 接收参数 */
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        $goods_status = (int) $this->input->get('goods_status');
        $goods_type = (int)$this->input->get('goods_type');
        $goods_pattern = (int)$this->input->get('goods_pattern');
        $goods_id = (int) $this->input->get('goods_id'); 
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
        	$offset = $page * $perpage;
        	
        	$where = "is_del = 0";
        	if ($key) {
        		$where .= " and goods_name like '%".$key."%'";
        	}
        	if ($goods_id) {
        		$where .= " and goods_id = {$goods_id}";
        	}
        	if ($goods_status) {
        		$where .= " and goods_status = {$goods_status}";
        	}
        	if ($goods_type) {
        		$where .= " and goods_type = {$goods_type}";
        	}
        	if ($goods_pattern) {
        		$where .= " and goods_pattern = {$goods_pattern}";
        	}
        	$order = array("is_new asc","ctime desc");
        	
        	$contents = Goods_Model_List::instance()->fetchByWhere($where,$order,$perpage,$offset);
        	if ($contents) {
        		foreach ($contents as $k => &$v) {
        			$v['money'] = number_format($v['all_fee'] - $v['spare_fee'],2)."&nbsp;/&nbsp;".number_format($v['all_fee'],2);
        		}
        	}
        	$data['count'] = Goods_Model_List::instance()->getCount($where);
        }
		$data['contents'] = (array)$contents;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function detail()
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    	
//     	$goods = Goods_Model_List::instance()->fetchByWhere("goods_pattern = 1 and is_del = 0","ctime desc");
    	$goods_id = $this->input->query('goods_id');
    	if (!$goods_id) {
    		$this->setStatus(1);
    		$this->setError('参数缺失或参数错误');
    		return self::RS_SUCCESS;
    	}
    	$content = Goods_Model_List::instance()->fetchByWhere("goods_id = {$goods_id}");
    	if (!$content) {
    		$this->setStatus(1);
    		$this->setError('该商品不存在');
    		return self::RS_SUCCESS;
    	}
    	$data['content'] = $content[0];
    	
    	$this->setData('data', $data);
    	$this->addResult(self::RS_SUCCESS, 'php', 'index.detail');
    	return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加其他商品
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
     
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加其他商品 - 保存
     */
    public function addSave()
    {
        $data = $this->_validate();
        if ($data['status'] == 0) {
        	
        	$id = Goods_Model_List::instance()->addForEntity($data['data']);
        	if (!$id) {
        		$this->setStatus(1);
        		$this->setError('添加失败');
        		return false;
        	}
            $this->setData('data', $data['data']);
            return true;
        }
        
        $this->setStatus($data['status']);
        $this->setError($data['error']);
        return false;
    }
    
    /**
     * 编辑其他商品
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
    
        $goods_id = (int) $this->input->get('goods_id');
        
        /* 查询商品主体信息 */
        if (! $content = Goods_Model_List::instance()->fetchByPK($goods_id)) {
            $this->setStatus(1);
            $this->setError('查无此商品');
            return self::RS_SUCCESS;
        } else {
        	if (($content[0]['goods_type'] != 2 && $content[0]['goods_type'] != 3  && $content[0]['goods_type'] != 4) || $content[0]['goods_pattern'] != 3 ) {
        		$this->setStatus(1);
        		$this->setError('该产品不属于其他产品,请返回列表再操作');
        		return self::RS_SUCCESS;
        	}
        }
        $content = $content[0];
        
        $data['time'] = date(DATETIME_FORMAT);
        $data['goods_id'] = $goods_id;
        $data['content'] = $content;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 编辑其他商品 - 保存
     */
    public function editSave()
    {
        $data = $this->_validate();
        if ($data['status'] == 0) {
            if (!Goods_Model_List::instance()->update($data['data'],"goods_id = {$data['data']['goods_id']}")) {
                $this->setStatus(1);
                $this->setError('编辑商品失败。');
                return false;
            }
            return true;
        }
        
        $this->setStatus($data['status']);
        $this->setError($data['error']);
        return false;
    }
    
    /**
     * 保存其他产品 － 校验
     */
    private function _validate ()
    {
    	$res = array('status' => 0, 'error' => null, 'data' => null);
    
    	$res['data'] = array(
    			'goods_id' => (int)$this->input->post('goods_id', 0),
    			'goods_name' => trim($this->input->post('goods_name')),
    			'goods_detail' => $this->input->post('goods_detail'),
    			'safety' => $this->input->post('safety'),
//     			'goods_pattern' => (int)$this->input->post('goods_pattern'),
    			'goods_type' => (int)$this->input->post('goods_type'),
    			'goods_status' => (int)$this->input->post('goods_status'),
    			'debtor_name' => trim($this->input->post('debtor_name')),
    			'debtor_card' => trim($this->input->post('debtor_card')),
    			'yield' => trim($this->input->post('yield')),
    			'high_pay' => trim($this->input->post('high_pay')),
    			'low_pay' => trim($this->input->post('low_pay')),
    			'increasing_pay' => trim($this->input->post('increasing_pay')),
    			'financial_period' => (int)trim($this->input->post('financial_period')),
    			'goods_broratio' => trim($this->input->post('goods_broratio')),
    			'all_fee' => trim($this->input->post('all_fee')),
    			'comment' => trim($this->input->post('comment')),
    			'deal_way' => trim($this->input->post('deal_way')),
    			'redeem_status' => (int)$this->input->post('redeem_status'),
    			'principal_status' => (int)$this->input->post('principal_status'),
    			'deal_status' => (int)$this->input->post('deal_status'),
    			'mtime' => date(DATETIME_FORMAT)
    	);
//     	Zeed_Benchmark::print_r($res['data']);exit;
    	if (!is_numeric($res['data']['yield'])) {
    		$res['status'] = 1;
    		$res['error'] = '请输入有效的年化收益率';
    		return $res;
    	}
    	if (!is_numeric($res['data']['high_pay'])) {
    		$res['status'] = 1;
    		$res['error'] = '购买上限请输入有效数值';
    		return $res;
    	}
    	if ($res['data']['high_pay'] <= 0) {
    		$res['status'] = 1;
    		$res['error'] = '购买上限数值请确保大于0';
    		return $res;
    	}
    	if (!is_numeric($res['data']['increasing_pay'])) {
    		$res['status'] = 1;
    		$res['error'] = '递增金额请输入有效数值';
    		return $res;
    	}
    	if ($res['data']['increasing_pay'] < 0) {
    		$res['status'] = 1;
    		$res['error'] = '递增金额请确保大于或等于0';
    		return $res;
    	}
    	if ($res['data']['increasing_pay'] >= $res['data']['high_pay']) {
    		$res['status'] = 1;
    		$res['error'] = '递增金额不能大于或等于购买上限';
    		return $res;
    	}
    	if (!is_numeric($res['data']['low_pay'])) {
    		$res['status'] = 1;
    		$res['error'] = '起售金额请输入有效数值';
    		return $res;
    	}
    	if ($res['data']['low_pay'] <= 0) {
    		$res['status'] = 1;
    		$res['error'] = '起售金额请确保大于0';
    		return $res;
    	}
    	if ($res['data']['low_pay'] >= $res['data']['high_pay']) {
    		$res['status'] = 1;
    		$res['error'] = '起售金额不能大于或等于购买上限';
    		return $res;
    	}
    	if (!is_numeric($res['data']['goods_broratio'])) {
    		$res['status'] = 1;
    		$res['error'] = '请输入有效的佣金比例';
    		return $res;
    	}
    	if (!is_numeric($res['data']['all_fee'])) {
			$res['status'] = 1;
			$res['error'] = '请输入有效的产品总额度';
			return $res;
		}
		if ($res['data']['all_fee'] < $res['data']['low_pay']) {
			$res['status'] = 1;
			$res['error'] = '产品总额度不能小于起售金额';
			return $res;
		}
    	
    	/* 处理添加时间 */
    	if (! $res['data']['goods_id']) {
    		$res['data']['goods_pattern'] = 2;
    		$res['data']['ctime'] = $res['data']['mtime'];
    		$res['data']['spare_fee'] = $res['data']['all_fee'];
    	} else {
    		$goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$res['data']['goods_id']}");
    		if ($goods) {
    			if ($goods[0]['all_fee'] == $goods[0]['spare_fee']) {
    				$res['data']['spare_fee'] = $res['data']['all_fee'];
    			} else {
    				$res['data']['spare_fee'] = $res['data']['all_fee']-$goods[0]['all_fee']+$goods[0]['spare_fee'];
    				if (($res['data']['spare_fee'] < $goods[0]['low_pay']) && ($res['data']['goods_status'] == 1 )) {
    					$res['status'] = 1;
    					$res['error'] = '该产品剩余额度不足以承担下次交易,请重新选择产品状态';
    					return $res;
    				}
    			}
    		} else {
    			$res['status'] = 1;
				$res['error'] = '该商品不存在';
				return $res;
    		}
    	}
    	return $res;
    }
    
    /**
     * 添加债权商品
     */
    public function addGoods()
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    
    	if ($this->input->isPOST()) {
    		$this->addGoodsSave();
    		return self::RS_SUCCESS;
    	}
    	 
    	$this->setData('data', $data);
    	$this->addResult(self::RS_SUCCESS, 'php', 'index.editGoods');
    	return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加债权商品 - 保存
     */
    public function addGoodsSave()
    {
    	$data = $this->_valid();
    	if ($data['status'] == 0) {
    		$id = Goods_Model_List::instance()->addForEntity($data['data']);
    		if (!$id) {
    			$this->setStatus(1);
    			$this->setError('添加失败');
    			return false;
    		}
    		$this->setData('data', $data['data']);
    		return true;
    	}
    
    	$this->setStatus($data['status']);
    	$this->setError($data['error']);
    	return false;
    }
    
    /**
     * 编辑债权商品
     */
    public function editGoods()
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    
    	if ($this->input->isPOST()) {
    		$this->editGoodsSave();
    		return self::RS_SUCCESS;
    	}
    
    	$goods_id = (int) $this->input->get('goods_id');
    
    	/* 查询商品主体信息 */
    	if (! $content = Goods_Model_List::instance()->fetchByPK($goods_id)) {
    		$this->setStatus(1);
    		$this->setError('查无此商品');
    		return self::RS_SUCCESS;
    	} else {
    		if ($content[0]['goods_type'] != 1) {
    			$this->setStatus(1);
    			$this->setError('该产品不属于债权产品,请返回列表后再操作');
    			return self::RS_SUCCESS;
    		}
    	}
    	$content = $content[0];
    
    
    	$data['goods_id'] = $goods_id;
    	$data['content'] = $content;
    	
    	$this->setData('data', $data);
    	$this->addResult(self::RS_SUCCESS, 'php', 'index.editGoods');
    	return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 编辑债券商品 - 保存
     */
    public function editGoodsSave()
    {
    	$data = $this->_valid();
//     	echo '<pre>';print_r($data);exit;
    	if ($data['status'] == 0) {
    		if (!Goods_Model_List::instance()->update($data['data'],"goods_id = {$data['data']['goods_id']}")) {
    			$this->setStatus(1);
    			$this->setError('编辑商品失败。');
    			return false;
    		}
    		return true;
    	}
    	
    	$this->setStatus($data['status']);
    	$this->setError($data['error']);
    	return false;
    }
    
    /**
     * 保存债权产品 － 校验
     */
    private function _valid ()
    {
    	$res = array('status' => 0, 'error' => null, 'data' => null);
    
    	$res['data'] = array(
    			'goods_id' => (int)$this->input->post('goods_id', 0),
    			'goods_name' => trim($this->input->post('goods_name')),
    			'goods_detail' => $this->input->post('goods_detail'),
    			'safety' => $this->input->post('safety'),
    			'goods_pattern' => (int)$this->input->post('goods_pattern'),
    			'all_fee' => trim($this->input->post('all_fee')),
    			'goods_status' => (int)$this->input->post('goods_status'),
    			'debtor_name' => trim($this->input->post('debtor_name')),
    			'debtor_card' => trim($this->input->post('debtor_card')),
    			'deal_way' => trim($this->input->post('deal_way')),
    			'start_time' => $this->input->post('start_time'),
    			'end_time' => $this->input->post('end_time'),
    			'deal_date' => $this->input->post('deal_date'),
    			'yield' => trim($this->input->post('yield')),
    			'high_pay' => trim($this->input->post('high_pay')),
    			'low_pay' => trim($this->input->post('low_pay')),
    			'increasing_pay' => trim($this->input->post('increasing_pay')),
    			'financial_period' => (int)trim($this->input->post('financial_period')),
    			'goods_broratio' => trim($this->input->post('goods_broratio')),
    			'comment' => trim($this->input->post('comment')),
    			'is_now' => (int)$this->input->post('is_now'),
    			'redeem_status' => (int)$this->input->post('redeem_status'),
    			'principal_status' => (int)$this->input->post('principal_status'),
    			'deal_status' => (int)$this->input->post('deal_status'),
    			'mtime' => date(DATETIME_FORMAT),
    	        'is_voucher'=>(int)$this->input->post('is_voucher'),
    	        'is_interest'=>(int)$this->input->post('is_interest'),
    	        'is_transfer'=>(int)$this->input->post('is_transfer'),
        	    'distance_order'=>$this->input->post('distance_order',null),
    	        'distance_cash'=>$this->input->post('distance_cash',null),
        	    'rate_max'=>0,
        	    'rate_min'=>-0.05,
    	        'counter_fee'=>trim($this->input->post('counter_fee',null)),
    	);
    	if (!is_numeric($res['data']['yield'])) {
    		$res['status'] = 1;
    		$res['error'] = '请输入有效的年化收益率';
    		return $res;
    	}
    	if (!is_numeric($res['data']['high_pay'])) {
    		$res['status'] = 1;
    		$res['error'] = '购买上限请输入有效数值';
    		return $res;
    	}
    	if ($res['data']['high_pay'] <= 0) {
    		$res['status'] = 1;
    		$res['error'] = '购买上限数值请确保大于0';
    		return $res;
    	}
    	if (!is_numeric($res['data']['increasing_pay'])) {
    		$res['status'] = 1;
    		$res['error'] = '递增金额请输入有效数值';
    		return $res;
    	}
    	if ($res['data']['increasing_pay'] < 0) {
    		$res['status'] = 1;
    		$res['error'] = '递增金额请确保大于或等于0';
    		return $res;
    	}
    	if ($res['data']['increasing_pay'] >= $res['data']['high_pay']) {
    		$res['status'] = 1;
    		$res['error'] = '递增金额不能大于或等于购买上限';
    		return $res;
    	}
    	if (!is_numeric($res['data']['low_pay'])) {
    		$res['status'] = 1;
    		$res['error'] = '起售金额请输入有效数值';
    		return $res;
    	}
    	if ($res['data']['low_pay'] <= 0) {
    		$res['status'] = 1;
    		$res['error'] = '起售金额请确保大于0';
    		return $res;
    	}
    	if ($res['data']['low_pay'] >= $res['data']['high_pay']) {
    		$res['status'] = 1;
    		$res['error'] = '起售金额不能大于或等于购买上限';
    		return $res;
    	}
		if (!is_numeric($res['data']['all_fee'])) {
			$res['status'] = 1;
			$res['error'] = '请输入有效的产品总额度';
			return $res;
		}
		if ($res['data']['all_fee'] < $res['data']['low_pay']) {
			$res['status'] = 1;
			$res['error'] = '产品总额度不能小于起售金额';
			return $res;
		}
		
		if (!is_numeric($res['data']['goods_broratio'])) {
			$res['status'] = 1;
			$res['error'] = '请输入有效的佣金比例';
			return $res;
		}
    	if ($res['data']['is_now'] == 2) {
    		if ($res['data']['start_time'] == '') {
    			$res['status'] = 1;
    			$res['error'] = '请选择产品起息时间';
    			return $res;
    		}
    		if ($res['data']['end_time'] < $res['data']['start_time']) {
    			$res['status'] = 1;
    			$res['error'] = '产品起息时间不要小于产品结息时间';
    			return $res;
    		}
    	}
    	if ($res['data']['deal_date'] <= $res['data']['end_time']) {
    		$res['status'] = 1;
    		$res['error'] = '产品兑付时间不要小于结息时间';
    		return $res;
    	}
    	if ($res['data']['comment'] == '') {
    		$res['status'] = 1;
    		$res['error'] = '请填写产品备注';
    		return $res;
    	}
    	if ($res['data']['deal_way'] == '') {
    		$res['status'] = 1;
    		$res['error'] = '请填写产品兑付方式';
    		return $res;
    	}
    	
    	if($res['data']['is_transfer']==1){
    	    if (($res['data']['distance_order']=='') || ($res['data']['counter_fee'] =='') || ($res['data']['distance_cash']=='')) {
    	        $res['status'] = 1;
    	        $res['error'] = '请将转让参数填写完整';
    	        return $res;
    	    }
    	}
    	
    	
    	if($res['data']['rate_max']<$res['data']['rate_min']){
    	    $res['status'] = 1;
    	    $res['error'] = '转让价率上限不能小于转让价率下限';
    	    return $res;
    	}
    	if($res['data']['distance_order']<0){
    	    $res['status'] = 1;
    	    $res['error'] = '持有天数请确保大于等于0';
    	    return $res;
    	}
    	if($res['data']['distance_cash']<0){
    	    $res['status'] = 1;
    	    $res['error'] = '截止到期天数请确保大于等于0';
    	    return $res;
    	}
    	
    	if (!$res['data']['start_time']) {
    		unset($res['data']['start_time']);
    	}
    	/* 处理添加时间 */
    	if (! $res['data']['goods_id']) {
    		$res['data']['goods_type'] = 1;
    		$res['data']['ctime'] = $res['data']['mtime'];
    		$res['data']['spare_fee'] = $res['data']['all_fee'];
    	} else {
    		$goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$res['data']['goods_id']}");
    		if ($goods) {
    			if ($goods[0]['all_fee'] == $goods[0]['spare_fee']) {
    				$res['data']['spare_fee'] = $res['data']['all_fee'];
    			} else {
    				$res['data']['spare_fee'] = $res['data']['all_fee']-$goods[0]['all_fee']+$goods[0]['spare_fee'];
    				if (($res['data']['spare_fee'] < $goods[0]['low_pay']) && ($res['data']['goods_status'] == 1 )) {
    					$res['status'] = 1;
    					$res['error'] = '该产品剩余额度不足以承担下次交易,请重新选择产品状态';
    					return $res;
    				}
    			}
    		} else {
    			$res['status'] = 1;
    			$res['error'] = '该商品不存在';
    			return $res;
    		}
    	}
    	return $res;
    }
    
    /*
     * 编辑新手产品
     */
    public function editNewGoods()
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    	
    	if ($this->input->isPOST()) {
    		$this->editNewSave();
    		return self::RS_SUCCESS;
    	}
    	$content = Goods_Model_List::instance()->fetchByWhere("goods_pattern = 1 and is_del = 0","ctime desc");
    	if (!$content) {
    		$this->setStatus(1);
    		$this->setError('未找到新手产品');
    		return self::RS_SUCCESS;
    	} else {
    		if ($content[0]['goods_pattern'] != 1 || $content[0]['goods_type'] != 0) {
    			$this->setStatus(1);
    			$this->setError('该产品不属于新手产品,请返回列表后再操作');
    			return self::RS_SUCCESS;
    		}
    		$data['content'] = $content[0];
    	}
    	
    	$this->setData('data', $data);
    	$this->addResult(self::RS_SUCCESS, 'php', 'index.editNewGoods');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /*
     * 编辑体验金产品
     */
    public function editExperienceGoods()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
         
        if ($this->input->isPOST()) {
            $this->editExperienceSave();
            return self::RS_SUCCESS;
        }
        $content = Goods_Model_List::instance()->fetchByWhere("goods_pattern = 4 and is_del = 0","ctime desc");
        if (!$content) {
            $this->setStatus(1);
            $this->setError('未找到体验金产品');
            return self::RS_SUCCESS;
        } else {
            if ($content[0]['goods_pattern'] != 4 || $content[0]['goods_type'] != 0) {
                $this->setStatus(1);
                $this->setError('该产品不属于体验金产品,请返回列表后再操作');
                return self::RS_SUCCESS;
            }
            $data['content'] = $content[0];
        }
         
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.editExperienceGoods');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 编辑体验金产品 - 保存
     */
    public function editExperienceSave()
    {
        $data = array(
            'goods_id' => (int)$this->input->post('goods_id'),
            'goods_name' => trim($this->input->post('goods_name')),
            'goods_detail' => $this->input->post('goods_detail'),
            'safety' => $this->input->post('safety'),
            'goods_pattern' => 4,
            'goods_status' => (int)$this->input->post('goods_status'),
            'debtor_name' => trim($this->input->post('debtor_name')),
            'debtor_card' => trim($this->input->post('debtor_card')),
            'deal_way' => trim($this->input->post('deal_way')),
            'yield' => trim($this->input->post('yield')),
            'high_pay' => trim($this->input->post('high_pay')),
            'low_pay' => trim($this->input->post('low_pay')),
            'all_fee' => trim($this->input->post('all_fee')),
            'increasing_pay' => trim($this->input->post('increasing_pay')),
            'financial_period' => (int)trim($this->input->post('financial_period')),
            'comment' => trim($this->input->post('comment')),
            'redeem_status' => (int)$this->input->post('redeem_status'),
            'principal_status' => (int)$this->input->post('principal_status'),
            'deal_status' => (int)$this->input->post('deal_status'),
            'mtime' => date(DATETIME_FORMAT)
        );
        //     	Zeed_Benchmark::print_r($data);exit;
        if (!is_numeric($data['yield'])) {
            $this->setStatus(1);
            $this->setError('请输入有效的年化收益率');
            return false;
        }
        if (!is_numeric($data['high_pay'])) {
            $this->setStatus(1);
            $this->setError('购买上限请输入有效数值');
            return false;
        }
        if ($data['high_pay'] <= 0) {
            $this->setStatus(1);
            $this->setError('购买上限数值请确保大于0');
            return false;
        }
        if (!is_numeric($data['increasing_pay'])) {
            $this->setStatus(1);
            $this->setError('递增金额请输入有效数值');
            return false;
        }
        if ($data['increasing_pay'] < 0) {
            $this->setStatus(1);
            $this->setError('递增金额请确保大于或等于0');
            return false;
        }
        if ($data['increasing_pay'] >= $data['high_pay']) {
            $this->setStatus(1);
            $this->setError('递增金额不能大于或等于购买上限');
            return false;
        }
        if (!is_numeric($data['financial_period']) || $data['financial_period'] <= 0) {
            $this->setStatus(1);
            $this->setError('理财期限请输入有效数值');
            return false;
        }
        if (!is_numeric($data['low_pay'])) {
            $this->setStatus(1);
            $this->setError('起售金额请输入有效数值');
            return false;
        }
        if ($data['low_pay'] <= 0) {
            $this->setStatus(1);
            $this->setError('起售金额请确保大于0');
            return false;
        }
        if ($data['low_pay'] >= $data['high_pay']) {
            $this->setStatus(1);
            $this->setError('起售金额不能大于或等于购买上限');
            return false;
        }
        if (!is_numeric($data['all_fee'])) {
            $this->setStatus(1);
            $this->setError('总额度请输入有效数值');
            return false;
        }
        if ($data['low_pay'] <= 0) {
            $this->setStatus(1);
            $this->setError('总额度请确保大于0');
            return false;
        }
        if ($data['low_pay'] > $data['all_fee']) {
            $this->setStatus(1);
            $this->setError('总额度必须大于或等于起售金额');
            return false;
        }
         
        if ($data['comment'] == '') {
            $this->setStatus(1);
            $this->setError('请填写产品备注');
            return false;
        }
        if ($data['deal_way'] == '') {
            $this->setStatus(1);
            $this->setError('请填写产品兑付方式');
            return false;
        }
        //     	if (!$data['start_time']) {
        //     		unset($res['data']['start_time']);
        //     	}
        $goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$data['goods_id']}");
        if ($goods) {
            if ($goods[0]['all_fee'] == $goods[0]['spare_fee']) {
                $res['data']['spare_fee'] = $res['data']['all_fee'];
            } else {
                $res['data']['spare_fee'] = $res['data']['all_fee']-$goods[0]['all_fee']+$goods[0]['spare_fee'];
                if (($res['data']['spare_fee'] < $goods[0]['low_pay']) && ($res['data']['goods_status'] == 1 )) {
                    $res['status'] = 1;
                    $res['error'] = '该产品剩余额度不足以承担下次交易,请重新选择产品状态';
                    return $res;
                }
            }
        } else {
            $this->setStatus(1);
            $this->setError('该商品不存在');
            return false;
        }
        if (!Goods_Model_List::instance()->update($data,"goods_id = {$data['goods_id']}")) {
            $this->setStatus(1);
            $this->setError('编辑商品失败。');
            return false;
        } else {
            return true;
        }
         
        $this->setStatus($data['status']);
        $this->setError($data['error']);
        return false;
    }
    
    /**
     * 编辑新手产品 - 保存
     */
    public function editNewSave()
    {
    	$data = array(
    			'goods_id' => (int)$this->input->post('goods_id'),
    			'goods_name' => trim($this->input->post('goods_name')),
    			'goods_detail' => $this->input->post('goods_detail'),
    			'safety' => $this->input->post('safety'),
    			'goods_pattern' => 1,
    			'goods_status' => (int)$this->input->post('goods_status'),
    			'debtor_name' => trim($this->input->post('debtor_name')),
    			'debtor_card' => trim($this->input->post('debtor_card')),
    			'deal_way' => trim($this->input->post('deal_way')),
    			'yield' => trim($this->input->post('yield')),
    			'high_pay' => trim($this->input->post('high_pay')),
    			'low_pay' => trim($this->input->post('low_pay')),
    			'all_fee' => trim($this->input->post('all_fee')),
    			'increasing_pay' => trim($this->input->post('increasing_pay')),
    			'financial_period' => (int)trim($this->input->post('financial_period')),
    			'comment' => trim($this->input->post('comment')),
    			'redeem_status' => (int)$this->input->post('redeem_status'),
    			'principal_status' => (int)$this->input->post('principal_status'),
    			'deal_status' => (int)$this->input->post('deal_status'),
    			'mtime' => date(DATETIME_FORMAT)
    	);
//     	Zeed_Benchmark::print_r($data);exit;
    	if (!is_numeric($data['yield'])) {
    		$this->setStatus(1);
    		$this->setError('请输入有效的年化收益率');
    		return false;
    	}
    	if (!is_numeric($data['high_pay'])) {
    		$this->setStatus(1);
    		$this->setError('购买上限请输入有效数值');
    		return false;
    	}
    	if ($data['high_pay'] <= 0) {
    		$this->setStatus(1);
    		$this->setError('购买上限数值请确保大于0');
    		return false;
    	}
    	if (!is_numeric($data['increasing_pay'])) {
    		$this->setStatus(1);
    		$this->setError('递增金额请输入有效数值');
    		return false;
    	}
    	if ($data['increasing_pay'] < 0) {
    		$this->setStatus(1);
    		$this->setError('递增金额请确保大于或等于0');
    		return false;
    	}
    	if ($data['increasing_pay'] >= $data['high_pay']) {
    		$this->setStatus(1);
    		$this->setError('递增金额不能大于或等于购买上限');
    		return false;
    	}
    	if (!is_numeric($data['financial_period']) || $data['financial_period'] <= 0) {
    		$this->setStatus(1);
    		$this->setError('理财期限请输入有效数值');
    		return false;
    	}
    	if (!is_numeric($data['low_pay'])) {
    		$this->setStatus(1);
    		$this->setError('起售金额请输入有效数值');
    		return false;
    	}
    	if ($data['low_pay'] <= 0) {
    		$this->setStatus(1);
    		$this->setError('起售金额请确保大于0');
    		return false;
    	}
    	if ($data['low_pay'] >= $data['high_pay']) {
    		$this->setStatus(1);
    		$this->setError('起售金额不能大于或等于购买上限');
    		return false;
    	}
    	if (!is_numeric($data['all_fee'])) {
    		$this->setStatus(1);
    		$this->setError('总额度请输入有效数值');
    		return false;
    	}
    	if ($data['low_pay'] <= 0) {
    		$this->setStatus(1);
    		$this->setError('总额度请确保大于0');
    		return false;
    	}
    	if ($data['low_pay'] > $data['all_fee']) {
    		$this->setStatus(1);
    		$this->setError('总额度必须大于或等于起售金额');
    		return false;
    	}
    	
    	if ($data['comment'] == '') {
    		$this->setStatus(1);
    		$this->setError('请填写产品备注');
    		return false;
    	}
    	if ($data['deal_way'] == '') {
    		$this->setStatus(1);
    		$this->setError('请填写产品兑付方式');
    		return false;
    	}
//     	if (!$data['start_time']) {
//     		unset($res['data']['start_time']);
//     	}
    	$goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$data['goods_id']}");
    	if ($goods) {
    		if ($goods[0]['all_fee'] == $goods[0]['spare_fee']) {
    			$res['data']['spare_fee'] = $res['data']['all_fee'];
    		} else {
    			$res['data']['spare_fee'] = $res['data']['all_fee']-$goods[0]['all_fee']+$goods[0]['spare_fee'];
    			if (($res['data']['spare_fee'] < $goods[0]['low_pay']) && ($res['data']['goods_status'] == 1 )) {
    				$res['status'] = 1;
    				$res['error'] = '该产品剩余额度不足以承担下次交易,请重新选择产品状态';
    				return $res;
    			}
    		}
    	} else {
    		$this->setStatus(1);
    		$this->setError('该商品不存在');
    		return false;
    	}
    	if (!Goods_Model_List::instance()->update($data,"goods_id = {$data['goods_id']}")) {
    		$this->setStatus(1);
    		$this->setError('编辑商品失败。');
    		return false;
    	} else {
    		return true;
    	}
    	
    	$this->setStatus($data['status']);
    	$this->setError($data['error']);
    	return false;
    }
    
    /**
     * 商品
     * 支持 AJAX 和 GET 请求删除
     *
     */
    public function publish()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 获取参数，并做基础处理 */
        $goods_id = (int)$this->input->post('goods_id');
        $status = (int)$this->input->query('status');
        if (!$goods_id) {
        	$this->setStatus(1);
        	$this->setError('参数缺失或参数错误');
        	return self::RS_SUCCESS;
        } else {
        	if ($status == 1) {
        		$content = Goods_Model_List::instance()->fetchByWhere("is_del=0 and goods_id={$goods_id}");
        		if ($content[0]['goods_status'] == 2) {
        			$this->setStatus(1);
        			$this->setError('当前产品状态为已售罄,标记为推荐产品失败');
        			return self::RS_SUCCESS;
        		} elseif ($content[0]['goods_status'] == 3) {
        			$this->setStatus(1);
        			$this->setError('当前产品状态为已下架,标记为推荐产品失败');
        			return self::RS_SUCCESS;
        		}
        	}
        	$data['mtime'] = date(DATETIME_FORMAT);
        	$data['is_hot'] = $status;
        	$result = Goods_Model_List::instance()->update($data,"goods_id = {$goods_id}");
        	if (!$result) {
        		$this->setStatus(1);
        		$this->setError('修改状态失败');
        		return self::RS_SUCCESS;
        	}
        }
        
    	$this->setData('data', $list);
        return self::RS_SUCCESS;
    }
    
    /**
     * 产生缩略图(JPEG格式)地址或文件类型图标地址 注意: 缩略图的扩展名可能不代表其真实的MIMEType
     *
     * @param boolean $filepath
     * @param string $mimetype 文件的MIMEType类型
     * @param string $thumbScheme 指定的已配置缩略图方案
     * @param string $urlPrefix 上传目录可访问地址
     * @return string 返回缩略图地址
     */
    protected function _generateThumbnailsUrl($filepath, $mimetype, $thumbScheme, $urlPrefix = null)
    {
        if (substr($mimetype, 0, 6) != 'image/') {
            $configIconsAttachment = Zeed_Config::loadGroup('icon.attachment');
            if (! isset($configIconsAttachment['list'][$mimetype])) {
                $thumbUrl = $configIconsAttachment['default'];
            } else {
                $thumbUrl = $configIconsAttachment['list'][$mimetype];
            }
            return $thumbUrl;
        }
    
        $thumbUrl = '';
        $suffix = substr($filepath, strrpos($filepath, '.'));
        $thumbUrl = str_replace($suffix, '_' . $thumbScheme . $suffix, $filepath);
    
        if (is_null($urlPrefix)) {
            $config = Zeed_Storage::instance()->getConfig();
            $thumbUrl = $config['url_thumb_mng_prefix'] . $thumbUrl;
        } else {
            $thumbUrl = $urlPrefix . $thumbUrl;
        }
    
        return $thumbUrl;
    }
}

// End ^ Native EOL ^ UTF-8