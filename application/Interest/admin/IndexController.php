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
class IndexController extends InterestAdminAbstract
{
    public $perpage = 15;

    /**
     * 结息列表管理
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        $username = $this->input->get('username');
        $idcard = $this->input->get('idcard',0);
        $start_time = $this->input->get('start_time');
        $end_time = $this->input->get('end_time');
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
        
            $where = "1 = 1";
            if ($key) {
                $where .= " and order_no = '{$key}'";
            }
            if (isset($start_time) && $start_time != null) {
                $where .= " and ctime >= '" . $start_time . "'";
            }
            if (isset($end_time) && $end_time != null) {
                $where .= " and ctime <= '" . $end_time . "'";
            }
            
            $wheres = "status = 0";
            /* 搜索用户名 */
            if ($username) {
                $wheres .= " and username = '{$username}'";
            }
            if ($idcard) {
                $wheres .= " and idcard = {$idcard}";
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
            
            $order = 'stime DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
        
            $interest = Interest_Model_Settlement::instance()->fetchByWhere($where, $order, $perpage, $offset);
            if(!empty($interest)){
                foreach($interest as $k=>&$v){
                    /* 用户名称 */
                    if(!empty($v['userid'])){
                        $user = Cas_Model_User::instance()->fetchByWhere("userid = {$v['userid']}");
                        $v['username'] = $user[0]['username'];
                    }
                    
                    /* 产品名称  */
                    if(!empty($v['order_no'])){
                        $order = Bts_Model_Order::instance()->fetchByWhere("order_no = {$v['order_no']}");
                        $v['goods_name'] = $order[0]['goods_name'];
                        $v['buy_moeny'] = $order[0]['buy_money'];
                    }
                }
            }
            
            $data['count'] = Interest_Model_Settlement::instance()->getCount($where);
            $data['interest'] = $interest ? $interest : array();
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