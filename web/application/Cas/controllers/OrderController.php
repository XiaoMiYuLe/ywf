<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OrderController extends CasAbstract {

    /**
     * 我的理财
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->setData('data', array());
        $this->addResult(self::RS_SUCCESS, 'php', 'order.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    public function p2p(){

    }

    /**
     * 我的理财产品详情
     */
    public function detail() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $order_no = $this->input->query('order_no');

        if (!$user = Cas_Model_User::instance()->fetchByWhere("userid = {$_SESSION['userid']} and status = 0")) {
            throw new Zeed_Exception('该用户不存在或已被冻结');
        }
        if(!$order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$order_no}' and userid = '{$user[0]['userid']}'")){
            throw new Zeed_Exception('该订单不存在');
        }

        // ajax
        //$params
        $params = array(
            'order_id' => $order[0]['order_id'], //订单号
        );

        //request
        $result = Api_Order_GetOrderDetail::run($params);

        if (!$result['data']) {
            throw new Zeed_Exception("该订单不存在！");
        }
        
        // 显示状态
        switch($result['data']['order_status']) {
            case 1:
                $show = '预约中';
                break;
            case 2:
                $show = '计息中';
                break;
            case 3:
                $show = '已结息';
                break;
            case 4:
                $show = '已兑付';
                break;
            default :
                $show = '';
                break;
        }
        
        $data['show'] = $show ? $show : '';
        $data['orderInfo'] = $result['data'] ? $result['data'] : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'order.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 获取我的理财列表（订单列表）
     */
    public function getOrderList() {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isAJAX()) {
            //params
            $params = array(
                'userid' => $_SESSION['userid'],
                'tag' => 1, //tag 1-预约中 2-计息中 3-已结息 4-已兑付
                'p' => ($this->input->query('pageIndex')) + 1, //当前第几页
                'psize' => $this->input->query('pageSize'), //每页显示数
                'tag' => $this->input->query('tag', 1), //1-我的理财 2-已兑付
            );
            //request
            $result = Api_Order_GetOrderList::run($params);

            if ($result['status'] == 0) {
                $result = $result['data'] ? $result['data'] : array();
            }

            return $result;
        }

        return self::RS_SUCCESS;
    }

    /**
     * 电子合同
     */
    public function getWord() {
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

    public function publish() {
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

    public function RevokeTransfer() {
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
