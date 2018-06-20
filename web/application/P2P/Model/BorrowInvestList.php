<?php

/**
 * 投资记录表
 */
class P2P_Model_BorrowInvestList extends P2P_Model_Public
{

    /**
     * @var string The table name.
     */
    protected $_name = 'borrow_invest_list';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'bil_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'p_';

    /**
     * @return P2P_Model_BorrowInvestList
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

    /**
     * 添加投资记录
     */
    public function addInvestInfo($borrow, $userid, $buy_money,$order_no)
    {
        $arrs['order_no'] = $order_no;
        $arrs['borrow_id'] = $borrow['borrow_id'];
        $arrs['borrow_name'] = $borrow['borrow_name'];
        $arrs['invest_user_id'] = $userid;
        $arrs['invest_money'] = $buy_money;
        $arrs['yield_rate'] = $borrow['yield_rate'];
        $arrs['invest_status'] = '1';
        $arrs['invest_time'] = NOW_TIME;
        return $this->insert($arrs);
    }
}

// End ^ LF ^ encoding
