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

class IndexController extends BrokerageAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 佣金列表管理
     */
    public function index ()
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
    
            $where[] = "1 = 1";
            if ($key) {
                $where[] = "username LIKE '%{$key}%'";
            }
    
            $order = 'order_time DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
    
            $brokerage = Cas_Model_User_Brokerage::instance()->fetchByWhere($where, $order, $perpage, $offset);
            if(!empty($brokerage)){
                foreach($brokerage as $k=>&$v){
                    /* 客户姓名  */
                    if(!empty($v['userid'])){
                        $user = Cas_Model_User::instance()->fetchByWhere("userid = {$v['userid']}");
                        $v['username'] = $user[0]['username'];
                    }
                    /* 产品名称  */
                    if(!empty($v['userid'])){
                        $order = Bts_Model_Order::instance()->fetchByWhere("order_id = '{$v['order_id']}'");
                        $v['goods_name'] = $order[0]['goods_name'];
                        $v['order_no'] = $order[0]['order_no'];
                    }
                }
            }
            $data['count'] = Cas_Model_User_Brokerage::instance()->getCount($where);
            $data['brokerage'] = $brokerage ? $brokerage : array();
        }
    
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    
    /**
     * 佣金比例设定
     */
    public function setting()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where[] = "1=1";
        $content = Brokerage_Model_Setting::instance()->fetchByWhere($where);
        $data['content'] = $content[0] ? $content[0] : array();
    	$this->setData('data', $data);
    	$this->addResult(self::RS_SUCCESS, 'php', 'setting.edit');
    	return parent::multipleResult(self::RS_SUCCESS);
    }
    
    
    public function settingAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if ($this->input->isPOST()) {
            $this->settingAddSave();
            return self::RS_SUCCESS;
        }
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
  
    /**
     * 添加 - 保存
     */
    public function settingAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                      $arr  =array(
                           'first_brokerage' =>$set['data']['first_brokerage'],
                           'second_brokerage' =>$set['data']['second_brokerage'],
                           'third_brokerage' =>$set['data']['third_brokerage'],
                       );
                      Brokerage_Model_Setting::instance()->update($arr, null, null, null);
                
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add brokerage failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    private function _validate ()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $res['data'] = array(
                'first_brokerage' =>$this->input->post('first_brokerage'),
                'second_brokerage' =>$this->input->post('second_brokerage'),
                'third_brokerage' =>$this->input->post('third_brokerage'),
        );
        return $res;
    }
    
    /**
     * 结算
     */
    public function settlement()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $brokerage_id = (int) $this->input->get('brokerage_id');
        
        /* 查询佣金信息*/
        $brokerage = Cas_Model_User_Brokerage::instance()->fetchByWhere("brokerage_id={$brokerage_id}");
        
        /*处理佣金状态为已结算*/
        $arr = array(
                'brokerage_status' => 2,
                'mtime' => date(DATETIME_FORMAT)
        );
        $result = Cas_Model_User_Brokerage::instance()->update($arr,"brokerage_id = {$brokerage_id}");
        
        if($result){
            $user = Cas_Model_User::instance()->fetchByWhere("userid = {$brokerage[0]['userid']}");
            
            /* 结算成功  处理账户余额 总收益*/
            if(!empty($brokerage)){
                if(!empty($user)){
                    $asset = $brokerage[0]['expected_money'] + $user[0]['asset'];
                    $earnings = $brokerage[0]['expected_money'] + $user[0]['earnings'];
                    $arrs = array(
                            'asset' => $asset,
                            'earnings' => $earnings
                    );
                    $results = Cas_Model_User::instance()->update($arrs, "userid = {$brokerage[0]['userid']}");
                    
                    /* 记录到资金明细表*/
                    if($results){
                        $user = Cas_Model_User::instance()->fetchByWhere("userid = {$brokerage[0]['userid']} and status = 0");
                        $dd['order_id'] = $brokerage[0]['order_id'];
                        $dd['order_no'] = $brokerage[0]['order_no'];
                        $dd['flow_asset'] = $user[0]['asset'];
                        $dd['userid'] = $brokerage[0]['userid'];
                        $dd['money'] = $brokerage[0]['expected_money'];
                        $dd['interest_time'] = date("Y-m-d",strtotime($brokerage[0]['order_time']));
                        $dd['status'] = "+";
                        $dd['ctime'] = date(DATETIME_FORMAT);
                        $dd['pay_type'] = 3; //线下支付
                        $dd['type'] = 5;  //产品佣金
                        Cas_Model_Record_Log::instance()->addForEntity($dd);
                    }
                }

            }
        }
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 批量结算
     */
    public function allSettlement()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $brokerage_id = $this->input->query('brokerage_id');
        $brokerage_id = explode(',',$brokerage_id);
        
        /*处理佣金状态为已结算*/
        $arr = array(
                'brokerage_status' => 2,
                'mtime' => date(DATETIME_FORMAT)
        );
        foreach($brokerage_id as $k=> &$v){
            
            /* 查询佣金信息*/
            $brokerage = Cas_Model_User_Brokerage::instance()->fetchByWhere("brokerage_id={$v}");
            
            /*更新状态*/
            $result = Cas_Model_User_Brokerage::instance()->update($arr,"brokerage_id = {$v}");
            
            if($result){
                
                $user = Cas_Model_User::instance()->fetchByWhere("userid = {$brokerage[0]['userid']}");
            
                /* 结算成功  处理账户余额 总收益*/
                if(!empty($brokerage)){
                    if(!empty($user)){
                        $asset = $brokerage[0]['expected_money'] + $user[0]['asset'];
                        $earnings = $brokerage[0]['expected_money'] + $user[0]['earnings'];
                        $arrs = array(
                                'asset' => $asset,
                                'earnings' => $earnings
                        );
                        $results = Cas_Model_User::instance()->update($arrs, "userid = {$brokerage[0]['userid']}");
                        
                        /* 记录到资金明细表*/
                        if($results){
                            $user = Cas_Model_User::instance()->fetchByWhere("userid = {$brokerage[0]['userid']} and status = 0");
                            $dd['order_id'] = $brokerage[0]['order_id'];
                            $dd['order_no'] = $brokerage[0]['order_no'];
                            $dd['flow_asset'] = $user[0]['asset'];
                            $dd['userid'] = $brokerage[0]['userid'];
                            $dd['money'] = $brokerage[0]['expected_money'];
                            $dd['interest_time'] = date("Y-m-d",strtotime($brokerage[0]['order_time']));
                            $dd['status'] = "+";
                            $dd['ctime'] = date(DATETIME_FORMAT);
                            $dd['pay_type'] = 3; //线下支付
                            $dd['type'] = 5;  //产品佣金
                            Cas_Model_Record_Log::instance()->addForEntity($dd);
                        }
                    }
            
                }
            }
        }
        if (! empty($result) ) {
            $this->setError('批量结算成功');
        } else {
            $this->setStatus(1);
            $this->setError('批量结算失败 ');
            return self::RS_SUCCESS;
        }
    
        return self::RS_SUCCESS;
    }
    
    /**
     * 拒绝
     */
    public function refused()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $brokerage_id = (int) $this->input->get('brokerage_id');
        
        /*处理佣金状态为已拒绝*/
        $arr = array(
                'brokerage_status' => 3,
        );
        Cas_Model_User_Brokerage::instance()->update($arr,"brokerage_id = {$brokerage_id}");
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 批量拒绝
     */
    public function allRefused()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $brokerage_id = $this->input->query('brokerage_id');
        $brokerage_id = explode(',',$brokerage_id);
        
        /*处理佣金状态为已拒绝*/
        $arr = array(
                'brokerage_status' => 3,
        );
        foreach($brokerage_id as $k=> &$v){
            $result = Cas_Model_User_Brokerage::instance()->update($arr,"brokerage_id = {$v}");
        }
        
        if (! empty($result) ) {
            $this->setError('批量拒绝成功');
        } else {
            $this->setStatus(1);
            $this->setError('批量拒绝失败 ');
        }
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8