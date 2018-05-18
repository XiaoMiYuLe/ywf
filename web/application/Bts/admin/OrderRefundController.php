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

class OrderRefundController extends BtsAdminAbstract
{
    public $perpage = 15;

    /**
     * 订单列表
    */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex');
        $key = $this->input->get('key');
        $status = $this->input->get('status');
        $start_ctime = $this->input->get('start_ctime',null);
        $end_ctime = $this->input->get('end_ctime',null);

        $page = $page > 0 ? $page+1 : 1;
        $perpage = $this->input->get('pageSize', $this->perpage);
        $offset = ($page - 1) * $perpage;
         
        $where = " is_del != 3";
        if($key){
            $where .= " and refund_sn like '%".$key."%'";
        }
        if ($status) {
            $where .= " and status = ".$status;
        }
        if (isset($start_ctime) && $start_ctime!=null) {
            $where .= " and ctime >= '".$start_ctime."'";
        }
        if (isset($end_ctime) && $end_ctime!=null) {
            $where .= " and ctime <= '".$end_ctime."'";
        }

        $order = 'refund_id DESC';
        if ($ordername) {
            $order = $ordername . " " . $orderby;
        }

        $listing = Bts_Model_Order_Refund::instance()->fetchByWhere($where, $order, $perpage, $offset);

        $data['listing'] = $listing ? $listing : array();
        $data['count'] = Bts_Model_Order_Refund::instance()->getCount($where);
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;

        /**
         * 支付状态:pay_status:0：未支付，1：已支付，2：已支付至担保，3：部分付款，4：部分退款，5：全额退款
         */
        if(! empty($data['listing'])){
            foreach ($data['listing'] as $k => $v){
                $goods = Bts_Model_Order_Items::instance()->fetchByWhere('item_id=' . $v['item_id']);
                $data['listing'][$k]['buy_num'] =$goods ? $goods[0]['buy_num'] : "";
                $data['listing'][$k]['goods_name'] = $goods ? $goods[0]['goods_name'] : "";
                $data['listing'][$k]['status'] = Bts_Model_Order::instance()->return_status[$v['status']];

            }
        }

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'orderrefund.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 订单详情
     */
    public function detail()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');

        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }

        $refund_id = (int) $this->input->query('refund_id');

        if (! $data = Bts_Model_Order_Refund::instance()->fetchByPK($refund_id)) {
            $this->setStatus(1);
            $this->setError('查无此订单');
            return self::RS_SUCCESS;
        }
        $order_id = (integer)$data[0]['order_id'];
        $orders = Bts_Model_Order::instance()->fetchByPK($order_id);
        $attachments = Bts_Model_Order_Attachment::instance()->getPath($data[0]['refund_id']);
        $goods = Bts_Model_Order_Items::instance()->fetchByWhere('item_id=' . $data[0]['item_id']);

        $data['data'] = $data[0];
        $data['data']['attachments'] = $attachments;
        $data['data']['goods_name'] = $goods ? $goods[0]['goods_name'] : "";
        $data['data']['status'] = Bts_Model_Order::instance()->return_status[$data['data']['status']];
        $data['data']['username'] = $orders[0]['username'];

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'orderrefund.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }


    /**
     * 修改
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }

        $refund_id = (int) $this->input->query('refund_id');

        if (! $data = Bts_Model_Order_Refund::instance()->fetchByPK($refund_id)) {
            $this->setStatus(1);
            $this->setError('查无此退货单');
            return self::RS_SUCCESS;
        }

        $data['data'] = $data[0];

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'orderrefund.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 修改 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                Bts_Model_Order_Refund::instance()->updateForEntity($set['data'], $set['data']['refund_id']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑订单失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }

        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 删除
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        $return_id = (int) $this->input->post('return_id');

        try {
            Bts_Model_Order_Refund::instance()->updateForEntity(array('is_del' => 3), $return_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除订单失败。错误信息 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }

        $this->setError('删除成功');
        return self::RS_SUCCESS;
    }


    /**
     * 保存菜单－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);

        $res['data'] = array(
                'refund_id' => $this->input->post('refund_id'),
                'status' => $this->input->post('status'),
                'reason' => $this->input->post('reason')
        );

        /* 数据验证 */
        if (empty($res['data']['refund_id']) || empty($res['data']['refund_id'])) {
            $res['status'] = 1;
            $res['error'] = '非法操作，请重试';
            return $res;
        }

        return $res;
    }

}

// End ^ Native EOL ^ UTF-8