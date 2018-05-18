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

class OrderController extends BtsAdminAbstract
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
        $key = trim($this->input->get('key',null));
        $status = $this->input->get('status', null);
        $goods_pattern = $this->input->get('goods_pattern', null);
        $goods_type= $this->input->get('goods_type', null);
        $goods_name= $this->input->get('goods_name', null);
        $start_ctime = $this->input->get('start_ctime', null);
        $end_ctime = $this->input->get('end_ctime', null);
        
        $page = $page > 0 ? $page + 1 : 1;
        $perpage = $this->input->get('pageSize', $this->perpage);
        $offset = ($page - 1) * $perpage;
        
        $where = "order_status <> ''";
        if (isset($key) && $key != null) {
            $where .= " and (username LIKE '%" . $key . "%' OR phone LIKE '%" . $key . "%' OR order_no LIKE '%" . $key . "%')";
        }

        if (isset($status) && $status != null) {
         /*    if($status==1){
                $where .= " and order_status in " . '(2,3,4)';
            }
            if($status==2){
                $where .= " and order_status = 1" ;
            } */
               $where .= " and order_status = ".$status;
        }
        if (isset($goods_name) && $goods_name != null) {
            $where .= " and goods_name = "."'{$goods_name}'";
        }
        if (isset($goods_pattern) && $goods_pattern != null) {
                $where .= " and goods_pattern = ".$goods_pattern;
        }
        if (isset($goods_type) && $goods_type != null) {
            $where .= " and goods_type = ".$goods_type;
        }
        if (isset($start_ctime) && $start_ctime != null) {
            $where .= " and ctime >= '" . $start_ctime . "'";
        }
        if (isset($end_ctime) && $end_ctime != null) {
            $where .= " and ctime <= '" . $end_ctime . "'";
        }
        $order = 'order_id DESC';
        if ($ordername) {
            $order = $ordername . " " . $orderby;
        }
        
        $listing = Bts_Model_Order::instance()->fetchByWhere($where, $order, $perpage, $offset);
        if(!empty($listing)){
            foreach ($listing as &$v){
                if($v['phone'] == null){
                    $v['phone'] = '';
                }
                switch ($v['order_status']){
                case 1:
                    $v['order_status_name']='预约中';
                    break;
                case 2:
                    $v['order_status_name']='计息中';
                    break;
                case 3:
                    $v['order_status_name']='已结息';
                break;
                case 4:
                    $v['order_status_name']='已兑付';
                    break;
                case 5:
                    $v['order_status_name']='已取消';
                    break;
                }
                
                switch ($v['goods_pattern']){
                    case 1:
                        $v['goods_pattern_name']='新手';
                        break;
                    case 2:
                        $v['goods_pattern_name']='直购';
                        break;
                    case 3:
                        $v['goods_pattern_name']='预约';
                        break;
                }
        
             }
         } 
        $data['listing'] = $listing ? $listing : array();
        $data['count'] = Bts_Model_Order::instance()->getCount($where);
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        $data['order_type'] = $order_type;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'order.index');
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
        
        $order_id = (int) $this->input->query('order_id');
        
        if (! $data = Bts_Model_Order::instance()->fetchByPK($order_id)) {
            $this->setStatus(1);
            $this->setError('查无此订单');
            return self::RS_SUCCESS;
        }
        
        $user_id = (integer) $data[0]['userid'];
        $user = $user_id ? Cas_Model_User::instance()->getUserByUserid($user_id) : "";
        $goods = Bts_Model_Order_Items::instance()->fetchByWhere('order_id=' . $order_id);
        
        $data[0]['ship_status'] = Bts_Model_Order::instance()->ship_status[$data[0]['ship_status']];
        $data[0]['pay_type'] = Bts_Model_Order::instance()->pay_type[$data[0]['pay_type']];

        $data['logistics'] = Bts_Model_Logistics::instance()->fetchByWhere("1 = 1", "logistics_id DESC",10, 0);
        $data['user'] = $user[0];
        $data['goods'] = $goods;
        $data['data'] = $data[0];
        $data['order_id'] = $order_id;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'order.edit');
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
    			Bts_Model_Order::instance()->updateForEntity($set['data'], $set['data']['order_id']);
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
     * 保存菜单－校验
     */
    private function _validate()
    {
    	$res = array('status' => 0, 'error' => null, 'data' => null);
    
    	$res['data'] = array(
    			'order_id' => $this->input->post('order_id'),
    			'status' => $this->input->post('status'),
    			'pay_status' => $this->input->post('pay_status'),
    	        'logistics_id' => $this->input->post('logistics_id'),
    	        'logistics_number' => $this->input->post('logistics_number'),
    			'remark' => $this->input->post('remark')
    	);
    
    	/* 数据验证 */
    	if (empty($res['data']['order_id']) || empty($res['data']['order_id'])) {
    		$res['status'] = 1;
    		$res['error'] = '非法操作，请重试';
    		return $res;
    	}
    
    	return $res;
    }
    
    /**
     * 订单详情
     */
    public function detail()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        $order_id = (int) $this->input->query('order_id');
        
        if (! $order = Bts_Model_Order::instance()->fetchByPK($order_id)) {
            $this->setStatus(1);
            $this->setError('查无此订单');
            return self::RS_SUCCESS;
        }
        $order = $order[0];
        $goods = Goods_Model_List::instance()->fetchByPk($order['goods_id']);
        //理财期限
        if($order['start_time'] && $order['end_time']){
            $a = strtotime($order['start_time']);
            $b = strtotime($order['end_time']);
            $days=round(($b-$a)/3600/24)+1;
        }
        if(!empty($order)){
            //理财期限
            $order['financial_period'] = $days;
            //产品佣金比例
            $order['goods_broratio'] = $goods[0]['goods_broratio']*0.01*100 .'%';
            //1：新手 2:直购 3：预约
            if($order['goods_pattern'] == 1){
                $order['goods_pattern'] = '新手';
            }elseif($order['goods_pattern'] == 2){
                $order['goods_pattern'] = '直购';
            }elseif($order['goods_pattern'] == 3){
                $order['goods_pattern'] = '预约';
            }
            
            //1债权 2保险 3资管 4基金
            if($order['goods_type'] == 1){
                $order['goods_type'] = '债权';
            }elseif($order['goods_type'] == 2){
                $order['goods_type'] = '保险';
            }elseif($order['goods_type'] == 3){
                $order['goods_type'] = '资管';
            }elseif($order['goods_type'] == 4){
                $order['goods_type'] = '基金';
            }
            
            //1：预约中 2：计息中 3：已结息 4：已兑付
            if($order['order_status'] == 1){
                $order['order_status'] = '预约中';
            }elseif($order['order_status'] == 2){
                $order['order_status'] = '计息中';
            }elseif($order['order_status'] == 3){
                $order['order_status'] = '已结息';
            }elseif($order['order_status'] == 4){
                $order['order_status'] = '已兑付';
            }elseif($order['order_status'] == 5){
                $order['order_status'] = '已取消';
            }
        }
        $data = $order;
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'order.detail');	
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 扔进回收站
     */
    public function trash ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $issuc= Bts_Model_Order::instance()->updateForEntity(array('is_del' => 1), $this->input->post('id'));
        if (! empty($issuc) ) {
            $this->setError('更新成功');
        } else {
            $this->setStatus(1);
            $this->setError('更新订单状态失败 ');
        }
    
        return self::RS_SUCCESS;
    }
    
    /*取消订单*/
    public function delete(){
        $this->addResult(self::RS_SUCCESS, 'json');
        /* 接收参数  */
        $order_id = $this->input->query('order_id', null);
        /* 状态修改  */
        $arr = array(
            'order_status' => 5,
        );
        $result = Bts_Model_Order::instance()->update($arr, "order_id={$order_id}");
        if (empty($result) ) {
            $this->setStatus(1);
            $this->setError('取消订单失败');
            return self::RS_SUCCESS;
        }
        
        return self::RS_SUCCESS;
    }
    
    /**
     * 订单生效
     */
    public function deal ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        /* 接收参数  */
        $start_time = $this->input->query('start_time', null);
        $end_time = $this->input->query('end_time', null);
        $pay_time = $this->input->query('pay_time', null);
        $cash_time = $this->input->query('cash_time', null);
        $order_id = $this->input->query('order_id', null);
        
        /* 状态修改  */
        $arr = array(
                'start_time' => $start_time,
                'end_time' => $end_time,
                'pay_time' => $pay_time,
                'cash_time' => $cash_time,
                'order_status' => 2,
                'is_pay' => 1,
        );
        //产品剩余额度
        $goods_order = Bts_Model_Order::instance()->fetchByWhere("order_id = '{$order_id}' and is_del=0 and is_pay=0");
        $goods_order = $goods_order[0];
        $buy_money = $goods_order['buy_money'];
        $goods = Goods_Model_List::instance()->fetchByWhere("goods_id = '{$goods_order['goods_id']}' and is_del=0");
        $spare_fee = $goods[0]['spare_fee']-$buy_money;
        $goods_id = Goods_Model_List::instance()->update(array('spare_fee'=>$spare_fee),"goods_id ='{$goods_order['goods_id']}' and is_del=0");
        //是否售罄
        $goods_new = Goods_Model_List::instance()->fetchByWhere("goods_id = '{$goods_order['goods_id']}' and is_del=0");
        if($goods_new[0]['spare_fee']<$goods_new[0]['low_pay']){
            $goods_new_id =  $goods_id = Goods_Model_List::instance()->update(array('goods_status'=>2),"goods_id = '{$goods_order['goods_id']}' and is_del=0");
        }
        //购买人数
        self::addOne($goods_order['goods_id']);
        //佣金处理,新手类产品不存在佣金
        self::brokerage($goods_order['order_no']);
        //将用户置为非新手
        Cas_Model_User::instance()->update(array('is_buy'=>1),"userid = {$goods_order['userid']}");
        //理财天数
        $a = strtotime($arr['start_time']);
        $b = strtotime($arr['end_time']);
        $days=round(($b-$a)/3600/24)+1;
        $arr['bts_yield'] = round(($goods_order['yield']/365)*0.01*$days*$goods_order['buy_money'],2);
        
        $result = Bts_Model_Order::instance()->update($arr, "order_id={$order_id}");

        return self::RS_SUCCESS;
    }
    //购买人数加1
    public static function addOne($goods_id){
        $good = Goods_Model_List::instance()->fetchByWhere("goods_id='{$goods_id}'");
        $num = $good[0]['buy_num']+1;
        Goods_Model_List::instance()->update(array('buy_num'=>$num),"goods_id = '{$goods_id}'");
    }
    //计算用户余额  不变
    public static function calculate_asset($userid){
        $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
        $asset = $ExistUser[0]['asset'];
        return $asset;
    }
    //计算用户余额 +
    public static function calculate_asset_plus($userid,$total_fee){
        $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
        $asset = $ExistUser[0]['asset'] +$total_fee;
        return $asset;
    }
    //计算用户余额-
    public static function calculate_asset_reduce($userid,$total_fee){
        $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
        $asset = $ExistUser[0]['asset'] -$total_fee;
        return $asset;
    }
    //资金流水表
    public static function record_log($asset,$userid,$order_no,$total_fee,$status,$type,$pay_type,$order_id){
        $data['flow_asset'] = $asset;
        $data['userid'] = $userid;
        $data['order_no'] = $order_no;
        $data['money'] = $total_fee;
        $data['status'] = $status;
        $data['ctime'] = date('Y-m-d H:i:s');
        $data['mtime'] = date('Y-m-d H:i:s');
        $data['type'] = $type;
        $data['pay_type'] = $pay_type;
        $data['order_id'] = $order_id;
        $log = Cas_Model_Record_Log::instance()->addForEntity($data);
        if(!$log){
            return false;
        }
        return $log;
    }
//佣金处理-预约类
	public static function brokerage($order_no)
	{
         //查订单
         $goods_order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$order_no}'");
         //查用户
         $user_order =Cas_Model_User::instance()->fetchByWhere("userid = '{$goods_order[0]['userid']}'");
         //查产品
         $good_pay = Goods_Model_List::instance()->fetchByWhere("goods_id='{$goods_order[0]['goods_id']}'");
      
         if($good_pay['is_new']!=1){
             //佣金
             $parents =  Cas_Model_User::instance()->fetchParents($goods_order[0]['userid']);
             if(!empty($parents)){
                 //一级客户
                 if($parents[0]['one']){
                     $data['userid'] = $parents[0]['one'];//用户id
                     $data['user_grade'] = 1;//客户等级
                     //查一级客户
                     $user_one = Cas_Model_User::instance()->fetchByWhere("userid ='{$parents[0]['one']}' and status=0");
                     $data['username'] = $user_order[0]['username'];//客户名称
                     //var_dump( $data['username']);die;
                     //查订单
                     $order_pay = Bts_Model_Order::instance()->fetchByWhere("order_no='{$order_no}'");
                     $data['order_id'] = $order_pay[0]['order_id'];//订单id
                     $data['investment_amount'] = $order_pay[0]['buy_money'];//投资金额
                     $data['order_time'] = $order_pay[0]['ctime'];//下单时间
                     //查佣金设定表
                     $brokerage_setting = Brokerage_Model_Setting::instance()->fetchByWhere("1=1");
                     //产品佣金比例*佣金设定比例
                     $data['brokerage_ratio'] = $good_pay[0]['goods_broratio']*0.01*$brokerage_setting[0]['first_brokerage'];
                     //提成计算
                     $data['expected_money'] = round($brokerage_setting[0]['first_brokerage']*0.01*$order_pay[0]['buy_money']*$good_pay[0]['goods_broratio']*0.01,2);//预计提成
                     $data['brokerage_status'] = 1;//佣金状态
                     Cas_Model_User_Brokerage::instance()->addForEntity($data);
                 }
                 //二级客户
                 if($parents[0]['two']){
                     $data['userid'] = $parents[0]['two'];//用户id
                     $data['user_grade'] = 2;//客户等级
                     //查一级客户
                     $user_one = Cas_Model_User::instance()->fetchByWhere("userid ='{$parents[0]['two']}' and status=0");
                     $data['username'] = $user_order[0]['username'];//客户名称
                     //var_dump( $data['username']);die;
                     //查订单
                     $order_pay = Bts_Model_Order::instance()->fetchByWhere("order_no='{$order_no}'");
                     $data['order_id'] = $order_pay[0]['order_id'];//订单id
                     $data['investment_amount'] = $order_pay[0]['buy_money'];//投资金额
                     $data['order_time'] = $order_pay[0]['ctime'];//下单时间
                     //产品佣金比例*佣金设定比例
                     $data['brokerage_ratio'] = $good_pay[0]['goods_broratio']*0.01*$brokerage_setting[0]['first_brokerage'];
                     //查佣金设定表
                     $brokerage_setting = Brokerage_Model_Setting::instance()->fetchByWhere("1=1");
                     //提成计算
                     $data['expected_money'] = round($brokerage_setting[0]['second_brokerage']*0.01*$order_pay[0]['buy_money']*$good_pay[0]['goods_broratio']*0.01,2);//预计提成
                     $data['brokerage_status'] = 1;//佣金状态
                     Cas_Model_User_Brokerage::instance()->addForEntity($data);
                 }
                 //三级客户
                 if($parents[0]['three']){
                     $data['userid'] = $parents[0]['three'];//用户id
                     $data['user_grade'] = 3;//客户等级
                     //查一级客户
                     $user_one = Cas_Model_User::instance()->fetchByWhere("userid ='{$parents[0]['three']}' and status=0");
                     $data['username'] = $user_order[0]['username'];//客户名称
                     //var_dump( $data['username']);die;
                     //查订单
                     $order_pay = Bts_Model_Order::instance()->fetchByWhere("order_no='{$order_no}'");
                     $data['order_id'] = $order_pay[0]['order_id'];//订单id
                     $data['investment_amount'] = $order_pay[0]['buy_money'];//投资金额
                     $data['order_time'] = $order_pay[0]['ctime'];//下单时间
                     //查佣金设定表
                     $brokerage_setting = Brokerage_Model_Setting::instance()->fetchByWhere("1=1");
                     //产品佣金比例*佣金设定比例
                     $data['brokerage_ratio'] = $good_pay[0]['goods_broratio']*0.01*$brokerage_setting[0]['first_brokerage'];//产品佣金比例
                     //提成计算
                     $data['expected_money'] = round($brokerage_setting[0]['third_brokerage']*0.01*$order_pay[0]['buy_money']*$good_pay[0]['goods_broratio']*0.01,2);//预计提成
                     $data['brokerage_status'] = 1;//佣金状态
                     Cas_Model_User_Brokerage::instance()->addForEntity($data);
                 }
             }
         }
	}
    
}

// End ^ Native EOL ^ UTF-8