<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 充值
 */

class RechargeController extends CasAbstract {

    /**
     * 快捷支付
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');
        if (!Cas_Authorization::getLoggedInUserid()) {
            //未登入
            header('Location: ' . '/cas/sign/in');
            exit;
        }
        
        /*校验用户是否有效*/
        if (!$user = Cas_Model_User::instance()->fetchByWhere("userid = '{$_SESSION['userid']}' and status = 0")) {
            throw new Zeed_Exception('该用户不存在或已被冻结');
        }
        
        $arr= array(
            'userid'=>$user[0]['userid'],
        );
        
        $bank = Api_Cas_MyBankcard::run($arr);
        
        if($bank['status']==1){
            throw new Zeed_Exception($bank['error']);
        }
        
        $data['card'] = $bank['data'][0] ?$bank['data'][0]:array();
        $data['asset'] = $user[0]['asset'];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'recharge.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /* 充值签约*/
    public function bdCard() {
        $this->addResult(self::RS_SUCCESS, 'json');
        if (! Cas_Authorization::getLoggedInUserid()) {
            //已登录
            $this->setStatus(2);
            $this->setError("您还没有登录！");
            return self::RS_SUCCESS;
        }
    
        $total_fee = $this->input->post('money');
        $total_fee = $total_fee*100;
    
        $params = array(
            'member_id' =>$_SESSION['userid'],
            'merchant_id' => 100000000234069,
            'total_fee' => $total_fee,
        );
    
        //request
        $result = Api_Pay_BindCard2::run($params);
        $result['msg'] = $result['msg'] ? $result['msg'] : $result['error'];
        $order_no = $result['data']->order_no;

        $result['data'] ='';
        $result['data']['order_no'] = $order_no;
       
        return $result;
    }
    
    /*
     * 确认支付
     * */
    public function pay(){
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! Cas_Authorization::getLoggedInUserid()) {
            //已登录
            $this->setStatus(2);
            $this->setError("您还没有登录！");
            return self::RS_SUCCESS;
        }
    
        /* “check_code” = 123456;
         “member_id” = 3236;
         “merchant_id” = 100000000000147;
         “order_no” = bk14736599327516;
         type = 1; */
    
        $order_no = $this->input->post('order_no');
        $check_code = $this->input->post('check_code');
    
        $params = array(
            'check_code' => $check_code,
            'member_id' => $_SESSION['userid'],
            'merchant_id' => 100000000234069,
            'order_no' => $order_no,
            'type' => 3,
        );
    
        //request
        $result = Api_Pay_Pay::run($params);
        $result['msg'] = $result['msg'] ? $result['msg'] : $result['error'];
        $result['data'] ='';
        return $result;
    }
    
    /**
     * 网银支付
     */
    public function internetBank() {
        $this->addResult(self::RS_SUCCESS, 'json');
        /*校验用户是否有效*/
        if (!$user = Cas_Model_User::instance()->fetchByWhere("userid = '{$_SESSION['userid']}' and status = 0")) {
            throw new Zeed_Exception('该用户不存在或已被冻结');
        }
        
        $data['asset'] = $user[0]['asset'];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'recharge.internetbank');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    //支付信息
    public function payinfo(){
        $this->addResult(self::RS_SUCCESS, 'json');
        $order_amt = $this->input->post('order_amt');
        $order_amt = $order_amt*100;
        $order_id = 'cz' . time().rand(1,9999);
        $iss_ins_cd = $this->input->post('iss_ins_cd');
        $params = array(
            'userid' => $_SESSION['userid'],
            'order_id' => $order_id,
            'order_amt' => $order_amt,
            'order_pay_type' => 'B2C',
            'iss_ins_cd' => $iss_ins_cd,
        );
        
        if(empty($params['iss_ins_cd'])){
            $this->setStatus(1);
            $this->setError("请选择银行");
            return self::RS_SUCCESS;
        }
        
        $result = Api_Webpay_Payinfo::run($params);
        return $result;
    }
    
    /**
     * 充值成功提示页面
     */
    public function prompt() {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $this->addResult(self::RS_SUCCESS, 'php', 'recharge.prompt');
        return parent::multipleResult(self::RS_SUCCESS);
        
    }
}
