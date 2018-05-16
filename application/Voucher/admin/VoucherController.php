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

class VoucherController extends VoucherAdminAbstract
{
    public $perpage = 15;
    
    /**
     *优惠券列表
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
        	$offset = $page * $perpage;
        	$page = $page + 1;
        	
        	$where ='1=1';
        	
        	$order = 'ctime desc';
        	if ($ordername) {
        		$order = $ordername . " " . $orderby;
        	}
        	
        	$contents = Voucher_Model_Content::instance()->fetchByWhere($where, $order,$perpage,$offset);
        	if(!empty($contents)){
        	    foreach ($contents as $k=>&$v){
    	        switch ($v['disabled']){
    	            case 1:
    	                $v['disabled'] = '未失效';
    	                break;
	                case 2:
	                    $v['disabled'] = '已失效';
	                    break;
    	        }
        	    switch ($v['type']){
        	            case 1:
        	                $v['type'] = '代金券';
        	                break;
    	                case 2:
    	                    $v['type'] = '体验金';
    	                    break;
	                    case 3:
	                        $v['type'] = '加息券';
	                        break;
        	        }
        	        if(empty($v['voucher_money'])){
        	            $v['voucher_money'] ='';
        	        }
        	        if(empty($v['use_money'])){
        	            $v['use_money'] ='';
        	        }
        	    }
        	}
        	$data['count'] = Voucher_Model_Content::instance()->getCount($where);
        	$data['contents'] = $contents ? $contents : array();
        }
		
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'voucher.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加优惠券
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $this->addResult(self::RS_SUCCESS, 'php', 'voucher.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加优惠券保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                unset($set['data']['voucher_id']);
                if(empty($set['data']['order_money'])){
                    $set['data']['order_money'] = null;
                }
                
                if(empty($set['data']['voucher_money'])){
                    $set['data']['voucher_money'] = null;
                }
                
                if(empty($set['data']['increase_interest'])){
                    $set['data']['increase_interest'] = null;
                }
                if(empty($set['data']['use_money'])){
                    $set['data']['use_money'] = null;
                }
                
                $voucher_id = Voucher_Model_Content::instance()->addForEntity($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加优惠券失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    /**
     * 编辑优惠券
     */
    public function edit()
    {
          $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $voucher_id = (int) $this->input->query('voucher_id');
        
        /* 查询代金券信息 */
        if (! $voucher = Voucher_Model_Content::instance()->fetchByPK($voucher_id)) {
            $this->setStatus(1);
            $this->setError('查无此优惠券');
            return self::RS_SUCCESS;
        }
        $voucher = $voucher[0];
        
        if(!empty($voucher['voucher_type'])){
            $voucher['voucher_type'] = explode(',', $voucher['voucher_type']);
            
            if(!empty($voucher['voucher_type'])){
                foreach ($voucher['voucher_type'] as $k=>&$v){
                    switch ($v){
                        case 1:
                            $voucher['voucher_type1'] = 1;
                            break;
                        
                        case 2:
                            $voucher['voucher_type2'] = 2;
                            break;
                        case 3:
                            $voucher['voucher_type3'] = 3;
                            break;
                    }
                }
            }
        }
        
        
        $data['voucher_id'] = $voucher_id;
        $data['content'] = $voucher;
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'voucher.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 编辑保存优惠券
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            $voucher_id = $set['data']['voucher_id'];
            $where = "voucher_id = {$voucher_id}";
            unset($set['data']['type']);
            if(empty($set['data']['order_money'])){
                $set['data']['order_money'] = null;
            }
            
            if(empty($set['data']['voucher_money'])){
                $set['data']['voucher_money'] = null;
            }
            
            if(empty($set['data']['increase_interest'])){
                $set['data']['increase_interest'] = null;
            }
            
            if(empty($set['data']['use_money'])){
                $set['data']['use_money'] = null;
            }

            if (!Voucher_Model_Content::instance()->update($set['data'], $where)) {
                $this->setStatus(1);
                $this->setError('编辑失败!');
                return false;
            }else{
                $where = "voucher_id = {$voucher_id}";
                $arr = array(
                        'valid_data' => date('Y-m-d',strtotime("+{$set['data']['valid_data']} day")),
                );
                Cas_Model_User_Voucher::instance()->update($arr,$where);
            }
            return true;
        }
        
        return false;
    }
    
    /**
     * 校验数据
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $res['data'] = array(
                'voucher_id' => (int)$this->input->post('voucher_id',0),
                'voucher_money' => $this->input->post('voucher_money'),
                'use_money' => $this->input->post('use_money'),
                'valid_data' => $this->input->post('valid_data'),
                'disabled' => $this->input->post('disabled'),
                'ctime' => date(DATETIME_FORMAT),
                'voucher_type'=> $this->input->post('voucher_type'),
                'to_recommender'=> $this->input->post('to_recommender'),
                'order_money'=> $this->input->post('order_money'),
                'increase_interest'=> floatval($this->input->post('increase_interest')),
                'type'=> $this->input->post('type'),
        );
        
        if(is_array($res['data']['voucher_type']) && !empty($res['data']['voucher_type'])){
            $res['data']['voucher_type'] = implode(',', $res['data']['voucher_type']);
        }
        
        if (!empty($res['data']['voucher_id'])) {
            $res['data']['mtime'] = date(DATETIME_FORMAT);
        }else{
            $res['data']['mtime'] = $res['data']['ctime'];
        }
        return $res;
    }
}

// End ^ Native EOL ^ UTF-8