<?php

/**
 * iNewS Project
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright  Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 9, 2010
 * @version    SVN: $Id$
 */
class Bts_Model_Order_Refund extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'order_refund';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'refund_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'bts_';
    
    /**
     * 生成退单号
     *
     * @param integer $tokenLen 退单号
     * @return string|null
     *
     * @see BTS_Order::getSimpleReturnNumberToken()
     */
    public function getSimpleReturnNumberToken($tokenLen = 8)
    {
    	return '400'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $tokenLen);
    }
    
    /**
     * 根据订单ID查询订单
     * @param unknown $order_number
     * @return unknown
     */
    public function getOderByNumber($order_number){
    
    	$select = $this->getAdapter()->select()->from($this->getTable());
    	 
    	$where = "return_sn ='{$order_number}'";
    	$select->where($where);
    	 
    	$row = $select->query()->fetch();
    	return $row;
    	 
    }
    
    /**
     * 获取退货申请订单数量
     * $retrun int
     */
    public function countNeedRefund()
    {
    	$where = "status = 2";
    	$select = $this->getAdapter()->select()->from($this->getTable(), array('count_num' => "COUNT(*)"));
    	$row = $select->where($where)->query()->fetch();
    
    	return $row ? $row["count_num"] : 0;
    }
    
    /**
     *
     * @return Bts_Model_Order
     */
    public static function instance ()
    {
    	return parent::_instance(__CLASS__);
    }
    
}

// End ^ LF ^ encoding

