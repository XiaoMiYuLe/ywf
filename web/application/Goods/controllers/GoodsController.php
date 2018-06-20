<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GoodsController extends IndexAbstract {

    /**
     * 商品列表页
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //7天平台数据
        $time = strtotime("-1 week");
        $time1 = date("Y-m-d", $time);
        $where = "transfer_status=0 and is_pay=1 and goods_id not in(109,117) and pay_time>'{$time1}'";
        $order = array(
            'pay_time desc'
        );

        $orders = Bts_Model_Order::instance()->fetchByWhere($where, $order, null, null, null);
        $data['time'] = date("Y-m-d");
        $data['time'] = substr($data['time'], 2, 9);
        $data['count'] = count($orders);
        if (!empty($orders)) {
            foreach ($orders as $k => &$v) {
                $data['buy_money'] +=$v['buy_money'];
            }
            $data['buy_money'] = number_format($data['buy_money'], 2);
        } else {
            $data['buy_money'] = 0.00;
        }
        //投资动态，最新的7条订单

        $order_new = Bts_Model_Order::instance()->fetchByWhere('transfer_status=0 and is_pay=1 and goods_id not in(109,117)', 'pay_time desc', 7, 0, null);
        if (!empty($order_new)) {
            foreach ($order_new as $k => &$v) {
                $user = Cas_Model_User::instance()->fetchByWhere("userid = '{$v['userid']}'");
                $v['phone'] = substr_replace($user[0]['phone'], '****', 3, 4);
            }
        }

        $data['order_new'] = $order_new ? $order_new : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'goods.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /*
     * 加载理财产品
     * */

    public function getGoodsContent() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $arr = array(
            'userid' => $_SESSION['userid'],
            'p' => ($this->input->query('pageIndex')) + 1,
            'psize' => $this->input->query('pageSize'),
            'tag' => $this->input->query('tag'),
            'order' => $this->input->query('order'),
        );

        $result = Api_Goods_GetGoodsList::run($arr);

        return $result['data'];
        return self::RS_SUCCESS;
    }

    /*
     * 加载转让产品
     * */

    public function GetTransferList() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $arr = array(
            'p' => ($this->input->query('pageIndex')) + 1,
            'psize' => $this->input->query('pageSize'),
        );

        $result = Api_Two_GetTransferList::run($arr);

        return $result['data'];
        return self::RS_SUCCESS;
    }

    /**
     * 商品详情页
     */
    public function detail() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $goods_id = $this->input->get('goods_id');


        $userid = $_SESSION['userid'];
        $arr = array(
            'goods_id' => $goods_id,
            'userid' => $userid,
        );

        $result = Api_Goods_GetGoodsDetail::run($arr);

        if ($result['status'] == 1) {
            throw new Zeed_Exception('请求失败');
        }

        $data['goods'] = $result['data'];

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'goods.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 产品转让详情页
     */
    public function trdetail() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $arr = array(
            'order_id' => $this->input->get('order_id'),
        );

        $result = Api_Two_GetTransferDetail::run($arr);

        if ($result['status'] == 1) {
            throw new Zeed_Exception('请求失败');
        }

        $data['goods'] = $result['data'];
        $data['goods']['order_id'] = $arr['order_id'];

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'goods.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /* 投资记录 */

    public function record() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $goods_id = $this->input->query('goods_id');

        //params
        $params = array(
            'goods_id' => $goods_id,
            'p' => ($this->input->query('pageIndex')) + 1,
            'psize' => $this->input->query('pageSize'),
        );

        //request
        $result = Api_Goods_BuyRecordReal::run($params);

        if (!empty($result['data']['content'])) {
            foreach ($result['data']['content'] as $k => &$v) {
                $v['phone'] = substr_replace($v['phone'], '****', 3, 4);
            }
        }

        return $result['data'];
        return self::RS_SUCCESS;
    }

    /* 投资状态,下单 */

    public function Investment() {
        $this->addResult(self::RS_SUCCESS, 'json');

        if (!Cas_Authorization::getLoggedInUserid()) {
            //已登录
            $this->setStatus(2);
            $this->setError("您还没有登录！");
            return self::RS_SUCCESS;
        }

        //params
        $params = array(
            'userid' => $_SESSION['userid'],
        );

        //request
        $result = Api_Goods_ConfirmInvestment::run($params);

        if ($result . status == 0) {
            if ($result['data']['is_tiecard'] == 0) {
                $this->setStatus(3);
                $this->setError("您还未绑定银行卡，请先绑定");
                return self::RS_SUCCESS;
            }

            if ($result['data']['is_pay_pwd'] == 0) {
                $this->setStatus(4);
                $this->setError("您尚未设定交易密码，请先设定");
                return self::RS_SUCCESS;
            }

            if (($result['data']['is_ecoman'] == 0 && empty($result['data']['parent'])) || ($result['data']['is_ecoman'] == 0 && !empty($result['data']['parent'])) && $result['data']['parentis_ecoman'] == 0) {
                $this->setStatus(5);
                $this->setError("非常抱歉，您不是经纪人推荐注册，所以无法购买产品，请致电客服400-1369-998咨询");
                return self::RS_SUCCESS;
            }
        }

        $goods_id = $this->input->post('g');
        $buy_money = $this->input->post('buy_money');
        $transfer_order_id = $this->input->post('order_id');

        $goods = Goods_Model_List::instance()->fetchByWhere("goods_id='{$goods_id}'");
        $user = Cas_Model_User::instance()->fetchByWhere("userid='{$params['userid']}'");
        if ($goods[0]['is_new'] == 1) {
            if ($user[0]['is_buy'] == 1) {
                $this->setStatus(1);
                $this->setError("您已经不是新手了，不能购买新手产品");
                return self::RS_SUCCESS;
            }
        }

        //预约类产品
        if ($goods[0]['goods_pattern'] == 3) {
            $arr = array(
                'goods_id' => $goods_id,
                'buy_money' => $buy_money,
                'goods_broratio' => $goods[0]['goods_broratio'],
                'goods_name' => $goods[0]['goods_name'],
                'goods_pattern' => $goods[0]['goods_pattern'],
                'goods_type' => $goods[0]['goods_type'],
                'yield' => $goods[0]['yield'],
                'userid' => $_SESSION['userid'],
                'financial_period' => $goods[0]['financial_period'],
            );

            $order = Api_Order_MakeOrder::run($arr);
            if ($order['status'] == 0) {
                $this->setStatus(6);
                $this->setError("预约成功");
                return self::RS_SUCCESS;
            } else {
                return $order;
            }
        }

        //新手产品
        if ($goods[0]['goods_pattern'] == 1) {

            $arr = array(
                'goods_id' => $goods_id,
                'buy_money' => $buy_money,
                'goods_broratio' => $goods[0]['goods_broratio'],
                'goods_name' => $goods[0]['goods_name'],
                'goods_pattern' => $goods[0]['goods_pattern'],
                'goods_type' => $goods[0]['goods_type'],
                'yield' => $goods[0]['yield'],
                'financial_period' => $goods[0]['financial_period'],
                'userid' => $_SESSION['userid'],
            );
        }
        //债权产品
        if ($goods[0]['goods_pattern'] == 2) {

            $start_time = date('Y-m-d'); //起息时间
            $y = explode('-', $start_time);
            $fulltime = mktime(0, 0, 0, $y[1], $y[2] + $goods[0]['financial_period'] - 1, $y[0]);
            $atime = mktime(0, 0, 0, $y[1], $y[2] + $goods[0]['financial_period'], $y[0]);
            $end_time = date('Y-m-d', $fulltime); //结息时间
            $deal_date = date('Y-m-d', $atime); //兑付时间
            $arr = array(
                'goods_id' => $goods_id,
                'buy_money' => $buy_money,
                'cash_time' => $deal_date,
                'end_time' => $end_time,
                'goods_broratio' => $goods[0]['goods_broratio'],
                'goods_name' => $goods[0]['goods_name'],
                'goods_pattern' => $goods[0]['goods_pattern'],
                'goods_type' => $goods[0]['goods_type'],
                'is_now' => $goods[0]['is_now'],
                'yield' => $goods[0]['yield'],
                'userid' => $_SESSION['userid'],
                'transfer_order_id' => $transfer_order_id,
            );


            if ($arr['is_now'] == 2) {
                $arr['start_time'] = $goods[0]['start_time'];
            }
        }

        $order = Api_Order_MakeOrder::run($arr);
        return $order;
    }

    public function tiyan() {
        $this->addResult(self::RS_SUCCESS, 'json');
        if (!Cas_Authorization::getLoggedInUserid()) {
            //已登录
            $this->setStatus(2);
            $this->setError("您还没有登录！");
            return self::RS_SUCCESS;
        }

        //params
        $params = array(
            'userid' => $_SESSION['userid'],
        );

        //request
        $result = Api_Goods_ConfirmInvestment::run($params);

        if ($result . status == 0) {
            if ($result['data']['is_tiecard'] == 0) {
                $this->setStatus(3);
                $this->setError("您还未绑定银行卡，请先绑定");
                return self::RS_SUCCESS;
            }

            if ($result['data']['is_pay_pwd'] == 0) {
                $this->setStatus(4);
                $this->setError("您尚未设定交易密码，请先设定");
                return self::RS_SUCCESS;
            }

            if (($result['data']['is_ecoman'] == 0 && empty($result['data']['parent'])) || ($result['data']['is_ecoman'] == 0 && !empty($result['data']['parent'])) && $result['data']['parentis_ecoman'] == 0) {
                $this->setStatus(5);
                $this->setError("非常抱歉，您不是经纪人推荐注册，所以无法购买产品，请致电客服400-1369-998咨询");
                return self::RS_SUCCESS;
            }
        }

        $arr = array(
            'goods_id' => $this->input->post('goods_id'),
            'userid' => $_SESSION['userid'],
        );

        $order = Api_Two_UseExperienceByUser::run($arr);
        $order['data'] = '';
        return $order;
    }

}
