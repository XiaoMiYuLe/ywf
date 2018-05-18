<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 提现
 */
class CashController extends CasAbstract {

    /**
     * 提现
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //账户余额
        $params['userid'] = $_SESSION['userid'];
        $userBalance = Api_Cas_GetUserBalance::run($params);

        //我的银行卡
        $bank = Api_Cas_MyBankcard::run($params);

        //判断用户手续费
        $number = Api_Cas_GetWithdrawNumber::run($params);

        //提现主接口,记录用户提现
        if ($this->input->isAJAX()) {
            $paramss = array(
                'userid' => $_SESSION['userid'], //用户id
                'withdraw_money' => $this->input->post('tixian'), //提现金额
                'pay_pwd' => (string) $this->input->post('password'), //支付密码
            );
            
            $result = Api_Cas_WithdrawDeposit::run($paramss);
            
            if ($result['status'] == 0) {
                unset($result['data']);
            }
            
            return $result;
        }

        //数据分配
        $data['number'] = $number['data'] ? $number['data'] : array();
        $data['bank'] = $bank['data'] ? $bank['data'][0] : array();
        $data['balance'] = $userBalance['data'] ? $userBalance['data'] : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'cash.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 提现成功提示页面
     */
    public function prompt() {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        
        $this->addResult(self::RS_SUCCESS, 'php', 'cash.prompt');
        return parent::multipleResult(self::RS_SUCCESS);
    }

}
