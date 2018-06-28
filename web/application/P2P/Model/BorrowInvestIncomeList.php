<?php

/**
 * 投资记录表
 */
class P2P_Model_BorrowInvestIncomeList extends P2P_Model_Public
{

    /**
     * @var string The table name.
     */
    protected $_name = 'borrow_invest_income_list';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'biil_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'p_';

    /**
     * @return P2P_Model_BorrowInvestIncomeList
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

    /*
     * 生成收益记录
     */
    public function buildIncome($borrow, $bi_one)
    {
        try {
            $this->beginTransaction();
            $brl_model = P2P_Model_BorrowRepaymentList::instance();
            $br_list = $brl_model->fetchByWhere("borrow_id = " . $borrow['borrow_id']);
            //合计收益（待收收益）
            $sum_income_money = 0;
            //投资金额（待收本金）
            $invest_money = $bi_one['invest_money'];
            //收益率
            $yield_rate = $bi_one['yield_rate'];
            //计息方式
            $interest_rate = $borrow['interest_rate'];
            //平均每期本金（用于等额本金）
            $avg_money = 0;
            //计算平均值
            if ($interest_rate == 3) {
                $avg_money = ceil($invest_money / $borrow["interest_limit"]);
            }
            foreach ($br_list as $br_one) {
                //计息天数
                $time_limit = $br_one['time_limit'];
                //收益 ceil -- 进一法取整,floor -- 舍去法取整
                $income_money = floor($invest_money * $yield_rate * $time_limit / 360) / 100;
                //插入数据库的数据
                $insert_money = $avg_money;
                //是否最后一期
                if ($br_one['is_last'] == 1) {
                    $insert_money = $invest_money;
                } else {
                    $invest_money -= $avg_money;
                }
                $sum_income_money += $income_money;
                //更新还款资金信息
                $br_money = $income_money + $insert_money;
                if (!$brl_model->update(array("expect_interest_money" => ($br_one['expect_interest_money'] + $br_money)),
                    'expect_interest_money = ' . $br_one['expect_interest_money'] . ' and brl_id = ' . $br_one['brl_id'])) {
                    throw new Zeed_Exception('更新还款记录资金失败');
                }
                //添加收益信息
                if (!$this->insert(array('bil_id' => $bi_one['bil_id'], 'brl_id' => $br_one['brl_id'], 'corpus_money' => $insert_money, 'time_limit' => $br_one['time_limit'],
                    'invest_user_id' => $bi_one['invest_user_id'], 'income_money' => $income_money, 'interest_limit_num' => $br_one['interest_limit_num'], 'is_last' => $br_one['is_last'],
                    'expect_payment_time' => $br_one['expect_payment_time'], 'add_time' => NOW_TIME))) {
                    throw new Zeed_Exception('生成收益记录失败');
                }
            }
            if (!P2P_Model_BorrowInvestList::instance()->update(array('invest_status' => 2), 'bil_id = ' . $bi_one['bil_id'])) {
                throw new Zeed_Exception('更新投资预期收益记录失败');
            }
            $crl_model = Cas_Model_Record_Log::instance();
            //投资人资金解冻
            if (!$crl_model->unFreezeMoney($bi_one['invest_user_id'], $bi_one['order_no'], $bi_one['invest_money'])) {
                throw new Zeed_Exception('资金解冻失败');
            }
            //投资人满标投资扣款
            if (!$crl_model->reduceMoney($bi_one['invest_user_id'], $bi_one['order_no'], $bi_one['invest_money'], 3)) {
                throw new Zeed_Exception('满标投资扣款失败');
            }
            //借款人满标增资
            if (!$crl_model->addMoney($borrow['borrow_user_id'], $bi_one['order_no'], $bi_one['invest_money'], 11)) {
                throw new Zeed_Exception('满标增资失败');
            }
            //获取投资人用户信息
            $user = Cas_Model_User::instance()->getUserByUserid($bi_one['invest_user_id']);
            //更新投资用户待收本金和待收收益
            if (!Cas_Model_User::instance()->update(array('receivable_money' => ($user['receivable_money'] + $invest_money), 'receivable_income' => ($user['receivable_income'] + $sum_income_money)),
                array('userid' => $bi_one['invest_user_id']))) {
                throw new Zeed_Exception('更新用户资金失败');
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
        return true;
    }
}

// End ^ LF ^ encoding
