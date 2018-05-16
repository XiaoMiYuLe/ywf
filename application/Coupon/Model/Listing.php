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
class Coupon_Model_Listing extends Zeed_Db_Model
{
    
    /**
     * @var string The table name.
     */
    protected $_name = 'listing';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'cpns_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'coupon_';

    /**
     * 获取listing
     * @param unknown $where
     * @param string $order
     * @param string $perpage
     * @param string $offset
     * @param string $cols
     * @return NULL|Ambigous <NULL, multitype:, multitype:mixed Ambigous <string, boolean, mixed> >
     */
    public function fetchListingByWhere($where, $order = null, $perpage = null, $offset = null, $cols = '*')
    {
        if (! $where) return null;
    
        $select = $this->getAdapter()->select()->from($this->getTable(),$cols);
        $select->JoinLeft('cas_user','cas_user.userid = coupon_listing.userid',array('username','phone'));
    
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
     * 查询已领数量
     * @param unknown $coupon_id
     * @return number
     */
    public function countNumByCouponid($coupon_id)
    {
        if (! $coupon_id) return 0;
        $where = 'coupon_id = ' . $coupon_id;
        $select = $this->getAdapter()->select()->from($this->getTable(),array('count_num' => "COUNT(*)"));
        $row = $select->where($where)->query()->fetchAll();
        return $row ? $row[0]['count_num'] : 0;
    }

    /**
     * count by where
     * @param unknown $where
     * @return NULL|number
     */
    public function countListingByWhere($where)
    {
        if (! $where) return 0;
    
        $select = $this->getAdapter()->select()->from($this->getTable(),array('count_num' => "COUNT(*)"));
        $select->JoinLeft('cas_user','cas_user.userid = coupon_listing.userid',array('username','phone'));
    
        $row = $select->where($where)->query()->fetchAll();
        return $row ? $row[0]['count_num'] : 0;
    }
    
    /**
     * 获取用户中心listing
     * @param unknown $where
     * @param string $order
     * @param string $perpage
     * @param string $offset
     * @param string $cols
     * @return NULL|Ambigous <NULL, multitype:, multitype:mixed Ambigous <string, boolean, mixed> >
     */
    public function fetchUserListingByWhere($where, $order = null, $perpage = null, $offset = null, $cols = '*')
    {
        if (! $where) return null;
    
        $cols = array('cpns_id','coupon_listing.coupon_id');
        $select = $this->getAdapter()->select()->from($this->getTable(),$cols);
        $select->joinLeft('coupon_category', 'coupon_category.coupon_id = coupon_listing.coupon_id',array('coupon_name','face_value','coupon_type','is_exchange','coupon_point','rule','body','valid_stime','valid_etime'));
        $select->JoinLeft('coupon_relation','coupon_relation.coupon_id = coupon_listing.coupon_id',array('basic_price','relation_type','relation_content'));
        
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
     * count用户中心listing
     * @param unknown $where
     * @param string $order
     * @param string $perpage
     * @param string $offset
     * @param string $cols
     * @return NULL|Ambigous <NULL, multitype:, multitype:mixed Ambigous <string, boolean, mixed> >
     */
    public function CountUserListingByWhere($where)
    {
        if (! $where) return 0;
    
        $select = $this->getAdapter()->select()->from($this->getTable(),array('count_num' => "COUNT(*)"));
        $select->joinLeft('coupon_category', 'coupon_category.coupon_id = coupon_listing.coupon_id',array('coupon_name','face_value','coupon_type','is_exchange','coupon_point','rule','body','valid_stime','valid_etime'));
        $select->JoinLeft('coupon_relation','coupon_relation.coupon_id = coupon_listing.coupon_id',array('basic_price','relation_type','relation_content'));
    
        $row = $select->where($where)->query()->fetchAll();
        return $row ? $row[0]['count_num'] : 0;
    }
    
    /**
     * 获取用户领取优惠券的详情
     * @param unknown $cpns_id
     * @return boolean|Ambigous <NULL, multitype:, multitype:mixed Ambigous <string, boolean, mixed> >
     */
    public function fetchDetailForUser($cpns_id)
    {
        if (! $cpns_id) return false;
        $where = 'cpns_id = ' . $cpns_id;
        $cols  = array('cpns_id','coupon_id','cpns_status','userid','ctime');   
        
        $select = $this->getAdapter()->select()->from($this->getTable(),$cols);
        $select->JoinLeft('coupon_category','coupon_category.coupon_id = coupon_listing.coupon_id',array('coupon_name','face_value','coupon_type','is_exchange','coupon_point','rule','body','valid_stime','valid_etime'));
        $select->JoinLeft('coupon_relation','coupon_relation.coupon_id = coupon_listing.coupon_id',array('basic_price','relation_type','relation_content'));
    
        $row = $select->where($where)->query()->fetchAll();
        return $row ? $row[0] : null;
    }
    
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
    
}

// End ^ LF ^ encoding

