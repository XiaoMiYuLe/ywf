<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Cas
 * @package    Cas_Model
 * @subpackage Cas_Model_User
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @version    SVN: $Id$
 */

class Cas_Model_Record_Log extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'record_log';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'record_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'cas_';

    /**
     * @return Cas_Model_Record_Log
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

    /*
     * 用户资金变更
     */
    protected function doUserMoney($userid, $new_asset, $new_freeze_money, $old_asset, $old_freeze_money)
    {
        $data['asset'] = $new_asset;
        $data['freeze_money'] = $new_freeze_money;
        return Cas_Model_User::instance()->update($data, array('userid = ?' => (int)$userid, 'asset = ?' => $old_asset, 'freeze_money = ?' => $old_freeze_money));
    }

    /**
     * 资金冻结方法
     */
    public function freezeMoney($userid = 0, $order_no = '', $money = 0)
    {
        $user = Cas_Model_User::instance()->getUserByUserid($userid);
        $freeze_money = $user['freeze_money'] + $money;//冻结金额
        $flow_asset = $user['asset'] - $money;//账户金额
        if (!$this->doUserMoney($user['userid'], $flow_asset, $freeze_money, $user['asset'], $user['freeze_money'])) {
            return false;
        }
        $arrs['userid'] = $user['userid'];
        $arrs['flow_asset'] = $flow_asset;
        $arrs['order_no'] = $order_no;
        $arrs['money'] = $money;
        $arrs['status'] = "-";
        $arrs['ctime'] = NOW_TIME;
        $arrs['pay_type'] = 1;  // 支付方式    余额支付
        $arrs['type'] = 9;      //记录类型  冻结操作
        return $this->addForEntity($arrs);
    }

    /*
     * 资金解冻方法
     */
    public function unFreezeMoney($userid = 0, $order_no = '', $money = 0)
    {
        $user = Cas_Model_User::instance()->getUserByUserid($userid);
        $freeze_money = $user['freeze_money'] - $money;//冻结金额
        $flow_asset = $user['asset'] + $money;//账户金额
        if (!$this->doUserMoney($user['userid'], $flow_asset, $freeze_money, $user['asset'], $user['freeze_money'])) {
            return false;
        }
        $arrs['userid'] = $user['userid'];
        $arrs['flow_asset'] = $flow_asset;
        $arrs['order_no'] = $order_no;
        $arrs['money'] = $money;
        $arrs['status'] = "+";
        $arrs['ctime'] = NOW_TIME;
        $arrs['pay_type'] = 1;  // 支付方式    余额支付
        $arrs['type'] = 10;      //记录类型  解冻操作
        return $this->addForEntity($arrs);
    }

    /*
     * 减少资金
     */
    public function reduceMoney($userid = 0, $order_no = '', $money = 0, $type = 2)
    {
        $user = Cas_Model_User::instance()->getUserByUserid($userid);
        $freeze_money = $user['freeze_money'];//冻结金额
        $flow_asset = $user['asset'] - $money;//账户金额
        if (!$this->doUserMoney($user['userid'], $flow_asset, $freeze_money, $user['asset'], $user['freeze_money'])) {
            return false;
        }
        $arrs['userid'] = $user['userid'];
        $arrs['flow_asset'] = $flow_asset;
        $arrs['order_no'] = $order_no;
        $arrs['money'] = $money;
        $arrs['status'] = "-";
        $arrs['ctime'] = NOW_TIME;
        $arrs['pay_type'] = 1;  // 支付方式    余额支付
        $arrs['type'] = $type;      //记录类型  2提现扣款   3投资扣款
        return $this->addForEntity($arrs);
    }

    /*
     * 增加资金
     */
    public function addMoney($userid = 0, $order_no = '', $money = 0, $type = 1)
    {
        $user = Cas_Model_User::instance()->getUserByUserid($userid);
        $freeze_money = $user['freeze_money'];//冻结金额
        $flow_asset = $user['asset'] + $money;//账户金额
        if (!$this->doUserMoney($user['userid'], $flow_asset, $freeze_money, $user['asset'], $user['freeze_money'])) {
            return false;
        }
        $arrs['userid'] = $user['userid'];
        $arrs['flow_asset'] = $flow_asset;
        $arrs['order_no'] = $order_no;
        $arrs['money'] = $money;
        $arrs['status'] = "+";
        $arrs['ctime'] = NOW_TIME;
        $arrs['pay_type'] = 1;  // 支付方式    余额支付
        $arrs['type'] = $type;      //记录类型 1充值  11满标增资
        return $this->addForEntity($arrs);
    }

}

// End ^ LF ^ encoding
