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

/**
 * 获取转让详情
 */
class Api_Two_GetTransferDetail
{

    /**
     * 返回参数
     */
    protected static $_res = array(
            'status' => 0,
            'error' => '',
            'data' => ''
    );

    /**
     * 接口运行方法
     *
     * @param string $params            
     * @throws Zeed_Exception
     * @return string Ambigous multitype:number, multitype:number string ,
     *         unknown, multitype:>
     */
    public static function run ($params = null)
    {
        // 执行参数验证
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            try {
            	/*需要获取的产品相关参数*/
            	$filed = array(
            		'goods_id', //产品id
            		'goods_name', //产品名称
            		'all_fee', //'总额度',
            		'spare_fee',  //'剩余额度',
            		'is_now', //起息方式 1：购买成功当日 2：自定义
            		'is_new',
            		'start_time',//'起息日期',
            		'end_time',//'结息日期',
            		'deal_date',//'兑付日期',
            		'deal_way',  //'兑付方式：',
            		'yield', //'收益率%',
            		'goods_pattern', // '产品模式【1：新手 2:直购 3：预约】',
            		'goods_type',  //'产品类型：1债权 2保险 3资管 4资金 5信托',
            		'goods_status',//'产品状态：1 销售中 2 已售罄 3 已下架',
            		'financial_period', //'理财期限(天)',
            		'low_pay',  //'最低投资额(元)',
            		'high_pay',  //'最高投资额(元)',
            		'increasing_pay', //'递增金额',
            		'goods_broratio',//产品佣金比例',
            		'goods_detail',  //'详情',
            		'is_del', //'是否删除 0否  1是',
            		'ctime', //'创建时间',
            		'mtime',//'修改时间',
            		'buy_num',  //'购买人数',
            		'principal_way',  //'兑付方式',
            		'redeem_status',  //'提前赎回：1允许 2不允许',
            		'principal_status',//兑付本金：1:是2：否',
            		'deal_status',  //'是否结息：1是2否',
            		'comment', //'备注',
            		'safety', //'安全保障',
            		'is_hot', //'是否精选推荐'
            		'is_interest',//是否支持加息 1：是  2：否
            		 'is_voucher',//是否支持代金券 1：是 2:否
            	);
            	$where = "order_id = {$res['data']['order_id']}";
            	$order = Bts_Model_Order::instance()->fetchByWhere($where);
            	
            	if($order[0]['transfer_status']==2){
            	    throw new Zeed_Exception('该订单已在转让中');
            	}
            	
            	if($order[0]['transfer_status']==3){
            	    throw new Zeed_Exception('该订已转让');
            	}
            	
            	
            	/*查询产品信息*/
            	$goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$order[0]['goods_id']} and is_del = 0",null,null,null);
            	
                if (!$goods){
                	throw new Zeed_Exception('该产品不存在');
                } else {
                	if ($goods[0]['goods_status'] == 3) {
                		throw new Zeed_Exception('该产品已下架');
                	}
                	
                	//数据处理
                	//订单购买金额
                	$goods[0]['buy_money'] = (float)$order[0]['buy_money'];
                	//转让价
                	$goods[0]['transfer_price'] = $order[0]['transfer_price'];
                	$goods[0]['transfer_price_show'] = number_format($order[0]['transfer_price']);
                	 
                	//展示的预期年化收益率
                	$goods[0]['yield_show'] = round(($goods[0]['yield']*$order[0]['buy_money'])/$order[0]['transfer_price'],1);
                	
                	//剩余天数:当前时间距离转让区间上限 transfer_maxdate 的天数
                	$time = strtotime(date("Y-m-d"));
                	$transfer_maxdate  = strtotime($order[0]['transfer_maxdate']);
                	$goods[0]['distance_days'] = round(($transfer_maxdate-$time)/3600/24)+1;
                	
                	//持有天数展示
                	if($goods[0]['distance_order']==0){
                	    $goods[0]['distance_order_show'] = '随时可转让';
                	}else{
                	    $goods[0]['distance_order_show'] = '持有满'.$goods[0]['distance_order'].'天可转让';
                	}
                	

                	foreach ($goods as $k => &$v) {
                	    //新手专享的起息时间,结息时间,和兑付时间
                	    if($v['is_new'] == 1){
                	        $start_time = date('Y-m-d');//起息时间
                	        $y = explode('-', $start_time);
                	        $fulltime = mktime(0,0,0,$y[1],$y[2]+$v['financial_period']-1,$y[0]);
                	        $atime = mktime(0,0,0,$y[1],$y[2]+$v['financial_period'],$y[0]);
                	        $end_time = date('Y-m-d',$fulltime);//结息时间
                	        $deal_date = date('Y-m-d', $atime);//兑付时间
                	        $v['start_time'] = $start_time;
                	        $v['end_time'] = $end_time;
                	        $v['deal_date'] = $deal_date;   
                	        
                	    }elseif ($v['goods_pattern']==4){
                	        $start_time = date('Y-m-d');//起息时间
                	        $y = explode('-', $start_time);
                	        $fulltime = mktime(0,0,0,$y[1],$y[2]+$v['financial_period']-1,$y[0]);
                	        $atime = mktime(0,0,0,$y[1],$y[2]+$v['financial_period'],$y[0]);
                	        $end_time = date('Y-m-d',$fulltime);//结息时间
                	        $deal_date = date('Y-m-d', $atime);//兑付时间
                	        $v['start_time'] = $start_time;
                	        $v['end_time'] = $end_time;
                	        $v['deal_date'] = $deal_date;
                	    }else {
                	    	if ($v['is_now'] == 1) {
                	    		$v['start_time'] = '购买成功当日';
                	    	} elseif ($v['is_now'] == 2) {
                	    		$v['start_time'] = $v['start_time'];
                	    	}
                	    }
                	    
                		if ($v['all_fee'] > 0) {
                			$v['schedule'] = (($v['all_fee']-$v['spare_fee'])/$v['all_fee'])*100;
                		}
                		if ($v['schedule'] < 1 && $v['schedule'] > 0) {
                			$v['schedule'] = 1;
                		} else {
                			$v['schedule'] = floor($v['schedule']);
                		}
                		$v['all_fee_view'] = number_format($v['all_fee'],2);
                		$v['spare_fee_view'] = number_format($v['spare_fee'],2);
                		if ($res['data']['userid']) {
                			if (!$user = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
                				throw new Zeed_Exception('该用户不存在或已被冻结');
                			} else {
                			    $v['is_ecoman'] = $user[0]['is_ecoman'];
                			    
                				if ($user[0]['pay_pwd']) {
                					$v['is_pay_pwd'] = 1;
                				} else {
                					$v['is_pay_pwd'] = 0;
                				}
                				//绑卡的状态
                				if(!empty($user[0]['bank_id'])){
                				    $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$user[0]['bank_id']}' and is_use=1 and is_del=0");
                				    if(!empty($bank_info)){
                				        $v['is_tiecard']= 1;
                				    }else{
                				        $v['is_tiecard']= 0;
                				    }
                				}
                			}
                		}
                	}
                }
                
                if(!empty($res['data']['userid'])){
                    //用户体验金
                    $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("userid = {$res['data']['userid']} and type=2 and voucher_status=1 and is_manager=0");
                    if(!empty($voucher)){
                        $goods[0]['voucher_money'] = $voucher[0]['voucher_money'];
                    }else{
                        $goods[0]['voucher_money'] = '';
                    }
                }else{
                    $goods[0]['voucher_money'] = '';
                }
                $goods[0]['low_pay'] =(string)(int)$goods[0]['low_pay'];
                $goods[0]['high_pay'] =(string)(int)$goods[0]['high_pay'];
                $goods[0]['increasing_pay'] =(string)(int)$goods[0]['increasing_pay'];
                
                $res['data'] = $goods[0];
               
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取产品详情出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            
        }
        return $res;
    }

    /**
     * 验证参数
     *
     * @param array $params            
     * @throws Zeed_Exception
     */
    public static function validate ($params)
    {
    	/*校验参数*/
        if (! isset($params['order_id']) || strlen($params['order_id']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 order_id 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
