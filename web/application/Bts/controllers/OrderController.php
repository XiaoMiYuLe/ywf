<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OrderController extends IndexAbstract {

    /**
     * 快捷支付购买
     */
    public function shortcutPay() {
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
         
        $order_no = $this->input->get('order_no');
        $order_id = $this->input->get('order_id');
        $type=$this->input->get('type');
        
        if(!$order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$order_no}' and userid = '{$user[0]['userid']}'")){
            throw new Exception('该订单不存在');
        }
        
        $arr= array(
          'userid'=>$user[0]['userid'], 
        );
        
        $bank = Api_Cas_MyBankcard::run($arr);
        
        if($bank['status']==1){
            throw new Zeed_Exception($bank['error']);
        }
        
        //查 产品
        if(!$goods = Goods_Model_List::instance()->fetchByWhere("goods_id='{$order[0]['goods_id']}'")){
            throw new Zeed_Exception('该产品不存在');
        }
        $params = array(
            'is_voucher'=>$goods[0]['is_voucher'],
            'is_interest'=>$goods[0]['is_interest'],
            'userid'=>$user[0]['userid'],
        );
        
        $voucher = Api_Two_GetJudgeVoucher::run($params);
        if($voucher['status']==1){
            throw new Zeed_Exception($voucher['error']);
        }

        $data['goods_name'] = $order[0]['goods_name'];
        $data['order_no'] = $order[0]['order_no'];
        $data['yield'] = $order[0]['yield'];
        $data['cash_time'] = $order[0]['cash_time'];
        $data['buy_money'] = $order[0]['buy_money'];
        $data['real_money'] = $order[0]['real_money'];
        $data['quota'] = $bank['data'][0]['quota'];
        $data['voucher'] = $voucher['data']['content'];
        $data['voucher_count'] = count($voucher['data']['content']).'张优惠券未使用';
        $data['phone'] = substr_replace($user[0]['phone'], '****', 3,4);
        $data['card'] = $bank['data'][0] ?$bank['data'][0]:array();
        $data['asset'] = $user[0]['asset'];
        $data['type'] = $type;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'order.shortcutpay');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /* 购买签约*/
    public function bdCard() {
        $this->addResult(self::RS_SUCCESS, 'json');
       if (! Cas_Authorization::getLoggedInUserid()) {
            //已登录
            $this->setStatus(2);
            $this->setError("您还没有登录！");
            return self::RS_SUCCESS;
        }
        $res['data']['voucher'] = $this->input->post('voucher_id');
        $order_no = $this->input->post('order');
        
        $bts_order = Bts_Model_Order::instance()->fetchByWhere("order_no='{$order_no}'");
        if(empty($bts_order)){
            throw new Zeed_Exception("该订单不存在");
        }
        
        $res['data']['yield'] = $bts_order[0]['yield'];
        $res['data']['buy_money'] = $bts_order[0]['buy_money'];
        $res['data']['mtime'] = date("Y-m-d H:i:s");
       
         /*如果传入了优惠券，则获取优惠券信息*/
        if ($res['data']['voucher']) {
            $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("id = {$res['data']['voucher']}");
            if ($voucher[0]['voucher_status'] == 2) {
                $this->setStatus(1);
                $this->setError("该代金券已被使用，请重新确认订单");
                return self::RS_SUCCESS;
            }
            if ($now > $voucher[0]['valid_data']) {
                $this->setStatus(1);
                $this->setError("该代金券已失效，请重新核实");
                return self::RS_SUCCESS;
            }
            
            if($voucher[0]['type']==1){
                if($res['data']['buy_money']<$voucher[0]['use_money']){
                    $this->setStatus(1);
                    $this->setError('起投金额需大于'.$voucher[0]['use_money'].'元才能使用该代金券');
                    return self::RS_SUCCESS;
                }
            }
            
            $res['data']['is_voucher'] = 1;
            if($voucher[0]['type']==1){
                $res['data']['real_money'] = $res['data']['buy_money'] - $voucher[0]['voucher_money'];
            }
             
            //加息处理
            if($voucher[0]['type']==3){
                if($res['data']['yield']){
                    $res['data']['yield'] +=  $voucher[0]['increase_interest']*100;
                }else{
                    $this->setStatus(1);
                    $this->setError('参数年化收益率 yield 未提供');
                    return self::RS_SUCCESS;
                }
                $res['data']['real_money'] = $res['data']['buy_money'];
            }
        } else {
       		unset($res['data']['voucher']);
       		$res['data']['real_money'] = $res['data']['buy_money'];
       	}
       	

       	$id = Bts_Model_Order::instance()->update($res['data'],"order_no='{$order_no}'");
       	if(empty($id)){
       	    $this->setStatus(1);
       	    $this->setError('提交优惠券失败');
       	    return self::RS_SUCCESS;
       	}
        
        //如果为转让订单，则支付金额为订单中的real_money
        if($bts_order[0]['is_transfer_order']==1){
            $total_fee = $bts_order[0]['real_money']*100;
        }else{
            $total_fee = $res['data']['real_money']*100;
        }
        
        $params = array(
            'order_no' => $this->input->post('order'),
            
            'member_id' =>$_SESSION['userid'],
            'merchant_id' => 100000000234069,
            'total_fee' => $total_fee,
        );
        
        //request
        $result = Api_Pay_BindCard::run($params);
        $result['msg'] = $result['msg'] ? $result['msg'] : $result['error'];
        
        $result['data'] ='';

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
            'type' => 2,
        );
    
        //request
        $result = Api_Pay_Pay::run($params);
        $result['msg'] = $result['msg'] ? $result['msg'] : $result['error'];
    
        $result['data'] ='';
        return $result;
    }
    
    /**
     * 余额支付购买
     */
    public function balancePay() {
        $this->addResult(self::RS_SUCCESS, 'json');
        if (!Cas_Authorization::getLoggedInUserid()) {
            //未登入
            header('Location: ' . '/cas/sign/in');
            exit;
        }
        
        $res['data']['voucher'] = $this->input->post('voucher_id');
        $order_no = $this->input->post('order_no');
        
        $bts_order = Bts_Model_Order::instance()->fetchByWhere("order_no='{$order_no}'");
        if(empty($bts_order)){
            throw new Zeed_Exception("该订单不存在");
        }
        
        $res['data']['yield'] = $bts_order[0]['yield'];
        $res['data']['buy_money'] = $bts_order[0]['buy_money'];
        $res['data']['mtime'] = date("Y-m-d H:i:s");
        
        /*如果传入了优惠券，则获取优惠券信息*/
        if ($res['data']['voucher']) {
            $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("id = {$res['data']['voucher']}");
            if ($voucher[0]['voucher_status'] == 2) {
                $this->setStatus(1);
                $this->setError("该代金券已被使用，请重新确认订单");
                return self::RS_SUCCESS;
            }
            if ($now > $voucher[0]['valid_data']) {
                $this->setStatus(1);
                $this->setError("该代金券已失效，请重新核实");
                return self::RS_SUCCESS;
            }
            
            if($voucher[0]['type']==1){
                if($res['data']['buy_money']<$voucher[0]['use_money']){
                    $this->setStatus(1);
                    $this->setError('起投金额需大于'.$voucher[0]['use_money'].'元才能使用该代金券');
                    return self::RS_SUCCESS;
                }
            }
            
            
            $res['data']['is_voucher'] = 1;
            if($voucher[0]['type']==1){
                $res['data']['real_money'] = $res['data']['buy_money'] - $voucher[0]['voucher_money'];
            }
             
            //加息处理
            if($voucher[0]['type']==3){
                if($res['data']['yield']){
                    $res['data']['yield'] +=  $voucher[0]['increase_interest']*100;
                }else{
                    $this->setStatus(1);
                    $this->setError('参数年化收益率 yield 未提供');
                    return self::RS_SUCCESS;
                }
                $res['data']['real_money'] = $res['data']['buy_money'];
            }
        } else {
       		unset($res['data']['voucher']);
       		$res['data']['real_money'] = $res['data']['buy_money'];
       	}

       	$id = Bts_Model_Order::instance()->update($res['data'],"order_no='{$order_no}'");
       	if(empty($id)){
       	    $this->setStatus(1);
       	    $this->setError('提交优惠券失败');
       	    return self::RS_SUCCESS;
       	}
       	
       	//如果为转让订单，则支付金额为订单中的real_money
       	if($bts_order[0]['is_transfer_order']==1){
       	    $total_fee = $bts_order[0]['real_money'];
       	}else{
       	    $total_fee = $res['data']['real_money'];
       	}
            
       $params = array(
           'pay_pwd' => $this->input->post('pay_pwd'),
           'member_id' => $_SESSION['userid'],
           'merchant_id' => 100000000234069,
           'order_no' => $order_no,
           'total_fee'=> $total_fee,
       );
       
       $result = Api_Pay_BalancePaid::run($params);
           
       return $result;
    }
    
    

    /**
     * 防错
     */
    public function index() {
        echo '';
    }

    /**
     * 支付成功提示页面
     */
    public function prompt() {
        $this->addResult(self::RS_SUCCESS, 'json');
        if (!Cas_Authorization::getLoggedInUserid()) {
            //未登入
            header('Location: ' . '/cas/sign/in');
            exit;
        }
        $order_no = $this->input->get('order_no');
        $data['order_no'] = $order_no;
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'order.prompt');
        return parent::multipleResult(self::RS_SUCCESS);
    }

}
