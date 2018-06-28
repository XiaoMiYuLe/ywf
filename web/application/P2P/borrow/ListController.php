<?php

/**
 * 标的表
 */
class ListController extends IndexAbstract
{
    public $perpage = 15;

    /*
     * 格式化信息
     */
    public function dataFormat($data = null, $status = 0, $error_msg = '')
    {
        $new_data['status'] = $status;
        $new_data['error_msg'] = $error_msg;
        $new_data['data'] = $data;
        return $new_data;
    }

    /**
     * 标的展示列表
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        /* 接收参数 */
        $pageIndex = $this->input->get('pageIndex', 0);
        $pageSize = $this->input->get('pageSize', 15);
        $status = $this->input->get('status', "1,2,3,4,5");

        $offset = $pageIndex * $pageSize;
        $where = " borrow_status in (" . $status . ") ";
        $order = array('borrow_status asc', 'add_time desc');

        $field = array('borrow_id', 'borrow_name', 'total_money', 'raise_money', 'interest_rate', 'interest_type',
            'yield_rate', 'raise_day', 'borrow_status', 'borrow_time_limit', 'interest_limit', 'add_time');
        $list = P2P_Model_Borrow::instance()->fetchByWhere($where, $order, $pageSize, $offset, $field);
        $data['status'] = 0;
        $data['error'] = "";
        $data['list'] = $list ? $list : array();
        $data['count'] = P2P_Model_Borrow::instance()->getCount($where);

        return $data;
//        var_dump($data);
//        $this->setData('data', $data);
//        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
//        return parent::multipleResult(self::RS_SUCCESS);
//        $this->addResult(self::RS_SUCCESS, 'php', 'sign.in.php');
//        return self::RS_SUCCESS;
    }

    /**
     * 标的详情
     */
    public function detail()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        /* 接收参数 */
        $bid = $this->input->get('bid', 0);
        $b_list = P2P_Model_Borrow::instance()->fetchByWhere('borrow_id = ' . $bid);
        $borrow = null;
        if ($b_list) {
            $borrow = $b_list[0];
            $borrow['remainder_money'] = $borrow['total_money'] - $borrow['raise_money'];
            $borrow['progress_bar'] = ($borrow['raise_money'] / $borrow['total_money']) * 100;
            //募集结束时间
            $borrow['raise_time'] = date("Y-m-d H:i:s", strtotime("+5 day", strtotime($borrow['show_time'])));
        }
        $bi_list = P2P_Model_BorrowInfo::instance()->fetchByWhere('borrow_id = ' . $bid);
        $borrow_info = null;
        if ($bi_list) {
            $borrow_info = $bi_list[0];
        }
        $data['borrow'] = $borrow;
        $data['info'] = $borrow_info;
        var_dump($data);
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'borrow.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /* 投资记录 */
    public function record() {
        $this->addResult(self::RS_SUCCESS, 'json');
        /* 接收参数 */
        $borrow_id = $this->input->query('borrow_id');
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
    public function invest()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        if (!Cas_Authorization::getLoggedInUserid()) {
            //未登录
            return $this->dataFormat("", 2, "您还没有登录！");
        }
        $borrow_id = $this->input->post('bid');
        $buy_money = $this->input->post('money');
        //这个是测试时候获取的用户ID
        //$userid = $this->input->get('uid');

        $userid = $_SESSION['userid'];
        return P2P_Model_Borrow::instance()->invest($borrow_id, $userid, $buy_money);
    }

    //获取投资记录
    public function getInvestList()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        //获取标的ID
        $bid = $this->input->query('bid');
        $pageIndex = $this->input->query('pageIndex', 0);
        $pageSize = $this->input->query('pageSize', 15);
        if(!$bid){
            return $this->dataFormat("",1,"请输入标的号");
        }
        $offset = $pageIndex * $pageSize;
        $where = " borrow_id = " . $bid;
        $order = array('bil_id desc');
        $list = P2P_Model_BorrowInvestList::instance()->fetchByWhere($where, $order, $pageSize, $offset);
        if ($list) {
            $user_model = Cas_Model_User::instance();
            //获取用户名，并打码
            foreach ($list as $k => $one) {
                $user = $user_model->getUserByUserid($one['invest_user_id']);
                $list[$k]['phone'] = substr_replace($user['phone'], '******', 3, 6);
            }
        } else {
            $list = array();
        }
        $data['status'] = 0;
        $data['error'] = "";
        $data['pageIndex'] = $pageIndex;
        $data['pageSize'] = $pageSize;
        $data['list'] = $list;
        $data['count'] = P2P_Model_BorrowInvestList::instance()->getCount($where);
        return $data;
    }

    /* 投资状态,下单 */
    public function fullBorrow()
    {
        return P2P_Model_Borrow::instance()->fullBorrow();
    }


}

// End ^ LF ^ encoding
