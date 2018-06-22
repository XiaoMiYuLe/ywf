<?php

/**
 * 投资记录表
 */
class P2P_Model_BorrowRepaymentList extends P2P_Model_Public
{

    /**
     * @var string The table name.
     */
    protected $_name = 'borrow_repayment_list';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'brl_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'p_';

    /**
     * @return P2P_Model_BorrowRepaymentList
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

    /*
     * 获取计息开始时间（T+1计息）
     */
    public function getInterestStartTime($set_time)
    {
        //格式化一下
        $set_time = date("Y-m-d", strtotime($set_time));
        //节假日
        $fee_time = array("2018-09-24", "2018-10-01", "2018-10-02", "2018-10-03", "2018-10-04", "2018-10-05");
        //调班
        $no_fee_time = array("2018-09-29", "2018-09-30");
        //是否工作日
        $is_work_time = true;
        if (in_array($set_time, $fee_time)) {
            $is_work_time = false;
        } elseif (in_array($set_time, $no_fee_time)) {
            $is_work_time = true;
        } else {
            $now_week = date("w", strtotime($set_time));
            if ($now_week == '0' || $now_week == '6') {
                $is_work_time = false;
            }
        }
        if ($is_work_time) {
            return $set_time;
        } else {
            $set_time = date("Y-m-d", strtotime($set_time) + 24 * 60 * 60);
            return $this->getInterestStartTime($set_time);
        }
    }

    /*
     * 生成还款记录
     */
    public function buildRepayment($borrow)
    {
        //查询是否已经有记录了
        $b_r_List = $this->fetchByWhere("borrow_id = " . $borrow['borrow_id']);
        if ($b_r_List) {
            return true;
        }
        //获取满标时间
        $full_time = $borrow['full_time'];
        //获取计息类型
        $interest_type = $borrow['interest_type'];
        //计息开始时间
        $interest_start_time = date("Y-m-d", strtotime($full_time) + 24 * 60 * 60);
        //1是N+1计息  2是T+1计息
        if ($interest_type == 2) {
            $interest_start_time = $this->getInterestStartTime($interest_start_time);
        }
        //借款天数
        $borrow_time_limit = $borrow['borrow_time_limit'];
        //计息结算时间
        $interest_end_time = date("Y-m-d", strtotime($interest_start_time) + 24 * 60 * 60 * ($borrow_time_limit - 1));
        //最后一期兑付时间
        $last_payment_time = date("Y-m-d", strtotime($interest_start_time) + 24 * 60 * 60 * $borrow_time_limit);
        //收益率
        //$yield_rate = $borrow['yield_rate'];
        //计息方式
        $interest_rate = $borrow['interest_rate'];
        //募集金额
        $raise_money = $borrow['raise_money'];
        //还款利率,进一法取整  ceil -- 进一法取整,floor -- 舍去法取整
        //$sum_yield_money = ceil($raise_money * $yield_rate * $borrow_time_limit / 360) / 100;
        //服务费,进一法取整
        $sum_brokerage_money = ceil($raise_money * $borrow['counter_fee'] * $borrow_time_limit / 360) / 100;
        //备注：预还款金额没计算利息和本金，是利息和本金要在投资人那边计算再累加上来
        try {
            $this->beginTransaction();
            $limit_num = 1;//第几期
            if ($interest_rate == "1") {
                if (!$this->insert(array(
                    'borrow_id' => $borrow['borrow_id'], "borrow_user_id" => $borrow['borrow_user_id'], 'time_limit' => $borrow_time_limit,
                    "expect_interest_money" => $sum_brokerage_money, "brokerage_money" => $sum_brokerage_money,
                    "is_last" => "1", "interest_limit_num" => $limit_num, "expect_payment_time" => $last_payment_time, "add_time" => NOW_TIME
                ))
                ) {
                    throw new Zeed_Exception('创建还款记录失败');
                }
            } elseif ($interest_rate == "2" || $interest_rate == "3") {
                $is_do = false;//现有方案，第一个月不还款
                $repay_date = 0;//算息天数，配合第一个月不还款方案
                $start_time = strtotime($interest_start_time);
                $time_limit = $borrow_time_limit;
                $_brokerage_money = 0;//已结算管理费
                do {
                    $t = date("t", $start_time);//获取当月天数
                    $d = date("d", $start_time);//获取当时第几天
                    $is_last = "0";//是否最后一期
                    $repay_date = $repay_date + ($t - $d) + 1;//算息天数
                    if (!$is_do && $repay_date < $time_limit) {
                        $is_do = true;
                        $repay_time = date("Y-m-" . $t . " 00:00:00", $start_time);
                        $start_time = strtotime($repay_time) + 24 * 60 * 60;
                        continue;
                    }
                    if ($time_limit <= ($t - $d) + 1) {
                        $is_last = "1";
                        $repay_date = $time_limit;
                        $repay_time = date("Y-m-d 00:00:00", $start_time + $time_limit * 24 * 60 * 60);
                    } else {
                        $time_limit -= $repay_date;
                        $repay_time = date("Y-m-" . $t . " 00:00:00", $start_time);
                        $start_time = strtotime($repay_time) + 24 * 60 * 60;
                    }
                    //假如是最后一期，需要加上本金
                    if ($is_last == '1') {
                        //收益金额,最后一期的收益金额为：总额扣除前面生成的
                        //$yield_money = $raise_money * ($yield_rate / 100) * $repay_date / 360;
                        //管理费，最后一期 = 总额 - 已结算
                        $brokerage_money = $sum_brokerage_money - $_brokerage_money;
                        //预还款金额
                        $expect_interest_money = $brokerage_money;
                    } else {
                        //收益金额,
                        //$yield_money = $raise_money * ($yield_rate / 100) * $repay_date / 360;
                        //管理费
                        $brokerage_money = ceil($raise_money * $borrow['counter_fee'] * $repay_date / 360) / 100;
                        //累加已结算管理费
                        $_brokerage_money += $brokerage_money;
                        //预还款金额
                        $expect_interest_money = $brokerage_money;
                    }

                    //如果插入失败，回滚跳出
                    if (!$this->insert(array(
                        'borrow_id' => $borrow['borrow_id'], "borrow_user_id" => $borrow['borrow_user_id'], 'time_limit' => $repay_date,
                        "expect_interest_money" => $expect_interest_money, "brokerage_money" => $brokerage_money, "is_last" => $is_last,
                        "interest_limit_num" => $limit_num, "expect_payment_time" => $repay_time, "add_time" => NOW_TIME
                    ))
                    ) {
                        throw new Zeed_Exception('创建还款记录失败');
                    }
                    //添加最后一期跳出循环
                    if ($is_last == "1") {
                        break;
                    }
                    $limit_num++;
                    $repay_date = 0;
                } while (true);
            }
            //更新对应标的投资记录的信息
            if (!P2P_Model_BorrowInvestList::instance()->update(array('interest_limit' => $limit_num, 'last_payment_time' => $last_payment_time),
                'borrow_id = ' . $borrow['borrow_id'])
            ) {
                throw new Zeed_Exception('创建还款记录失败');
            }
            //更新标的信息
            if (!P2P_Model_Borrow::instance()->update(array('interest_end_time' => $interest_end_time, 'interest_start_time' => $interest_start_time,
                'last_payment_time' => $last_payment_time, 'interest_limit' => $limit_num), 'borrow_id = ' . $borrow['borrow_id'])) {
                throw new Zeed_Exception('创建还款记录失败');
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
        //查询是否已经有记录了
        $b_r_List = $this->fetchByWhere("borrow_id = " . $borrow['borrow_id']);
        if ($b_r_List) {
            return true;
        } else {
            return false;
        }
    }
}

// End ^ LF ^ encoding
