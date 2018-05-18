<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */
class IndexController extends VoucherAdminAbstract
{
    public $perpage = 15;

    /**
     * 代金券明细
     */
    public function index ()
    {
        
      /*    $title = "测试";
        $message = "hehe";
        $a = self::JPush($title, $message, '170976fa8a80f97b52e', null, null, "addlog",3);
        
        var_dump($a);  */
        
     /*     $registration_id = '170976fa8a80f97b52e';
        $content = 'hehe';
        $title = 'ceshi2';
        
        $result = Push_Jpush::sendRegistrationId($registration_id,$content,$title,$extras = array());
        var_dump($result);  */
        
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        $phone = trim($this->input->get('phone'));
        $voucher_status = (int)$this->input->get('voucher_status');
        $username = $this->input->get('username');
        $idcard = $this->input->get('idcard',0);
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            
            $offset = $page * $perpage;
            $page = $page + 1;
            
            $where = "type=1";
            if ($voucher_status) {
                $where .= " and voucher_status = {$voucher_status}";
            }
            
            $wheres = "status = 0";
            /* 搜索用户名 */
            if ($username) {
                $wheres .= " and username like '%".$username."'";
            }
            if ($idcard) {
                $wheres .= " and idcard = {$idcard}";
            }
            
            if ($phone) {
                $wheres .= " and phone = '{$phone}'";
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
            
            $order = 'use_time DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
            
            $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere($where, $order, $perpage, $offset);
            if(!empty($voucher)){
                foreach($voucher as $k=>&$v){ //1： 未使用；2：已使用 3：已过期
                    if($v['voucher_status']==1){
                        $v['voucher_status'] = '未使用';
                    }elseif($v['voucher_status'] == 2){
                        $v['voucher_status'] = '已使用';
                    }elseif($v['voucher_status'] == 3){
                        $v['voucher_status'] = '已过期';
                    }
                    
                    if(empty($v['order_id'])){
                        $v['order_id'] = '';
                    }
                    
                    if(empty($v['use_time'])){
                        $v['use_time'] = '';
                    }
                }
            }
            $data['count'] = Cas_Model_User_Voucher::instance()->getCount($where);
            
            $data['voucher'] = $voucher ? $voucher : array();
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
     * 体验金明细
     */
    public function experience()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        /* 接收参数 */
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        $phone = trim($this->input->get('phone'));
        $voucher_status = (int)$this->input->get('voucher_status');
        $username = $this->input->get('username');
        $idcard = $this->input->get('idcard',0);
    
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
    
            $offset = $page * $perpage;
            $page = $page + 1;
    
            $where = "type=2";
            if ($voucher_status) {
                $where .= " and voucher_status = {$voucher_status}";
            }
    
            $wheres = "status = 0";
            /* 搜索用户名 */
            if ($username) {
                $wheres .= " and username like '%".$username."'";
            }
            if ($idcard) {
                $wheres .= " and idcard = {$idcard}";
            }
    
            if ($phone) {
                $wheres .= " and phone = '{$phone}'";
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
    
            $order = 'use_time DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
    
            $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere($where, $order, $perpage, $offset);
            if(!empty($voucher)){
                foreach($voucher as $k=>&$v){ //1： 未使用；2：已使用 3：已过期
                    if($v['voucher_status']==1){
                        $v['voucher_status'] = '未使用';
                    }elseif($v['voucher_status'] == 2){
                        $v['voucher_status'] = '已使用';
                    }elseif($v['voucher_status'] == 3){
                        $v['voucher_status'] = '已过期';
                    }elseif(empty($v['voucher_status'])){
                        $v['voucher_status'] = '';
                    }
                    
                    if(empty($v['start_data'])){
                        $v['start_data'] = '';
                    }
                    if(empty($v['order_id'])){
                        $v['order_id'] = '';
                    }
                    
                    if(empty($v['use_time'])){
                        $v['use_time'] = '';
                    }
                    
                    if($v['is_manager']==1){
                        $v['voucher_money'] = $v['money_remarks'];
                    }
                }
            }
            $data['count'] = Cas_Model_User_Voucher::instance()->getCount($where);
    
            $data['voucher'] = $voucher ? $voucher : array();
        }
    
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'experience.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 加息券明细
     */
    public function interest()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        /* 接收参数 */
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        $phone = trim($this->input->get('phone'));
        $voucher_status = (int)$this->input->get('voucher_status');
        $username = $this->input->get('username');
        $idcard = $this->input->get('idcard',0);
    
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
    
            $offset = $page * $perpage;
            $page = $page + 1;
    
            $where = "type=3";
            if ($voucher_status) {
                $where .= " and voucher_status = {$voucher_status}";
            }
    
            $wheres = "status = 0";
            /* 搜索用户名 */
            if ($username) {
                $wheres .= " and username like '%".$username."'";
            }
            if ($idcard) {
                $wheres .= " and idcard = {$idcard}";
            }
    
            if ($phone) {
                $wheres .= " and phone = '{$phone}'";
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
    
            $order = 'use_time DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
    
            $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere($where, $order, $perpage, $offset);
            if(!empty($voucher)){
                foreach($voucher as $k=>&$v){ //1： 未使用；2：已使用 3：已过期
                    if($v['voucher_status']==1){
                        $v['voucher_status'] = '未使用';
                    }elseif($v['voucher_status'] == 2){
                        $v['voucher_status'] = '已使用';
                    }elseif($v['voucher_status'] == 3){
                        $v['voucher_status'] = '已过期';
                    }
                    
                    if(empty($v['order_id'])){
                        $v['order_id'] = '';
                    }
                    
                    if(empty($v['use_time'])){
                        $v['use_time'] = '';
                    }
                }
            }
            $data['count'] = Cas_Model_User_Voucher::instance()->getCount($where);
    
            $data['voucher'] = $voucher ? $voucher : array();
        }
    
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'interest.index');
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
    
        $this->addResult(self::RS_SUCCESS, 'php', 'experience.add');
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
                $id = Cas_Model_User_Voucher::instance()->addForEntity($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加体验金失败 : ' . $e->getMessage());
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
            'phone' => $this->input->post('phone'),
            'voucher_money' => $this->input->post('voucher_money'),
            'use_money' => $this->input->post('use_money'),
            'creat_time' => date(DATETIME_FORMAT),
            'type'=> 2,
            'is_manager'=>1,
            'start_data' => $this->input->post('start_time'),
            'valid_data' => $this->input->post('end_time'),
            'voucher_status'=>1,
        );
        
        if(!empty($res['data']['phone'])){
            $user = Cas_Model_User::instance()->fetchByFv('phone',$res['data']['phone']);
            if(!empty($user)){
                unset($res['data']['phone']);
                $res['data']['userid'] = $user[0]['userid'];
            }else{
                $res['status'] = 1;
                $res['error'] = '手机号不存在';
                return $res;
            }
        }
        
        if($res['data']['voucher_money']<$res['data']['use_money']){
            $res['status'] = 1;
            $res['error'] = '金额输入不对';
            return $res;
        }
        
        if(strtotime($res['data']['start_data'])>strtotime($res['data']['valid_data'])){
            $res['status'] = 1;
            $res['error'] = '时间选择不对';
            return $res;
        }
        $res['data']['money_remarks'] = $res['data']['voucher_money'];
        
        return $res;
    }
    
    /**
     * 极光推送
     * @param string $title   推送标题
     * @param string $message 推送内容
     * @param array  $userids 推送alias
     * @return $result
     */
    public static function JPush($title, $message, $userids, $appkey = null, $masterSecret = null, $type = null, $status = null)
    {
        //构造notification
        $notification = new Widget_JPush_Api_Class_Notification();
        $notification->setTitle($title);
        $notification->setAlert($message);
        $notification->setBadge(1);
        $notification->setSound("default");
         
        $notification->setStatus($status);
        //构造option
        $option = new Widget_JPush_Api_Class_Options();
        $option->setApns_production(true);    //正式上线后   改成true
    
        //构造platform
        $platform = new Widget_JPush_Api_Class_Platform();
        $platform->is_android = true;
        $platform->is_ios = true;
    
        //设置激光推送 appkey masterSecret
        $appkey = '74e7fde8e3f4e2753e69990d';
        $masterSecret = '6abf34b43a4ecab000ff77c2';
    
        $jpush = new Widget_JPush_Api_Push($appkey, $masterSecret);
    
        $jpush->setNotification($notification);
        $jpush->setOption($option);
        $jpush->setPlatform($platform);
    
        // 构造alias
        $audience = new Widget_JPush_Api_Class_Audience();
         
        $audience->setRegistration_id($userids);
        $jpush->setAudience($audience);
         
        $result = $jpush->run();
         
        return $result;
    }

}