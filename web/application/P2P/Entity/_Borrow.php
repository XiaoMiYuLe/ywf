<?php
/**
 * 借款标的表
 */

class P2P_Entity_Borrow extends Zeed_Object
{
    public $borrow_id;// '标的id',
    public $borrow_user_id;//'借款用户ID（也就是cas_user表的userid）',
    public $borrow_name;//'标的名称',
    public $total_money;//'总金额',
    public $raise_money;//'募集金额',
    public $expect_interest_money;// '预期利息（满标后生成）',
    public $real_interest_money;//'已还利息（每次兑付更新）',
    public $interest_rate;//'计息方式 1：分期付息，到期还本  2：到期还本付息',
    public $interest_type;//'计息类型 1：N+1(满标次日计息)  2：T+1（满标次工作日计息）',
    public $yield_rate;//'收益率%',
    public $raise_day;//'募集天数',
    public $interest_start_time;//'计息开始时间（满标后生成）',
    public $interest_end_time;//'计息结束时间（满标后生成）',
    public $last_payment_time;//'最后一期兑付日（满标后生成）',
    public $full_time;//'满标时间（满标后生成）',
    public $borrow_status;//'标的状态：1：募集中 2：已满标 3：还款中 4：已兑付  5：流标',
    public $borrow_time_limit;//'借款期限(30天为一个月)',
    public $interest_limit;//'结算期数（共几期，满标后生成）',
    public $low_pay;//'最低投资额(元)',
    public $increasing_amount;//'递增金额',
    public $is_show;//'是否显示 0否 1是',
    public $buy_num;//'购买人次',
    public $is_hot;//'是否精选推荐 0否  1是',
    public $is_sign_agreement;//'是否签署借款协议 0否  1是',
    public $counter_fee;//'服务费率',
    public $add_time;//'添加时间'

    /**
     * @return P2P_Entity_Borrow
     */
    public final static function newInstance()
    {
        return new self();
    }
}

// End ^ Native EOL ^ UTF-8