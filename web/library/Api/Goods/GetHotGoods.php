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
 * 获取推荐商品
 */
class Api_Goods_GetHotGoods
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
            	/*需要获取的产品参数*/
            	$filed = array(
            		'goods_id', //产品id
            		'goods_name', //产品名称
            		'all_fee', //'总额度',
            		'spare_fee',  //'剩余额度',
//             		'is_now', //起息方式 1：购买成功当日 2：自定义
//             		'start_time',//'起息日期',
//             		'end_time',//'结息日期',
//             		'deal_date',//'兑付日期',
//             		'deal_way',  //'兑付方式：',
            		'yield', //'收益率%',
            		'goods_pattern', // '产品模式【1：新手 2:直购 3：预约】',
            		'goods_type',  //'产品类型：1债权 2保险 3资管 4资金 5信托',
            		'goods_status',//'产品状态：1 销售中 2 已售罄 3 已下架',
            		'financial_period', //'理财期限(天)',
            		'low_pay',  //'最低投资额(元)',
//             		'high_pay',  //'最高投资额(元)',
//             		'increasing_pay', //'递增金额',
//             		'goods_broratio',//产品佣金比例',
//             		'goods_detail',  //'详情',
            		'is_del', //'是否删除 0否  1是',
            		'ctime', //'创建时间',
            		'mtime',//'修改时间',
            		'buy_num',  //'购买人数',
//             		'principal_way',  //'兑付方式',
//             		'redeem_status',  //'提前赎回：1允许 2不允许',
//             		'principal_status',//兑付本金：1:是2：否',
//             		'deal_status',  //'是否结息：1是2否',
//             		'comment', //'备注',
//             		'safety', //'安全保障',
            		'is_hot' //'是否精选推荐'
            	);
            	
            	/*查询产品信息*/
                if (!$goods = Goods_Model_List::instance()->fetchByWhere("is_hot = 1 and is_del = 0 and (goods_status = 1 or goods_status = 2)","ctime desc",null,null,$filed)){
                	throw new Zeed_Exception('未找到推荐产品');
                } else {
                	foreach ($goods as $k => &$v) {
                		if ($v['yield']) {
                			$v['yield'] = round($v['yield'],0);
                		} else {
                			$v['yield'] = 0;
                		}
                		
                		if ($v['all_fee'] > 0) {
                			$v['schedule'] = (($v['all_fee']-$v['spare_fee'])/$v['all_fee'])*100;
                		}
                		if ($v['schedule'] < 1 && $v['schedule'] > 0) {
                			$v['schedule'] = 1;
                		} else {
                			$v['schedule'] = floor($v['schedule']);
                		}
                	}
                }
                $res['data'] = $goods[0];   
               
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取推荐产品出错。错误信息：' . $e->getMessage();
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
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
