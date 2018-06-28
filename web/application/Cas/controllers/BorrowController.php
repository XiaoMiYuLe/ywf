<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BorrowController extends CasAbstract
{

    /**
     * 我的理财
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $data = array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'borrow.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }


    /**
     * 我的理财产品详情
     */
    public function detail()
    {

    }

    /**
     * 获取我的理财列表（订单列表）
     */
    public function getOrderList()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if ($this->input->isAJAX()) {
            $tag = $this->input->get('tag', 1);
            $pageIndex = $this->input->query('pageIndex');//当前第几页
            $pageSize = $this->input->query('pageSize'); //每页显示数
            if ($tag == 1) {
                $status = '1,2';
            } else {
                $status = '0,3,4,5';
            }
            $where = 'invest_user_id = ' . $_SESSION['userid'] . ' and invest_status in (' . $status . ')';
            $offset = $pageIndex * $pageSize;
            $order = 'bil_id desc';
            $list = P2P_Model_BorrowInvestList::instance()->fetchByWhere($where, $order, $pageSize, $offset);
            if ($list) {
                $b_model = P2P_Model_Borrow::instance();
                foreach ($list as $k => $one) {
                    $b_list = $b_model->fetchByWhere('borrow_id = ' . $one['borrow_id']);
                    $list[$k]['borrow_time_limit'] = $b_list[0]['borrow_time_limit'];
                }
            } else {
                $list = array();
            }
            $count = (int)P2P_Model_BorrowInvestList::instance()->getCount($where);
            /**
             * 数据数组 总条数 当前页码 总页数 具体订单信息
             */
            $pageCount = ceil($count / $pageSize);
            $data = array(
                'totalnum' => $count,
                'currentpage' => $pageIndex,
                'totalpage' => $pageCount,
                'info' => $list,
            );
            return $data;
        }
        return self::RS_SUCCESS;
    }

    /**
     * 获取我的理财列表（订单列表）
     */
    public function getIncomeList()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if ($this->input->isAJAX()) {
            $bil_id = $this->input->get('bil_id', 1);
            $where = 'bil_id = ' . $bil_id;
            $list = P2P_Model_BorrowInvestIncomeList::instance()->fetchByWhere($where);
            $bi_list = P2P_Model_BorrowInvestList::instance()->fetchByWhere('bil_id = ' . $bil_id);
            $interest_limit = $bi_list[0]['interest_limit'];
            if ($list) {
                foreach ($list as $k => $one) {
                    $list[$k]['interest_limit'] = $interest_limit;
                    $list[$k]['sum_money'] = $one['corpus_money'] + $one['income_money'];
                }
            } else {
                $list = array();
            }
            return $list;
        }
        return self::RS_SUCCESS;
    }


    /**
     * 电子合同
     */
    public function getWord()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isAJAX()) {
            //$params
            $params = array(
                'order_no' => $this->input->post('order_no'),
            );

            //result
            $result = Api_Cas_ElectronicContract::run($params);

            return $result;
        }

        return self::RS_SUCCESS;
    }

    /* 发布转让 */

    public function publish()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if ($this->input->isAJAX()) {
            $params = array(
                'order_id' => $this->input->post('order_id'),
                'userid' => $_SESSION['userid'],
                'counter_money' => $this->input->post('counter_money'),
                'transfer_price' => $this->input->post('transfer_price'),
            );
            $result = Api_Two_PublishTransfer::run($params);
            return $result;
        }
        return self::RS_SUCCESS;
    }

    /* 撤销转让 */

    public function RevokeTransfer()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if ($this->input->isAJAX()) {
            $params = array(
                'order_id' => $this->input->post('order_id'),
                'userid' => $_SESSION['userid'],
            );
            $result = Api_Two_RevokeTransfer::run($params);
            return $result;
        }
        return self::RS_SUCCESS;
    }

}
