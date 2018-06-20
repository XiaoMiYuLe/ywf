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
  `bil_id` int(11) DEFAULT '0' COMMENT '标的投资表id',
  `brl_id` int(11) DEFAULT '0' COMMENT '还款表id',
  `corpus_money` decimal(10,2) DEFAULT '0.00' COMMENT '应收本金',
  `income_money` decimal(10,2) DEFAULT '0.00' COMMENT '收益金额',
  `interest_limit_num` tinyint(1) DEFAULT '0' COMMENT '结算期数（第几期）',
  `is_last` tinyint(1) DEFAULT '0' COMMENT '是否最后一期（0否 1是）',
  `expect_payment_time` date DEFAULT NULL COMMENT '预兑付日（满标后生成）',
  `add_time` datetime DEFAULT NULL COMMENT '添加时间',
    */
    /*
     * 生成收益记录
     */
    public function buildIncome($borrow, $bi_one)
    {
        try {
            $this->beginTransaction();
            $br_list = P2P_Model_BorrowRepaymentList::instance()->fetchByWhere("borrow_id = " . $borrow['borrow_id']);
            //预期收益总额
            $expect_income = 0;
            foreach ($br_list as $br_one) {

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
