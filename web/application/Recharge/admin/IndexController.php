<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-3-21
 * @version    SVN: $Id$
 */
class IndexController extends RechargeAdminAbstract
{
    public $perpage = 15;

    /**
     * 充值列表管理
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $phone = trim($this->input->get('phone'));
        $recharge_status = $this->input->get('recharge_status');
        $username = $this->input->get('username');
        $idcard = $this->input->get('idcard',0);
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
        
            $where = "type=1";
            $order = "ctime DESC";
         /*    if ($recharge_status) {
                $where .= " and recharge_status = {$recharge_status}";
            }
             */
            $wheres = "1 = 1";
            /* 搜索用户名 */
            if ($username) {
                $wheres .= " and username like '%".$username."%'";
            }
            if ($idcard) {
                $wheres .= " and idcard = '{$idcard}'";
            }
            if ($phone) {
                $wheres .= " and phone = '{$phone}'";
            }
            if(!empty($wheres)){
                $user = Cas_Model_User::instance()->fetchByWhere($wheres);
                $arr = array();
                if ($user) {
                    foreach ($user as $k => $v) {
                        $arr[] = $v['userid'];
                    }
                }
                if (!empty($arr)) {
                    $arr = implode(',',$arr);
                    $where .= " and userid in (".$arr.")";
                } else {
                    $where .= " and userid in (0)";
                }
            }
            
            $recharge = Cas_Model_Record_Log::instance()->fetchByWhere($where, $order, $perpage, $offset);
            if(!empty($recharge)){
                foreach($recharge as $k=>&$v){
                    if(!empty($v['userid'])){
                        $user = Cas_Model_User::instance()->fetchByWhere("userid = {$v['userid']}");
                        $v['username'] = $user[0]['username'];
                        $v['phone'] = $user[0]['phone'];
                        if(!empty($user[0]['bank_id'])){
                            $bank = Cas_Model_Bank::instance()->fetchByWhere("bank_id = '{$user[0]['bank_id']}' and is_use=1");
                            $v['bank_name'] = $bank[0]['bank_name'];
                            $v['opening_bank'] = $bank[0]['opening_bank']?$bank[0]['opening_bank']:'';
                            $v['bank_no'] = $bank[0]['bank_no'];
                        }
                    }
                }
            }
            
            $data['count'] = Cas_Model_Record_Log::instance()->getCount($where);
            $data['recharge'] = $recharge ? $recharge : array();
        }
        
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
}

// End ^ Native EOL ^ UTF-8