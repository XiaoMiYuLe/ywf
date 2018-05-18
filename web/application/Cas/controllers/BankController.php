<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BankController extends IndexAbstract {

    /**
     * 银行卡信息列表 1张
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (!Cas_Authorization::getLoggedInUserid()) {
            //未登入
            header('Location: ' . '/cas/sign/in');
            exit;
        }
        
        $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$_SESSION['userid']}'");
        if (!$userExists) {
            throw new Zeed_Exception('该用户不存在');
        }
         
        /* 检查用户状态 */
        if($userExists[0]['status'] == 1 ){
            throw new Zeed_Exception('该账号已禁用');
        }
         
        $userExists = current($userExists);
         
        /*绑定银行卡信息*/
        if(!empty($userExists)){
            if(!empty($userExists['bank_id'])){
                $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$userExists['bank_id']}' and is_use=1 and is_del=0");
                if(empty($bank_info)){
                    header('Location: ' . '/cas/bank/addBank');
                    exit;
                }
            }else{
                header('Location: ' . '/cas/bank/addBank');
                exit;
            }
        }
        
        
        $params = array(
            'userid'=>$_SESSION['userid'],
        );
        $card = Api_Cas_MyBankcard::run($params);

        $data['card'] = $card['data'][0] ?$card['data'][0]:array();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'bank.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加银行卡
     */
    public function addBank() {
        $this->addResult(self::RS_SUCCESS, 'json');

        if (!Cas_Authorization::getLoggedInUserid()) {
            //未登入
            header('Location: ' . '/cas/sign/in');
            exit;
        }
        
        $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$_SESSION['userid']}'");
        if (!$userExists) {
            throw new Zeed_Exception('该用户不存在');
        }
         
        /* 检查用户状态 */
        if($userExists[0]['status'] == 1 ){
            throw new Zeed_Exception('该账号已禁用');
        }
         
        $userExists = current($userExists);
         
        /*绑定银行卡信息*/
        if(!empty($userExists)){
            if(!empty($userExists['bank_id'])){
                $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$userExists['bank_id']}' and is_use=1 and is_del=0");
                if(!empty($bank_info)){
                    header('Location: ' . '/cas/bank/index');
                    exit;
                }
            }
        }
        
        
        $banklist = Api_Cas_BankcardList::run($arr);
       
       
        if($banklist['status']==0){
            $banklist = array_combine($banklist['data']['bank_name'],$banklist['data']['bank_quota']);
        } 
        
        $data['bank'] = $banklist ?$banklist:array();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'bank.addbank');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /*
     * 绑卡签约
     * */
    public function dt(){
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! Cas_Authorization::getLoggedInUserid()) {
            //已登录
            $this->setStatus(2);
            $this->setError("您还没有登录！");
            return self::RS_SUCCESS;
        }
        
        /* “card_no” = 6214242710498301;
        “cert_no” = 210302196001012114;
        “member_id” = 3236;
        “merchant_id” = 100000000000147;
        owner = “\U97e9\U6885\U6885”;
        phone = 13220482188;
        “total_fee” = 218; */
        //params
        $params = array(
            'subbank_name' => $this->input->post('subbank_name'),
            'card_no' => $this->input->post('card_no'),
            'cert_no' => $this->input->post('cert_no'),
            'owner' => $this->input->post('owner'),
            'phone' => $this->input->post('phone'),
            
            'member_id' =>$_SESSION['userid'],
            'merchant_id' => 100000000234069,
            'total_fee' => 218,
        );
        
        //request
        $result = Api_Pay_Debit::run($params);
        $result['msg'] = $result['msg'] ? $result['msg'] : $result['error'];
        $order_no = $result['data']['order_no'];
        $bank_name = $result['data']['bank_name'];
        $bind_id  = $result['data']['bind_id'];
        $result['data'] ='';
        $result['data']['order_no'] = $order_no ;
        $result['data']['bank_name'] = $bank_name ;
        $result['data']['bind_id'] = $bind_id;

        return $result;
      }
      
      /*
       * 卡密鉴权
       * */
      public function km(){
          $this->addResult(self::RS_SUCCESS, 'json');
      
          if (! Cas_Authorization::getLoggedInUserid()) {
              //已登录
              $this->setStatus(2);
              $this->setError("您还没有登录！");
              return self::RS_SUCCESS;
          }
          
          $urlmapping = Zeed_Config::loadGroup('urlmapping');
      
         /* “bind_id” = 343545;
         “member_id” = 3236;
         “merchant_id” = 100000000000147;
         “order_no” = bk1473733813191;
         “return_url” = qwert; */
          $order_no = $this->input->get('order_no');
          $bind_id = $this->input->get('bd');
          $phone = $this->input->get('phone');
          //params
          $params = array(
              'bind_id' =>$bind_id,
              'member_id' =>$_SESSION['userid'],
              'merchant_id' => 100000000234069,
              'order_no' => $order_no,
              'return_url' => $urlmapping['store_url_login'].'/cas/bank/zhaohang?order_no='.$order_no.'&phone='.$phone,
          );
          //request
          $result = Api_Pay_Certificate::run($params);
          var_dump($result['data']);die;
         /*  if($result['status']==0){
              $allHtml = preg_replace('/\n/g', " ", $result['data']);
              $allHtml = preg_replace('/\r/g', " ", $result['data']);
              $allHtml = preg_replace('/\ /g', " ", $result['data']);
          
          }
          return $allHtml; */
      }
      
      /*
       * 卡密isok
       * */
      public function ok(){
          $this->addResult(self::RS_SUCCESS, 'json');
      
          if (! Cas_Authorization::getLoggedInUserid()) {
              //已登录
              $this->setStatus(2);
              $this->setError("您还没有登录！");
              return self::RS_SUCCESS;
          }
      
         /*  “order_no” = bk1473733813191; */
          
          $order_no = $this->input->post('order_no');
          //params
          $params = array(
              'order_no' => $order_no,
          );
      
          //request
          $result = Api_Pay_Iskamiok::run($params);
      
          //$result['data'] ='';
          return $result;
      }
      
      /*
       * 发送短信验证
       * */
      public function sms(){
          $this->addResult(self::RS_SUCCESS, 'json');
      
          if (! Cas_Authorization::getLoggedInUserid()) {
              //已登录
              $this->setStatus(2);
              $this->setError("您还没有登录！");
              return self::RS_SUCCESS;
          }
      
          /*       “merchant_id” = 100000000000147;
          “order_no” = bk14736599659890; */
          $order_no = $this->input->post('order_no');
          //params
          $params = array(
              'order_no' =>$order_no,
              'merchant_id' => 100000000234069,
          );
      
          //request
          $result = Api_Pay_ReSendSms::run($params);
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
              'type' => 1,  
          );
      
          //request
          $result = Api_Pay_Pay::run($params);
          $result['msg'] = $result['msg'] ? $result['msg'] : $result['error'];
      
          $result['data'] ='';
          return $result;
      }
    
      /*
       * 支付查询
       * */
      public function search(){
          $this->addResult(self::RS_SUCCESS, 'json');
      
          if (! Cas_Authorization::getLoggedInUserid()) {
              //已登录
              $this->setStatus(2);
              $this->setError("您还没有登录！");
              return self::RS_SUCCESS;
          }
      
         /*  “merchant_id” = 100000000000147;
          “order_no” = bk1473676978772; */
      
           $order_no = $this->input->post('order_no');
      
          $params = array(
              'merchant_id' => 100000000234069,
              'order_no' => $order_no,
          );
      
          //request
          $result = Api_Pay_Search::run($params);
      
          $result['data'] ='';
          return $result;
      }
      
      /**
       * 招行卡密成功界面
       */
      public function zhaohang() {
          $this->addResult(self::RS_SUCCESS, 'json');
          
          if (! Cas_Authorization::getLoggedInUserid()) {
              //已登录
              $this->setStatus(2);
              $this->setError("您还没有登录！");
              return self::RS_SUCCESS;
          }
          $order_no = $this->input->get('order_no');
          $phone = $this->input->get('phone');
          
          $params = array(
              'order_no' => $order_no,
          );
          //sleep(40);
          $result = Api_Pay_Iskamiok::run($params);
          
          $data = array($result);
          
          $data[0]['data']['phone'] = $phone;

          $this->setData('data', $data[0]);
          
          $this->addResult(self::RS_SUCCESS, 'php', 'zhaohang.index');
          return parent::multipleResult(self::RS_SUCCESS);
      
      }
      
      /*
       * 卡密鉴权页面加载
       * */
      public function kmjz(){
        $this->addResult(self::RS_SUCCESS, 'json');
        $allHtml = $this->input->post('allHtml');
        
        header("location: /cas/bank/kmjz1?allHtml={$allHtml}");
        exit;
      }
      
      /*
       * 卡密鉴权页面加载
       * */
      public function kmjz1(){
          $this->addResult(self::RS_SUCCESS, 'json');
          $allHtml = $this->input->get('allHtml');
      
          $this->setData('data', $allHtml);
          $this->addResult(self::RS_SUCCESS, 'php', 'kmjz.index');
          return parent::multipleResult(self::RS_SUCCESS);
      }
}
