<?php

/**
 * iNewS Project
 *
 * LICENSE
 *
 * http://www.inews.com.cn/license/inews
 *
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Apr 16, 2010
 * @version    SVN: $Id$
 */
class Bts_Model_Order extends Zeed_Db_Model_Detach_Date
{

    /**
     * 订单状态
     * 
     * @const PAY_STATUS_ZERO 未支付
     * @const PAY_STATUS_ONE 支付完毕
     * 
     * @const STATUS_ZERO 待付款
     * @const STATUS_ONE 已完成
     * @const STATUS_TWO 待发货
     * @const STATUS_THREE 确认收货
     * @const STATUS_FOUR 待评价
     * @const STATUS_FIVE 退款订单
     * 
     * @const IS_REFUND_ZERO 未发起
     * @const IS_REFUND_ONE 已成功退货
     * @const IS_REFUND_TWO 等待审核
     * @const IS_REFUND_THREE 已同意
     * @const IS_REFUND_FOUR 已拒绝
     * 
     */
    const PAY_STATUS_ZERO = 0;
    const PAY_STATUS_ONE = 1;
    
    const STATUS_ZERO = 0;
    const STATUS_ONE = 1;
    const STATUS_TWO = 2;
    const STATUS_THREE = 3;
    const STATUS_FOUR = 4;
    const STATUS_FIVE = 5;
    
    const IS_REFUND_ZERO = 0;
    const IS_REFUND_ONE = 1;
    const IS_REFUND_TWO = 2;
    const IS_REFUND_THREE = 3;
    const IS_REFUND_FOUR = 4;    

    public $pay_status;

    public $ship_status;

    public $return_status;

    public $status;
    
    public $pay_type;

    /**
     * 表名
     *
     * @var string
     */
    protected $_name = 'order';

    /**
     * 主键
     *
     * @var String
     */
    protected $_primary = 'order_id';

    /**
     * 前缀
     *
     * @var string Table prefix.
     */
    protected $_prefix = 'bts_';

    protected $_detachField = 'ctime';

    public function __construct ()
    {
        parent::__construct();
        $this->pay_status = array(
                0 => '未支付',
                1 => '支付完毕'
        );
        $this->ship_status = array(
                0 => '未发货',
                1 => '已发货',
                2 => '部分发货',
                3 => '部分退货',
                4 => '全部退货'
        );
        $this->return_status = array(
                0 => '未申请退单',
                1 => '已成功退货',
                2 => '等待审核',
                3 => '已审核/待退货',
                4 => '用户已退货',
                5 => '等待用户补交保证金',
                6 => '用户已交保证金'
        );
        
        $this->status = array(
                0 => '待处理 ',
                1 => '已签收',
                2 => '待付款',
                3 => '付款成功',
                4 => '待发货',
                5 => '已发货',
        );
        
        $this->pay_type = array(
            0 => '在线支付 ',
            1 => '货到付款'
        );
    }
    
    /**
     * 执行购物车生成订单
     *
     * @param varchar $cart_ids
     * @param int $userid
     * @param int $consignee
     * @throws Zeed_Exception
     * @return string Ambigous number>
     */
    public static function transactionCart ($cart_ids, $userid, $consignee)
    {
        try {
    
            /* 检查用户是否存在 */
            if (! $userExists = Cas_Model_User::instance()->fetchByPK($userid)) {
                throw new Zeed_Exception('该用户不存在');
            }
    
            Bts_Model_Order::instance()->beginTransaction();
    
            // 优惠金额先定位0
            $res['data']['coupon_amount'] = 0;
            $res['data']['userid'] = $userid;
    
            /* 读取提交的的购物车 */
            $where = " AND bts_cart.cart_id in ({$cart_ids})";
            $carts = Bts_Model_Cart::instance()->getOrderGoodsByUserId($userid, $where);
    
            if (empty($carts)) {
                throw new Zeed_Exception('购物车数据不存在');
            }
    
            /* 生成总订单 */
            $res['data']['order_number'] = Bts_Model_Order::instance()->getSimpleOrderNumberToken();
            
            /**
             * 订单ID
             *
             * 订单编号
             * 用户ID
             * 订单状态
             * 总计金额
             * 实际支付金额
             * 创建时间
            */
    
            // 取得用户地区信息
            $consignee = Cas_Model_User_Consignee::instance()->fetchByPK($consignee);
    
            // 收货详情数据组织
            if (! empty($consignee)) {
    
                /**
                 * 收货人信息
                 *
                 * 收货人姓名
                 * 收货人地区
                 * 收货人地区ID
                 * 收货人地址
                 * 收货人右边
                 * 收货人EMAIL
                 * 收货人手机号
                 */
                $res['data']['consignee_name'] = $consignee[0]['name'];
                $res['data']['consignee_region_name'] = $consignee[0]['region_name'];
                $res['data']['consignee_region_id'] = $consignee[0]['region_id'];
                $res['data']['consignee_address'] = $consignee[0]['address'];
                $res['data']['consignee_zipcode'] = $consignee[0]['zipcode'];
                $res['data']['consignee_email'] = $consignee[0]['email'];
                $res['data']['consignee_mobile'] = $consignee[0]['mobile'];
            } else {
                throw new Zeed_Exception('地址信息不存在，请检查 ');
            }
    
            
            $res['data']['count'] = $carts['sum_quantity'];
            $res['data']['total_amount'] = $carts['sum_amount'] - $res['data']['coupon_amount'] > 0 ? $carts['sum_amount'] - $res['data']['coupon_amount'] : 0;
            // 用户名
            $res['data']['order_type'] = 0;
            $res['data']['username'] = $userExists[0]['username'];
            $res['data']['status'] = $res['data']['total_amount'] <= 0 ? self::STATUS_THREE : self::STATUS_TWO; // 设置订单状态为待付款状态
            $res['data']['ctime'] = date(DATETIME_FORMAT); 
            /* 生成订单 */
            $order_id = Bts_Model_Order::instance()->addForEntity($res['data']);
            if (! $order_id) {
                throw new Zeed_Exception('缺少订单ID');
            }
            
            
            /** 日志  */
            $logSet = array(
                'admin_userid' => $userid,
                'type'         => 1,
                'order_id'     => $order_id,
                'order_number' => $res['data']['order_number'],
                'content'      => 'userid 为'.$userid.'的用户提交了订单号为'.$res['data']['order_number'].'订单',
                'remark'       => '',
                'ip'           => Zeed_Util::clientIP(),
                'ctime'        => date(DATETIME_FORMAT)
            );
            Bts_Model_Order_Log::instance()->insert($logSet);
            /* 拆解信息 并分为几个订单进行生成 重新计算订单价格 */

            /* 入库 - 消费订单物品详细列表 */
            foreach ($carts['data'] as $vv) {
                // 判断是否有库存
                if ($vv['stock'] - $vv['quantity'] < 0) {
                    throw new Zeed_Exception('购物车商品库存不足，请删除后重试');
                }

                /**
                 * 订单ID
                 * 商品ID
                 * 商品名称
                 * 商品图片
                 * 商品SIZE
                 * 商品颜色
                 * 商品重量 单位为克
                 * 商品长度
                 * 商品WIDE
                 * 商品高度
                 * 购买价格
                 * 购买数量
                 * 商品编号
                 */
                $set_item = array(
                    'order_id' => $order_id,
                    'content_id' => $vv['content_id'],
                    'goods_name' => $vv['name'],
                    'goods_image' => $vv['image_default'],
                    'goods_weight' => $vv['weight'],
                    'goods_length' => $vv['length'],
                    'goods_height' => $vv['height'],
                    'goods_wide' => $vv['wide'],
                    'description' => $vv['description'],
                    'buy_price' => $vv['price'],
                    'buy_num' => $vv['quantity'],
                    'sku' => $vv['sku'],
                    'ctime'=>date(DATETIME_FORMAT)
                );

                // 添加订单详情库
                Bts_Model_Order_Items::instance()->addForEntity($set_item);

                // 更改商品库存 不能为负数
                if (! $vv['stock'] - $vv['quantity'] < 0) {
                    $date = array(
                        'stock' => $vv['stock'] - $vv['quantity']
                    );
                } else {
                    $date = array(
                        'stock' => 0
                    );
                }

                Goods_Model_Content::instance()->updateForEntity($date, $vv['content_id']);
            }
    
            // 删除购物车中对应商品
            if (strpos($cart_ids, ',')) {
                $shopcatrs = explode(',', $cart_ids);
                foreach ($shopcatrs as $value) {
                    Bts_Model_Cart::instance()->deleteById($value);
                }
            } else {
                Bts_Model_Cart::instance()->deleteById($cart_ids);
            }
        } catch (Zeed_Exception $e) {
            Bts_Model_Order::instance()->rollBack();
            $res['status'] = 1;
            $res['error'] = '生成订单失败。错误信息：' . $e->getMessage();
    
            return $res;
        }
    
        Bts_Model_Order::instance()->commit();
        $res['status'] = 0;
        $res['error'] = '创建订单成功';
        return $res;
    }
    
    /**
     * 立即购买生成订单
     *
     * @param varchar $specification_id
     * @param int $userid
     * @param int $consignee
     * @throws Zeed_Exception
     * @return string Ambigous number>
     */
    public static function transaction ($content_id, $userid ,$buy_num, $consignee,$order_type = 0)
    {
        try {
        
            Bts_Model_Order::instance()->beginTransaction();
        
            // 优惠金额先定位0
            $res['data']['coupon_amount'] = 0;
            $res['data']['buy_num'] = $buy_num ? $buy_num : 1;
            $res['data']['userid'] = $userid;
            $res['data']['content_id'] = $content_id;
            $res['data']['consignee'] = $consignee;
            
            /* 检查用户是否存在 */
            if (! $userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid'])) {
                throw new Zeed_Exception('该用户不存在');
            }
        
            $content = Goods_Model_Content::instance()->fetchByPK($res['data']['content_id']);
            if (empty($content)) {
                throw new Zeed_Exception('生成订单失败：您所选择的商品不存在');
            }
            $content = $content[0];
            
            $property = array();
            /* 获取商品属性规格 */
            if (! empty($content['property_related'])) {
            
                $property_relateds = explode(',', $content['property_related']);
            
                if (is_array($property_relateds)) {
            
                    $property = array();
            
                    foreach ($property_relateds as $v) {
            
                        $info = explode(':', $v);
                        $name = Trend_Model_Property::instance()->fetchByPK($info[0],array('label_name'));
                        $value = Trend_Model_Property_Value::instance()->fetchByPK($info[1],array('property_value'));
            
                        $name && $value ? $property[] = array( $name[0]['label_name'] => $value[0]['property_value']) : '';
            
                    }
                }
            }
        
            $description = array(
                'category'=>$content['category'],
                'brand_id'=>$content['brand_id'],
                'userid'=>$content['userid'],
                'name'=>$content['name'],
                'is_shelf'=>$content['is_shelf'],
                'property_related'=>$content['property_related'],
                'sku'=>$content['sku'],
                'stock'=>$content['stock'],
                'weight'=>$content['weight'],
                'price'=>$content['price'],
                'price_market'=>$content['price_market'],
                'price_cost'=>$content['price_cost'],
                'propertys'=>$property
            );
            /* 判断所选商品是否库存充足 */
            if ($content['stock'] - $res['data']['buy_num'] < 0) {
                throw new Zeed_Exception('生成订单失败：您所选择的商品库存不足');
            }
        
            /* 生成总订单 */
            $res['data']['order_number'] = Bts_Model_Order::instance()->getSimpleOrderNumberToken();
            
            // 取得用户地区信息
            $consignee = Cas_Model_User_Consignee::instance()->fetchByPK($res['data']['consignee']);
        
            // 收货详情数据组织
            if (! empty($consignee)) {
        
                /**
                 * 收货人信息
                 *
                 * 收货人姓名
                 * 收货人地区
                 * 收货人地区ID
                 * 收货人地址
                 * 收货人右边
                 * 收货人EMAIL
                 * 收货人手机号
                 */
                $res['data']['consignee_name'] = $consignee[0]['name'];
                $res['data']['consignee_region_name'] = $consignee[0]['region_name'];
                $res['data']['consignee_region_id'] = $consignee[0]['region_id'];
                $res['data']['consignee_address'] = $consignee[0]['address'];
                $res['data']['consignee_zipcode'] = $consignee[0]['zipcode'];
                $res['data']['consignee_email'] = $consignee[0]['email'];
                $res['data']['consignee_mobile'] = $consignee[0]['mobile'];
            } else {
                throw new Zeed_Exception('地址信息不存在，请检查 ');
            }
        
            // 用户名
            $res['data']['order_type'] = 0;
            $res['data']['username'] = $userExists[0]['username'];
            $res['data']['count'] = $res['data']['buy_num'];
            $res['data']['total_amount'] = ($content['price'] * $res['data']['buy_num'] - $res['data']['coupon_amount']) > 0 ? $content['price'] * $res['data']['buy_num'] - $res['data']['coupon_amount'] : 0;
            $res['data']['status'] = $res['data']['total_amount'] <= 0 ? self::STATUS_THREE : self::STATUS_TWO; // 设置订单状态为待付款状态
            $res['data']['ctime'] = date(DATETIME_FORMAT);
            $res['data']['order_type'] = $order_type;
            /* 生成订单 */
            $order_id = Bts_Model_Order::instance()->addForEntity($res['data']);
            
            if (! $order_id) {
                throw new Zeed_Exception('缺少订单ID');
            }
            
            /** 日志  */
            $logSet = array(
                'admin_userid' => $userid,
                'type'         => 1,
                'order_id'     => $order_id,
                'order_number' => $res['data']['order_number'],
                'content'      => 'userid 为'.$userid.'的用户提交了订单号为'.$res['data']['order_number'].'订单',
                'remark'       => '',
                'ip'           => Zeed_Util::clientIP(),
                'ctime'        => date(DATETIME_FORMAT)
            );
            Bts_Model_Order_Log::instance()->insert($logSet);
        
            /* 入库 - 消费订单物品详细列表 */
            /**
             * 订单ID
             *
             * 商品ID
             * 商品名称
             * 商品图片
             * 商品SIZE
             * 商品颜色
             * 商品重量 单位为克
             * 商品长度
             * 商品WIDE
             * 商品高度
             * 购买价格
             * 购买数量
             * 商品编号
            */
            $set_item = array(
                    'order_id' => $order_id,
                    'content_id' => $content['content_id'],
                    'goods_name' => $content['name'],
                    'goods_image' => $content['image_default'] ,
                    'goods_weight' => $content['weight'],
                    'goods_length' => $content['length'],
                    'goods_height' => $content['height'],
                    'goods_wide' => $content['wide'],
                    'description' => json_encode($description),
                    'buy_price' => $content['price'],
                    'buy_num' => $res['data']['buy_num'],
                    'sku' => $content['sku'],
                    'ctime'=>date(DATETIME_FORMAT)
            );
        
            // 添加订单详情库
            Bts_Model_Order_Items::instance()->addForEntity($set_item);
        
            // 更改商品库存 不能为负数
            if (! $content['stock'] - $res['data']['buy_num'] < 0) {
                $date = array(
                        'stock' => $content['stock'] - $res['data']['buy_num']
                );
                Goods_Model_Content::instance()->updateForEntity($date, $content['content_id']);
            }
        
        } catch (Zeed_Exception $e) {
            Bts_Model_Order::instance()->rollBack();
            $res['status'] = 1;
            $res['error'] = '生成订单失败。错误信息：' . $e->getMessage();
        
            return $res;
        }
        
        Bts_Model_Order::instance()->commit();
        $res['status'] = 0;
        $res['error'] = '创建订单成功';
        return $res;
    }
    
    /**
     * 立即购买生成订单
     *
     * @param varchar $specification_id
     * @param int $userid
     * @param int $consignee
     * @throws Zeed_Exception
     * @return string Ambigous number>
     */
    public static function grouponaction ($sku, $userid ,$buy_num, $consignee,$order_type = 0)
    {
        try {
            Bts_Model_Order::instance()->beginTransaction();
            
            $notime = date("Y-m-d H:i:s", time());
            $grouponwhere = "sku = '{$sku}' AND start_time < '{$notime}' AND end_time > '{$notime}'";
            
            if( $order_type == 1 ) {
                $grouponwhere .= " AND inventory > {$buy_num}";
                $group = Groupon_Model_Bulk::instance()->fetchByWhere($grouponwhere, 'ctime ASC', 1, 0);
                if( empty($group) ) {
                    throw new Zeed_Exception('无此团购或库存不足');
                }
            } else if($order_type == 2) {
                $grouponwhere .= " AND inventory > {$buy_num}";
                $group = Groupon_Model_Grab::instance()->fetchByWhere($grouponwhere, 'ctime ASC', 1, 0);
                if( empty($group) ) {
                    throw new Zeed_Exception('无此抢购或库存不足');
                }
            } else {
                $grouponwhere .= " AND inventory > {$buy_num}";
                $group = Groupon_Model_Bulk::instance()->fetchByWhere($grouponwhere, 'ctime ASC', 1, 0);
                if( empty($group) ) {
                    throw new Zeed_Exception('无此抢购或库存不足');
                }
            }
            
            // 优惠金额先定位0
            $res['data']['coupon_amount'] = 0;
            $res['data']['buy_num'] = $buy_num ? $buy_num : 1;
            $res['data']['userid'] = $userid;
            $res['data']['consignee'] = $consignee;
    
            
            /* 检查用户是否存在 */
            if (! $userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid'])) {
                throw new Zeed_Exception('该用户不存在');
            }
    
            if( $order_type == 2 ) {
                $bulk_log = Groupon_Model_Bulk_Log::instance()->fetchByWhere("userid = {$res['data']['userid']} AND bulk_id = {$group[0]['bulk_id']}", 'ctime DESC', 1, 0);
                if(! empty($bulk_log)) {
                    throw new Zeed_Exception('您已抢过此抢购');
                }
            }
            $content = Goods_Model_Content::instance()->fetchByWhere("sku = '{$sku}' ", 'ctime ASC', 1, 0);
            if (empty($content)) {
                throw new Zeed_Exception('生成订单失败：您所选择的商品不存在');
            }
            $res['data']['content_id'] = $content[0]['content_id'];
            
            $content = $content[0];
    
            $property = array();
            /* 获取商品属性规格  */
            if (!empty($content['property_related'])) {
    
                $property_relateds = explode(',', $content['property_related']);
    
                if (is_array($property_relateds)) {
    
                    $property = array();
    
                    foreach ($property_relateds as $v) {
    
                        $info = explode(':', $v);
                        $name = Trend_Model_Property::instance()->fetchByPK($info[0],array('label_name'));
                        $value = Trend_Model_Property_Value::instance()->fetchByPK($info[1],array('property_value'));
    
                        $name && $value ? $property[] = array( $name[0]['label_name'] => $value[0]['property_value']) : '';
    
                    }
                }
            }
    
            $description = array(
                'category'=>$content['category'],
                'brand_id'=>$content['brand_id'],
                'userid'=>$content['userid'],
                'name'=>$content['name'],
                'is_shelf'=>$content['is_shelf'],
                'property_related'=>$content['property_related'],
                'sku'=>$content['sku'],
                'stock'=>$content['stock'],
                'weight'=>$content['weight'],
                'price'=>$content['price'],
                'price_market'=>$content['price_market'],
                'price_cost'=>$content['price_cost'],
                'propertys'=>$property
            );
            /* 判断所选商品是否库存充足 */
            if ($content['stock'] - $res['data']['buy_num'] < 0) {
                throw new Zeed_Exception('生成订单失败：您所选择的商品库存不足');
            }
    
            /* 生成总订单 */
            $res['data']['order_number'] = Bts_Model_Order::instance()->getSimpleOrderNumberToken();
    
            // 取得用户地区信息
            $consignee = Cas_Model_User_Consignee::instance()->fetchByPK($res['data']['consignee']);
    
            // 收货详情数据组织
            if (! empty($consignee)) {
    
                /**
                 * 收货人信息
                 *
                 * 收货人姓名
                 * 收货人地区
                 * 收货人地区ID
                 * 收货人地址
                 * 收货人右边
                 * 收货人EMAIL
                 * 收货人手机号
                 */
                $res['data']['consignee_name'] = $consignee[0]['name'];
                $res['data']['consignee_region_name'] = $consignee[0]['region_name'];
                $res['data']['consignee_region_id'] = $consignee[0]['region_id'];
                $res['data']['consignee_address'] = $consignee[0]['address'];
                $res['data']['consignee_zipcode'] = $consignee[0]['zipcode'];
                $res['data']['consignee_email'] = $consignee[0]['email'];
                $res['data']['consignee_mobile'] = $consignee[0]['mobile'];
            } else {
                throw new Zeed_Exception('地址信息不存在，请检查 ');
            }
    
            // 用户名
            $res['data']['order_type'] = 0;
            $res['data']['username'] = $userExists[0]['username'];
            $res['data']['count'] = $res['data']['buy_num'];
            $res['data']['total_amount'] =  $group[0]['price'] * $res['data']['buy_num'];
            $res['data']['status'] = $res['data']['total_amount'] <= 0 ? self::STATUS_THREE : self::STATUS_TWO; // 设置订单状态为待付款状态
            $res['data']['ctime'] = date(DATETIME_FORMAT);
            $res['data']['order_type'] = $order_type;
            /* 生成订单 */
            $order_id = Bts_Model_Order::instance()->addForEntity($res['data']);
    
            if (! $order_id) {
                throw new Zeed_Exception('缺少订单ID');
            }
    
            /* 入库 - 消费订单物品详细列表 */
            /**
             * 订单ID
             *
             * 商品ID
             * 商品名称
             * 商品图片
             * 商品SIZE
             * 商品颜色
             * 商品重量 单位为克
             * 商品长度
             * 商品WIDE
             * 商品高度
             * 购买价格
             * 购买数量
             * 商品编号
            */
            $set_item = array(
                'order_id' => $order_id,
                'content_id' => $content['content_id'],
                'goods_name' => $content['name'],
                'goods_image' => $content['image_default'] ,
                'goods_weight' => $content['weight'],
                'goods_length' => $content['length'],
                'goods_height' => $content['height'],
                'goods_wide' => $content['wide'],
                'description' => json_encode($description),
                'buy_price' => $content['price'],
                'buy_num' => $res['data']['buy_num'],
                'sku' => $content['sku'],
                'ctime'=>date(DATETIME_FORMAT)
            );
    
            // 添加订单详情库
            Bts_Model_Order_Items::instance()->addForEntity($set_item);
    
            // 更改商品库存 不能为负数
            if (! $content['stock'] - $res['data']['buy_num'] < 0) {
                $date = array(
                    'stock' => $content['stock'] - $res['data']['buy_num']
                );
                Goods_Model_Content::instance()->updateForEntity($date, $content['content_id']);
            }
            
            // 更改团购或抢购的库存
            $group[0]['sku'];
            $group[0]['bulk_id'];
            $arr['inventory'] = $group[0]['inventory'] - $res['data']['buy_num'];
            Groupon_Model_Bulk::instance()->updateForEntity($arr, $group[0]['bulk_id']);
            
            $log = array(
                'bulk_id' => $group[0]['bulk_id'],
                'sku' => $group[0]['sku'],
                'goods_name' => $group[0]['goods_name'],
                'userid' => $res['data']['userid'],
                'number' => $res['data']['buy_num'],
                'price' => $group[0]['price'],
                'integral' => $res['data']['integral'],
                'ctime' => date(DATETIME_FORMAT)
            );
            Groupon_Model_Bulk_Log::instance()->addForEntity($log);
            
        } catch (Zeed_Exception $e) {
            Bts_Model_Order::instance()->rollBack();
            $res['status'] = 1;
            $res['error'] = '生成订单失败。错误信息：' . $e->getMessage();
    
            return $res;
        }
    
        Bts_Model_Order::instance()->commit();
        $res['status'] = 0;
        $res['error'] = '创建订单成功';
        return $res;
    }
    
    
    /**
     * 按要求取得订单列表信息 根据需求不断完善
     * 
     * @param unknown $where            
     * @return unknown
     */
    public function getOrderByWhere ($where)
    {
        $select = $this->getAdapter()
            ->select()
            ->from($this->getTable());
        if ($where) {
            $select->where($where);
        }
        $row = $select->query()->fetchAll();
        if (! empty($row)) {
            foreach ($row as $k => $v) {
                
                unset($row[$k]['partner_id']);
                unset($row[$k]['out_order_number']);
                unset($row[$k]['remark']);
                unset($row[$k]['is_delivery']);
                unset($row[$k]['is_invoice']);
                unset($row[$k]['invoice_tax']);
                unset($row[$k]['invoice_belong']);
                unset($row[$k]['coupon_amount']);
                unset($row[$k]['coupon_id']);
                unset($row[$k]['consume_credit']);
                unset($row[$k]['is_package']);
                
                $where = "order_id ={$v['order_id']}";
                $info = Bts_Model_Order_Items::instance()->fetchByWhere($where);
                $row[$k]['items'] = $info;
            }
        }
        
        return $row;
    }

    /**
     * 根据订单号查询订单
     *
     * @param unknown $order_number
     * @return unknown
     */
    public function getOderByNumber ($order_number)
    {
        $select = $this->getAdapter()
            ->select()
            ->from($this->getTable());
        
        $where = "order_number ='{$order_number}'";
        $select->where($where);
        
        $row = $select->query()->fetch();
        return $row;
    }
    
    
    /**
     * 根据订单ID查询订单
     *
     * @param unknown $order_number
     * @return unknown
     */
    public function fetchOrderById($id)
    {
    	$where = '1=1';
    	if ($id) {
    		$where .= ' AND order_id=' . $id;
    	}
    
    	$row = $this->fetchByWhere($where);
    	return $row ? $row : null;
    }

    /**
     * 修改订单状态
     *
     * @param $flag 订单状态            
     * @param $expectStatus 预期修改状态            
     * @return array false false
     */
    public function changeStatus ($order_number, $flag, $expectStatus = self::PAY_STATUS_ZERO)
    {
        switch ($flag) {
            case self::PAY_STATUS_ZERO:
            case self::PAY_STATUS_ONE:
            case self::PAY_STATUS_TWO:
            case self::PAY_STATUS_THREE:
            case self::PAY_STATUS_FOUR:
            case self::PAY_STATUS_FIVE:
                break;
            default:
                return false;
        }
        
        $db = $this->getAdapter();
        $timenow = time();
        
        try {
            $this->beginTransaction();
            
            $db->quoteIdentifier('order_id');
            $order_number_field = $db->quoteIdentifier('order_number');
            $pay_status_field = $db->quoteIdentifier('pay_status');
            
            /**
             * 获取订单信息
             */
            $order_number_field = $db->quoteIdentifier('order_number');
            $select = $db->select()
                ->from($this->getTable())
                ->where("{$order_number_field} LIKE ?", $order_number)
                ->forUpdate(true);
            
            if (null !== $expectStatus) {
                $pay_status_field = $db->quoteIdentifier('pay_status');
                $select->where("{$pay_status_field} = ?", $expectStatus);
            }
            
            $orderInfo = $this->_db->fetchRow($select);
            
            if (is_array($orderInfo) && count($orderInfo)) {
                $db->quoteIdentifier('mtime');
                
                /**
                 * 修改当前支付记录状态
                 */
                $db->update($this->getTable(), array(
                        'pay_status' => $flag,
                        'mtime' => date(DATETIME_FORMAT, $timenow)
                ), array(
                        "{$order_number_field} LIKE ?" => $orderInfo['order_number']
                ));
                
                $db->commit();
                
                /**
                 * 添加修改状态日志
                 */
                $chargelogSet = array(
                        'type' => 1,
                        'order_id' => $orderInfo['order_id'],
                        'order_number' => $orderInfo['order_number'],
                        'content' => '', // @todo
                                         // 这里需要魏永丽整理，往里面记录什么？以什么方式记入？json?序列化？
                        'remark' => '',
                        'ip' => Zeed_Util::clientIP(),
                        'ctime' => date(DATETIME_FORMAT, $timenow)
                );
                
                Bts_Model_Order_Log::instance()->changeAdapter('master')->addForEntity($chargelogSet);
                return $orderInfo;
            }
            
            throw new Zeed_Exception("can not found charge order '{$order_number}', when change status");
        } catch (Exception $e) {
            $this->rollBack();
        }
        
        return false;
    }

    /**
     * 生成订单号唯一标志用于生成订单号
     *
     * @param integer $tokenLen
     *            订单号唯一标志长度
     * @return string null
     * @see BTS_Order::getSimpleOrderNumber()
     */
    public function getSimpleOrderNumberToken ($tokenLen = 8)
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $tokenLen);
    }
    
    /**
     * 根据用户id获取订单
     *
     * @param integer $userid
     * @return string null
     * @see BTS_Order::getSimpleOrderNumber()
     */
    public function fetchOrderByUserId($userid , $order, $count = null, $offset = null, $cols = null)
    {
        $where = 'is_del = 0 AND is_del_user = 0';
        if (!$userid) {
            return null;
        }
        
        $order = 'status ASC';
        
        $where .= ' AND userid=' . $userid;
        $select = $this->getAdapter()->select()->from($this->getTable());
    
        if ($order !== null) {
        	$select->order($order);
        }
        
        if ($count !== null || $offset !== null) {
        	$select->limit($count, $offset);
        }
        
        $row = $select->where($where)->query()->fetchAll();
        
        return $row ? $row : null;
    }
    
    /**
     * 根据用户ID获取订单数量
     * @param integer $userid
     * @return 
     */
    
    public function fetchCountOrderByUserId($userid,$orderType = null)
    {
    	$where = 'is_del_user = 0 AND is_del = 0';
    	
    	if($orderType){
    		$where .= ' AND order_type = '.$orderType;	
    	}else{
    		$where .= ' AND order_type = 0';
    	}
    	
    	if (!$userid) {
    		return null;
    	}
    	
    	$where .= ' AND userid=' . $userid;
    	
    	$select = $this->getAdapter()->select()->from($this->getTable(), array('count_num' => "COUNT(*)"));
    
    	$row = $select->where($where)->query()->fetch();
    
    	return $row ? $row["count_num"] : 0;
    }
    
    
    /**
     * 取消订单
     * @param $order_id 订单id
     */
    public function cancelOrder($order_id)
    {
    	if(!$order_id){
    		return false;
    	}
		return $this->update(array('status'=>'0','is_cancel'=>'1'),'order_id='.$order_id);
    }
    
    /**
     * 删除订单
     * @param $order_id 订单id
     */
    public function delOrder($order_id)
    {
    	if(!$order_id){
    		return false;
    	}
    	return $this->update(array('is_del_user'=>'1'),'order_id='.$order_id);
    }
    
    /**
     * 获取最近订单的用户数量
     * @param int $days(1,7,30)
     * $retrun int
     */
    public function countRecentlyOrders($days)
    {
    	$where = '';
    	 
    	$day = "date(ctime) = curdate()";
    	$week = "DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(ctime)";
    	$month = "DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(ctime)";
    	 
    	$select = $this->getAdapter()->select()->from($this->getTable(), array('count_num' => "COUNT(*)"));
    	 
    	if($days == 1){
    		$where .= $day;
    		$row = $select->where($where)->query()->fetch();
    	}
    	if($days == 7){
    		$where .= $week;
    		$row = $select->where($where)->query()->fetch();
    	}
    	if($days == 30){
    		$where .= $month;
    		$row = $select->where($where)->query()->fetch();
    	}
    
    	return $row ? $row["count_num"] : 0;
    }
   
    public function fetchOrders($now){
        $sql = "select a.userid, count(*) as count from bts_order as a where cash_time='{$now}' and order_status = 4 group by a.userid";
        $row = $this->getAdapter()->query($sql)->fetchAll();
        return $row ? $row :null;
    }

    /**
     *
     * @return Bts_Model_Order
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding