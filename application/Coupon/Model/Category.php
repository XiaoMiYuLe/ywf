<?php

/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright  Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 9, 2010
 * @version    SVN: $Id$
 */
class Coupon_Model_Category extends Zeed_Db_Model
{
    
    /**
     * @var string The table name.
     */
    protected $_name = 'category';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'coupon_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'coupon_';
    
    
    /**
     * 获取优惠券详情
     * @param unknown $where
     */
    public function fetchCouponDetail($coupon_id)
    {
        $select = $this->getAdapter()->select()->from($this->getTable());
        $select->JoinLeft('coupon_relation','coupon_relation.coupon_id = coupon_category.coupon_id',array('basic_price','relation_type','relation_content'));
        $select->JoinLeft('coupon_listing','coupon_listing.coupon_id = coupon_category.coupon_id');
        
        $row = $select->where('coupon_category.coupon_id = '.$coupon_id)->query()->fetchAll();
        if ($row){
            foreach ($row as &$v){
                switch ($v['coupon_type']){
                    case 1 : 
                        $v['coupon_type_txt'] = '通用券';
                    case 2 :
                        if ($v['relation_type'] == 1) {
                            $v['coupon_type_txt'] = '全场满减';   
                            $v['relation_content_txt'] = '全场通用';
                        }
                        if ($v['relation_type'] == 2){
                            $v['coupon_type_txt'] = '分类满减';
                            if ($category = Goods_Model_Category::instance()->fetchByPK($v['relation_content'])){
                                $v['relation_content_txt'] = $category[0]['category_name'];
                            }
                        }
                        if ($v['relation_type'] == 3) $v['coupon_type_txt'] = '商户满减';
                    default :
                        break;    
                }
            }              
        }
        return $row ? $row[0] : null;
    }

    
    /**
     * 获得所有优惠券
     */
    public function fetchAvailableCoupon($where = null, $order = null, $perpage = null, $offset = null, $cols = '*')
    {
        $select = $this->getAdapter()->select()->from($this->getTable(),$cols);
         $select->JoinLeft('coupon_relation','coupon_relation.coupon_id = coupon_category.coupon_id',array('basic_price','relation_type','relation_content'));
        
        if ($perpage !== null || $offset !== null) {
            $select->limit($perpage, $offset);
        }
        if ($order !== null) {
            $select->order($order);
        }
        
        $row = $select->where($where)->query()->fetchAll();
        return $row ? $row : null;
    }
    
    /**
     * 接口获取优惠券
     */
    public function fetchCoupons($where = null, $order = null, $perpage = null, $offset = null, $cols = '*')
    {
        $select = $this->getAdapter()->select()->from($this->getTable(),array('coupon_id','coupon_name','face_value','coupon_type','coupon_point','rule','body','valid_stime','valid_etime'));
        $select->JoinLeft('coupon_relation','coupon_relation.coupon_id = coupon_category.coupon_id',array('basic_price','relation_type','relation_content'));
    
        if ($perpage !== null || $offset !== null) {
            $select->limit($perpage, $offset);
        }
        if ($order !== null) {
            $select->order($order);
        }
        $row = $select->where($where)->query()->fetchAll();

        if ($row){
            foreach ($row as &$v){
                switch ($v['coupon_type']){
                    case 1 :
                        $v['coupon_type_txt'] = '通用券';
                    case 2 :
                        if ($v['relation_type'] == 1) {
                            $v['coupon_type_txt'] = '全场满减';
                            $v['relation_content_txt'] = '全场通用';
                        }
                        if ($v['relation_type'] == 2){
                            $v['coupon_type_txt'] = '分类满减';
                            if ($category = Goods_Model_Category::instance()->fetchByPK($v['relation_content'])){
                                $v['relation_content_txt'] = $category[0]['category_name'];
                            }
                        }
                        if ($v['relation_type'] == 3) $v['coupon_type_txt'] = '商户满减';
                    default :
                        break;
                }
                if ($v['exchanged_total'] >= $v['total']){
                    $v['available'] = -1;
                } else {
                    $v['available'] = 1;
                }
            }
        }
        return $row ? $row : null;
    }
    
    /**
     * 获取优惠券数量
     * @param unknown $where
     * @return number
     */
    public function countCouponList($where)
    {
        $select = $this->getAdapter()->select()->from($this->getTable(), array('count_num' => "COUNT(*)"));
        $select->JoinLeft('coupon_relation','coupon_relation.coupon_id = coupon_category.coupon_id');
    
        $row = $select->where($where)->query()->fetchAll();
        return $row ? $row[0]['count_num'] : 0;
    }

    /**
     * @return Coupon_Model_Category
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
    
}

// End ^ LF ^ encoding

