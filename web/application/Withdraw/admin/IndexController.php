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
class IndexController extends WithdrawAdminAbstract
{
    public $perpage = 15;

    /**
     * 提现列表管理
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $phone = trim($this->input->get('phone'));
        $withdraw_status = (int)$this->input->get('withdraw_status');
        $username = $this->input->get('username');
        $idcard = $this->input->get('idcard',0);
        $start_time = $this->input->get('start_time');
        $end_time = $this->input->get('end_time');
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
        
            $where = "1 = 1";
            if ($withdraw_status) {
                $where .= " and withdraw_status = {$withdraw_status}";
            }
            if ($phone) {
                $where .= " and phone = '{$phone}'";
            }
            if (isset($start_time) && $start_time != null) {
                $where .= " and ctime >= '" . $start_time . "'";
            }
            if (isset($end_time) && $end_time != null) {
                $where .= " and ctime <= '" . $end_time . "'";
            }
            
            
                $wheres = "status = 0";
                $order = "ctime DESC";
                /* 搜索用户名 */
                if ($username) {
                    $wheres .= " and username like '%".$username."'";
                }
                if ($idcard) {
                    $wheres .= " and idcard = {$idcard}";
                }
                
                if(!empty($wheres)){
                    $user = Cas_Model_User::instance()->fetchByWhere($wheres);
                    $arr = array();
                    if ($user) {
                        foreach ($user as $k => $v) {
                            $arr[] = $v['userid'];
                        }
                    }
                    if (!empty($arr)) {
                        $arr = implode(',',$arr);
                        $where .= " and userid in (".$arr.")";
                    } else {
                        $where .= " and userid in (0)";
                    }
                }
            
            
            /* 查询提现想详情  */
            $order = 'withdraw_list.ctime DESC';
            $withdraw = Withdraw_Model_List::instance()->fetchByWhere($where, $order, $perpage, $offset,$cols);
            if ($withdraw) {
                foreach ($withdraw as $k => &$v) {
                    $userinfo = Cas_Model_User::instance()->fetchByWhere("userid = {$v['userid']}");
                    $v['username'] = $userinfo[0]['username'];
                }
            }
            
            $data['count'] = Withdraw_Model_List::instance()->getCount($where);
            $data['withdraw'] = $withdraw ? $withdraw : array();
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
     * 保存广告 － 校验
     */
    private function _validate ()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $res['data'] = array(
                'content_id' => (int)$this->input->post('content_id', 0),
                'type' => $this->input->post('type'),
                'title' => $this->input->post('title'),
		        'link_url' => $this->input->post('link_url'),
                'image' => $_FILES['image'],
                'count' => $this->input->post('count'),
		        'sort_order' => $this->input->post('sort_order'),
		        'status' => $this->input->post('status', 1),
		        'mtime' => date(DATETIME_FORMAT)
        );
        
    	if (! $res['data']['type']) {
            $res['status'] = 1;
            $res['error'] = '请选择广告类型';
            return $res;
        }
        
    	if (! $res['data']['title']) {
            $res['status'] = 1;
            $res['error'] = '广告名称不能为空';
            return $res;
        }
        
        /* 处理添加时间 */
        if (! $res['data']['content_id']) {
            $res['data']['ctime'] = $res['data']['mtime'];
        }
        
        return $res;
    }
    
    /**
     * 提现详情
     */
    public function detail()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        $withdraw_id = (int) $this->input->get('withdraw_id');
        $withdraw = Withdraw_Model_List::instance()->fetchByPK($withdraw_id);
        $withdraw = $withdraw[0];
    
        /* 用户名  */
        if(!empty($withdraw['userid'])){
            $where = "userid = {$withdraw['userid']}";
            $user = Cas_Model_User::instance()->fetchByWhere($where);
        }
        $withdraw['username'] = $user[0]['username'];
        $withdraw['idcard'] = $user[0]['idcard'];
        $withdraw['userctime'] = $user[0]['ctime'];
        if($user[0]['is_ecoman']==0){
            $withdraw['is_ecoman'] = '否'; 
        }else{
            $withdraw['is_ecoman'] = '是';
        }
        
        /* 银行卡信息  */
        if($user[0]['bank_id']){
            $bank = Cas_Model_Bank::instance()->fetchByWhere("bank_id = {$user[0]['bank_id']}");
            if(!empty($bank)){
                $withdraw['card'] = $bank[0]['bank_name'].$bank[0]['subbank_name'].$bank[0]['bank_no'];
            }
        }
        $data['withdraw'] = $withdraw;
        $this->setData('data', $data);
    
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_SUCCESS, 'php', 'index.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /*提交*/
    public function deal()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 获取参数，并做基础处理 */
        $withdraw_id = $this->input->post('withdraw_id', 0);
        $withdraw_status = $this->input->post('withdraw_status');
        $remark = $this->input->post('remark', null);
        $platform_number = $this->input->post('platform_number', null);
        
        if (!empty($withdraw_id)) {
            $data = array(
                    'withdraw_status' => $withdraw_status,    //提现状态
                    'remark' => $remark,     //备注
                    'platform_number' => $platform_number,   //平台流水号
            );
            
            $result = Withdraw_Model_List::instance()->update($data, "withdraw_id = {$withdraw_id}");
            if($result && $withdraw_status == 3){ // 提现失败  处理账户余额
                   $withdraw =  Withdraw_Model_List::instance()->fetchByWhere("withdraw_id = {$withdraw_id}");
                   $user = Cas_Model_User::instance()->fetchByWhere("userid = {$withdraw[0]['userid']}");
                   $arr['asset'] = $user[0]['asset'] + $withdraw[0]['withdraw_money'];
                   Cas_Model_User::instance()->update($arr, "userid = {$withdraw[0]['userid']}");
                   
                   /* 清除 资金明细表的记录  */
                   $where = "ctime = '{$withdraw[0]['ctime']}' AND userid = {$withdraw[0]['userid']}";
                   Cas_Model_Record_Log::instance()->delete($where);
            }
        }
        return self::RS_SUCCESS;
    }
    
    
    /**
     * 批量提现
     */
    public function allSettlement()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $withdraw_id = $this->input->query('withdraw_id');
        $withdraw_id = explode(',',$withdraw_id);
        /*处理提现记录为提现成功*/
        $arr = array(
                'withdraw_status' => 2,//提现成功
        );
        foreach($withdraw_id as $k=> &$v){
            if($v){
                
                /*处理重复操作*/
                $withdraw = Withdraw_Model_List::instance()->fetchByWhere("withdraw_id = {$v}");
                if($withdraw[0]['withdraw_status'] == 2 ){
                    $this->setStatus(1);
                    $this->setError('已提现成功 不能重复操作！ ');
                    return self::RS_SUCCESS;
                }
                /*更新状态*/
                $result = Withdraw_Model_List::instance()->update($arr,"withdraw_id = {$v}");
            }
        }
        if (! empty($result) ) {
            $this->setError('批量提现成功');
        } else {
            $this->setStatus(1);
            $this->setError('批量提现失败 ');
            return self::RS_SUCCESS;
        }
    
        return self::RS_SUCCESS;
    }
    
    
    /**
     * 批量失败
     */
    public function allRefused()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $withdraw_id = $this->input->query('withdraw_id');
        $withdraw_id = explode(',',$withdraw_id);
    
        /*处理提现记录为提现失败*/
        $arr = array(
                'withdraw_status' => 3,
        );
        foreach($withdraw_id as $k=> &$v){
            
            /*处理重复操作*/
            $withdraw = Withdraw_Model_List::instance()->fetchByWhere("withdraw_id = {$v}");
            if($withdraw[0]['withdraw_status'] == 3){
                $this->setStatus(1);
                $this->setError('已处理成提现失败 不能重复操作！ ');
                return self::RS_SUCCESS;
            }
            $result = Withdraw_Model_List::instance()->update($arr,"withdraw_id = {$v}");
            
        }
    
        if (! empty($result) ) {
            $this->setError('批量提现失败成功');
        } else {
            $this->setStatus(1);
            $this->setError('批量提现失败失败 ');
        }
        return self::RS_SUCCESS;
    }
    
    /* 导出  */
    public function exportExchangeList ()
    {
        $order = 'ctime ASC';
    
        /* 查询分类信息 */
        $withdraw = Withdraw_Model_List::instance()->fetchByWhere($where, $order, $perpage, $offset);
            if(!empty($withdraw)){
                foreach($withdraw as $k=>&$v){
                    if(!empty($v['userid'])){
                        $user = Cas_Model_User::instance()->fetchByWhere("userid = {$v['userid']}");
                        $v['userid'] = $user[0]['username'];
                    }
                    
                    /* 处理提现状态  */
                    if($v['withdraw_status'] == '2'){
                        $v['withdraw_status'] = '提现成功';
                    }elseif($v['withdraw_status'] == '3'){
                        $v['withdraw_status'] = '提现失败';
                    }else{
                        $v['withdraw_status'] = '未处理';
                    }
                }
            }
    
        /**
         * 临时变量 用来处理XLS表格第一个字段 序号
         */
        $i = 1;
        if(! empty($data['$withdraw'])){
            foreach ($data['$withdraw'] as $k => $v) {
                // 数组第一个元素加上序号
                array_unshift($data['$withdraw'][$k],$i);
                $i++;
            }
        }
         
        $input['list'] = $withdraw;
        /* xls 标题列 */
        $input['cols_name'] = array('编号','所属用户','手机号码','所属银行','开户行','银行账号','申请金额','提现手续费','实际提现金额','账户余额','平台流水号','状态','备注','提现时间');
        $input['title'] = '提现列表管理';
        $input['filename'] = '提现列表数据导出';
        $input['width'] = array('G' => '8', 'H' => '8', 'I' => '8', 'J' => '30', 'L' => '19');
        Widget_PhpExcel_Api_Write::downToExcel($input);
    }
}

// End ^ Native EOL ^ UTF-8