<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */
class WordController extends IndexAbstract
{
    /**
     * 电子合同
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $order_no = $this->input->query('order_no',null);

        $order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$order_no}' and (order_status=2 or order_status=3)");
        $order = $order[0];
        //借款期限
        if($order['start_time'] && $order['end_time']){
            $a = strtotime($order['start_time']);
            $b = strtotime($order['end_time']);
            $days=round(($b-$a)/3600/24)+1;
        }
        $goods = Goods_Model_List::instance()->fetchByWhere("goods_id = '{$order['goods_id']}'");
        $goods = $goods[0];
        
        $year = substr($order['ctime'],0,4);
        $month = substr($order['ctime'],5,2);
        $day = substr($order['ctime'],8,2);
        $ctime = $year.'年'.$month.'月'.$day.'日';
        $data=array(
            'order_no'=>$order['order_no'],
            'username'=>$order['username'],
            'money'=>$order['bts_yield']+$order['buy_money'],//本息
            'financial_period'=>$days,//借款期限
            'buy_money'=>$order['buy_money'],//借出金额
            'debtor_name'=>$goods['debtor_name'],//借款方名称
            'debtor_card'=>$goods['debtor_card'],//借款方证号
            'yield'=>$order['yield'].'%',
            'start_time'=>$order['start_time'],
            'end_time'=>$order['end_time'],
            'cash_time'=>$order['cash_time'],
            'ctime' =>$ctime,
        );
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'word.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    public function agreement()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_SUCCESS, 'php', 'agreement.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
}

// End ^ Native EOL ^ UTF-8