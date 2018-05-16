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
class IndexController extends IndexAbstract {
	
    /**
     * 商铺前台首页
     */
    public function index ()
    {
      
       // 如果没有用户信息 则跳转登录界面
        if (!$userid = Cas_Authorization::getLoggedInUserid()) {
              Zeed_Util_Redirector::factory('header', '/cas/sign/in', 0)->output();
        }
        // 组织查询条件
        $where =  array('userid' => $userid);
        // 获取店铺信息 
        $store = Store_Model_Content::instance()->fetchByWhere($where);
        
        $data['store'] = $store ? $store[0]: '';
        $this->setData ( 'data', $data );
        $this->addResult ( self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult ( self::RS_SUCCESS );
    }
    
    /**
     * 修改店铺
     * 
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');
        // 如果没有用户信息 则跳转登录界面
        if (!$userid = Cas_Authorization::getLoggedInUserid()) {
            Zeed_Util_Redirector::factory('header', '/cas/sign/in', 0)->output();
        }
        // 组织查询条件
        $where =  array('userid' => $userid);
        // 获取店铺信息
        $store = Store_Model_Content::instance()->fetchByWhere($where);
        
        if (empty($store)) {
            header ( "Content-type: text/html; charset=utf-8" );
            exit ( '请先申请成为店铺！' );
        }
        
        
        if ($this->input->isPOST()) {
        	$this->editSave();
        	return self::RS_SUCCESS;
        }
        
        
        $data['store'] = $store ? $store[0]: '';
        $this->setData ( 'data', $data );
        $this->addResult ( self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult ( self::RS_SUCCESS );
        
    }
    
    /**
     * 修改保存方法
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                
                foreach ($set['data'] as $k => $v) {
                    if (empty($set['data'][$k])) {
                        unset($set['data'][$k]);
                    }
                }

                /* 更新商户信息 */
                Store_Model_Content::instance()->editStore($set['data']);

        
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑商户失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 申请成为店铺
     */
    public function apply()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        // 如果没有用户信息 则跳转登录界面
        if (!$userid = Cas_Authorization::getLoggedInUserid()) {
            Zeed_Util_Redirector::factory('header', '/cas/sign/in', 0)->output();
        }
                
        if ($this->input->isPOST()) {
        	$this->applySave();
        	return self::RS_SUCCESS;
        }
        
        // 组织查询条件
        $where =  array('userid' => $userid);
        // 获取店铺信息
        $store = Store_Model_Content::instance()->fetchByWhere($where);
        $data['store'] = $store ? $store[0]: '';
        $this->setData ( 'data', $data );
        $this->addResult ( self::RS_SUCCESS, 'php', 'index.apply');
        return parent::multipleResult ( self::RS_SUCCESS );
    }
    
    
    /**
     * 申请商铺 - 保存
     */
    public function applySave ()
    {
    	$set = $this->_validate();
    
    	if ($set['status'] == 0) {    
    
    		try {
    			    
    			if (! $store_id = Store_Model_Content::instance()->addStore($set['data'])) {
    				throw new Zeed_Exception('添加商店失败');
    			}
    
    		} catch (Zeed_Exception $e) {
    			$this->setStatus(1);
    			$this->setError('添加商店失败 : ' . $e->getMessage());
    			return false;
    		}
    		return true;
    	}
    
    	$this->setStatus($set['status']);
    	$this->setError($set['error']);
    	return false;
    }
    
    
    
    /**
     * 商铺申请记录时间线
     */
    public function timeLine()
    {
        
        // 如果没有用户信息 则跳转登录界面
        if (!$userid = Cas_Authorization::getLoggedInUserid()) {
        	Zeed_Util_Redirector::factory('header', '/cas/sign/in', 0)->output();
        }
        
        $store = Store_Model_Content::instance()->fetchByFV("userid", $userid);
      
        
        // 如果商铺ID存在的话
        if (empty($store)) {
            header ( "Content-type: text/html; charset=utf-8" );
            exit ( '这里是404错误页面！' );
        } 
        
        $data['store'] = $store[0];
        
        
        $this->setData ( 'data', $data );
        $this->addResult ( self::RS_SUCCESS, 'php', 'index.timeline' );
        return parent::multipleResult ( self::RS_SUCCESS );
        
    }
    
    
    /**
     * 保存－校验
     */
    private function _validate ()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $res['data'] = array('store_id' => $this->input->post('store_id', 0), 
                'store_name' => $this->input->post('store_name', ""), 
                'logo' => empty($_FILES['logo'])? null : $_FILES['logo'], 
                'setup_date' => $this->input->post('setup_date', "0000-00-00"),
                'register_capital' => $this->input->post('register_capital', 0), 
                'employee_nums' => $this->input->post('employee_nums', 0), 
                'region_id' => $this->input->post('region_id', 0), 
                'region_name' => $this->input->post('region_name', 0), 
                'address' => $this->input->post('address', ""), 
                'address_return' => $this->input->post('address_return', 0), 
                'return_rule' => $this->input->post('return_rule', 0), 
                'business_time' => $this->input->post('business_time', 0), 
                'run_category' => $this->input->post('run_category', 0), 
                'tel' => $this->input->post('tel', 0), 
                'fax' => $this->input->post('fax', 0), 
                'homepage' => $this->input->post('homepage', 0), 
                'email' => $this->input->post('email', 0), 
                'image_default' => $this->input->post('image_default', 0), 
                'business_license' => $this->input->post('business_license', 0), 
                'business_image' => empty($_FILES['business_image'])? null : $_FILES['business_image'], 
                'business_categoryids' => $this->input->post('business_categoryids', 0), 
                'legalp_name' => $this->input->post('legalp_name', 0), 
                'description' => $this->input->post('description', 0), 
                'is_official' => $this->input->post('is_official', 0), 
                'source' => $this->input->post('source', 0), 
                'is_signing' => empty($this->input->post('is_signing'))? 0 : 1, 
                'signing_start_time' => $this->input->post('signing_start_time',"0000-00-00"), 
                'signing_end_time' => $this->input->post('signing_end_time',"0000-00-00"),                
                'status' => $this->input->post('status', 0), 
                'rejection_reason' => $this->input->post('rejection_reason', ''), 
                'is_verify' => $this->input->post('is_verify', 0), 
                'ctime' => date(DATETIME_FORMAT), 
                'mtime' => date(DATETIME_FORMAT), 
                'verify_time' => '', 
                'is_free' => $this->input->post('is_free', 0)
                
                
              );
        
        /*
         * 数据验证
         */
        if (empty($res['data']['store_name'])) {
            $res['status'] = 1;
            $res['error'] = '请填写商店名称';
            return $res;
        }
       
        
         
     //添加时要校验是否有管理员账号和密码信息
        if (empty($res['data']['store_id'])) {            
            /*
             * 检查店铺是否存在
            */
            $store = Store_Model_Content::instance()->fetchByFV("store_name", $res['data']['store_name']) ;
            if ($store) {
            	$res['status'] = 1;
                $res['error'] = '店铺名称已存在';
                return $res;
            }            
        }
        else {
            /*
             * 检查店铺是否存在
            */
            $store = Store_Model_Content::instance()->fetchByWhere(" store_name = '".$res['data']['store_name']."'  and store_id != {$res['data']['store_id']} ") ;
            if ($store) {
            	$res['status'] = 1;
            	$res['error'] = '店铺名称已存在';
            	return $res;
            }
        }
        
        
        $userid = Cas_Authorization::getLoggedInUserid();
        $res['data']['userid'] = $userid;
        
        return $res;
    }
    
    /**
     * 写入LOG公用方法
     */
    
    private function writeLog()
    {
        
    }
}

// End ^ Native EOL ^ UTF-8