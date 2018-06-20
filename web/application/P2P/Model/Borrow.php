<?php

/**
 * 借款标的表
 */
class P2P_Model_Borrow extends P2P_Model_Public
{

    /**
     * @var string The table name.
     */
    protected $_name = 'borrow';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'borrow_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'p_';

    /**
     * @return P2P_Model_Borrow
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

    /**
     * 投资方法
     */
    public function invest($borrow_id, $userid, $buy_money)
    {
        if (date("H") < 1) {
            return $this->dataFormat('', 1, '每日凌晨0点至1点为系统结算时间，不可进行投资');
        }
        // 执行参数验证
        if (!isset($borrow_id) || strlen($borrow_id) < 1) {
            return $this->dataFormat('', 1, '标的编号未提供');
        }
        if (!isset($buy_money) || strlen($buy_money) < 1) {
            return $this->dataFormat('', 1, '投资金额未提供');
        }
        if (!isset($userid) || strlen($userid) < 1) {
            return $this->dataFormat('', 1, '用户编号未提供');
        }
        /*检验用户是否有效*/
        if (!$userList = Cas_Model_User::instance()->fetchByWhere("userid = {$userid} and status = 0")) {
            return $this->dataFormat('', 1, "该用户不存在或已被冻结");
        }
        $user = $userList[0];
        if ($user['asset'] < $buy_money) {
            return $this->dataFormat('', 1, "用户可用资金不足");
        }
        /*检验产品是否有效*/
        if (!$borrow_list = P2P_Model_Borrow::instance()->fetchByWhere("borrow_id = {$borrow_id} and is_show = 1")) {
            return $this->dataFormat('', 1, '不存在此标的');
        }
        $borrow = $borrow_list[0];
        if ($borrow['borrow_status'] != 1) {
            return $this->dataFormat('', 1, '该标的不在募集中');
        }
        if ($buy_money < $borrow['low_pay']) {
            return $this->dataFormat('', 1, '投资金额小于标的最低投资额');
        }
        if (($borrow['total_money'] - $borrow['raise_money']) < $buy_money) {
            return $this->dataFormat('', 1, '标的剩余额度已不足');
        }
        if ($borrow['increasing_amount'] > 0) {
            $q = ($buy_money - (int)$borrow['low_pay']) / (int)$borrow['increasing_amount'];
            if (!is_int($q)) {
                return $this->dataFormat('', 1, "请按递增" . (int)$borrow['increasing_amount'] . "元填写投资金额");
            }
        }

        try {
            /*开启事务*/
            $this->beginTransaction();
            //获取订单编号
            $order_no = $this->getOrderNo();
            //冻结用户资金，生成用户资金记录
            $freeze_status = Cas_Model_Record_Log::instance()->freezeMoney($user, $order_no, $buy_money);
            if (!$freeze_status) {
                throw new Zeed_Exception('投资失败');
            }
            //生成投资记录
            $invest_status = P2P_Model_BorrowInvestList::instance()->addInvestInfo($borrow, $userid, $buy_money, $order_no);
            if (!$invest_status) {
                throw new Zeed_Exception('投资失败');
            }
            //更新标的信息
            $borrow_arr['raise_money'] = $borrow['raise_money'] + $buy_money;
            $borrow_arr['buy_num'] = $borrow['buy_num'] + 1;
            if ($borrow_arr['raise_money'] == $borrow['total_money']) {
                $borrow_arr['borrow_status'] = 2;
                $borrow_arr['full_time'] = date(DATETIME_FORMAT);
            }
            if (!$this->update($borrow_arr, array("borrow_id" => $borrow_id, 'raise_money' => $borrow['raise_money']))) {
                throw new Zeed_Exception('投资失败');
            }
            $this->commit();
        } catch (Zeed_Exception $e) {
            $this->rollBack();
            return $this->dataFormat("", 1, $e->getMessage());
        }
        return $this->dataFormat("", 0, "投资成功");
    }

    /**
     * 满标程序
     */
    public function fullBorrow()
    {
        //查询需要满标的标的
        $full_borrows = P2P_Model_Borrow::instance()->fetchByWhere("borrow_status = 2");
        //还款表MODEL
        $brl_model = P2P_Model_BorrowRepaymentList::instance();
        //投资表MODEL
        $bil_model = P2P_Model_BorrowInvestList::instance();
        //收益表Model
        $biil_model = P2P_Model_BorrowInvestIncomeList::instance();
        if ($full_borrows) {
            foreach ($full_borrows as $borrow) {
                //生产还款记录表
                $br_list = $brl_model->buildRepayment($borrow);
                if ($br_list) {
                    //查询投资记录
                    $bi_list = $bil_model->fetchByWhere("borrow_id = " . $borrow['borrow_id']." and invest_status = 1 ");
                    $is_ok = true;
                    if ($bi_list) {
                        foreach ($bi_list as $bi_one) {
                            if (!$biil_model->buildIncome($borrow, $bi_one)) {
                                $is_ok = false;
                            }
                        }
                        if($is_ok){

                        }
                    }
                }
            }
        }
    }


}

// End ^ LF ^ encoding
