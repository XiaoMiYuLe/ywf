<?php
/**
 * 投资表
 */

class P2P_Entity_BorrowInvestList extends Zeed_Object
{

    public $bil_id;//'投资表id',
    public $order_no;//'资金流水编号，投资时生成，用于冻结资金，以及满标后解冻、扣款和借款人增加资金（P开头）',
    public $borrow_id;// '标的ID',
    public $borrow_name;//'标的名称，冗余字段',
    public $invest_user_id;//'投资用户ID（也就是cas_user表的userid）',
    public $invest_money;//'投资金额',
    public $yield_rate;//'收益率%',
    public $interest_limit;//'结算期数(共几期，满标后生成)，冗余字段',
    public $expect_income;//'预期收益',
    public $get_income;//'已获收益',
    public $invest_status;//'投资状态（0投资失败 1投资成功 2计息中 3已兑付 4提前兑付 5流标）',
    public $last_payment_time;//'最后一期兑付日（满标后生成），冗余字段',
    public $invest_time;//'投资时间',
    /**
     * @return P2P_Entity_BorrowInvestList
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8