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

class Groupon_Model_Grab extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'grab';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'bulk_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'groupon_';
    
    
    /**
     * 
     * 获取今天的活动信息
     * 
     * @return array
     */
    public function fetchTodaysBulk(){
    	
    	$where = " now() between `start_time` AND `end_time`";
		$result = $this->fetchByWhere($where);
    	
    	return $result ? $result : null;
    }
    
    /**
     * 根据status 读取商品
     *
     * @param integer $status
     * @return array
     */
    public function fetchByStatus($status , $order, $count = null, $offset = null, $cols = '*')
    {
    	$where = " now() between `start_time` AND `end_time`";    	 
    	$order = 'end_time ASC';
    	 
    	$where .= ' AND status=' . $status;
    	$select = $this->getAdapter()->select()->from($this->getTable());
    	$select->join('goods_content','groupon_grab.sku = goods_content.sku',array(price_market,content_id,image_default));
    	 
    	if ($order !== null) {
    		$select->order($order);
    	}
    	 
    	if ($count !== null || $offset !== null) {
    		$select->limit($count, $offset);
    	}
    	
    	$result = $select->where($where)->query()->fetchAll();

    	return $result ? $result : null;
    }
    
    /**
     * 根据status 读取商品数量
     *
     * @param integer $status
     * @return int
     */
    public function countByStatus($status)
    {
    	$where = " now() between `start_time` AND `end_time`";
    
    	$where .= ' AND status=' . $status;
    	$select = $this->getAdapter()->select()->from($this->getTable(),array('count_num' => "COUNT(*)"));

    	$row = $select->where($where)->query()->fetchAll();
    	return $row ? $row['0']['count_num'] : 0;
    }
    
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
    
    /**
     * 根据条件筛选商品
     *
     * @params Integer $category_id
     * @params String $keyword
     * @params String $sort
     * @params String $order
     * @params Integer $count
     * @params Interger $offset
     *  
     * @return array
     */
    public function fetchGoodsByFilter($status, $category_id = null, $keyword = null, $sort = null, $order = null, $count = null, $offset = null)
    {
    	$where = " now() between `start_time` AND `end_time`";  
    	$where .= ' AND status=' . $status;
    	
    	$select = $this->getAdapter()->select()->from($this->getTable());
    	$select->join('goods_content','groupon_grab.sku = goods_content.sku',array(price_market,content_id,image_default));
    	
    	if ($category_id) {
    		$where .= ' AND category_id in(' . $category_id .')';

    	}

    	if ($keyword) {
    		$where .= ' AND goods_name like "%' . $keyword . '%"';
    	}
    	
    	if ($sort && $order) {
    		$orderBy = $sort . ' ' . $order;
    		$select->order($orderBy);
    	}
    	
    	if ($count !== null || $offset !== null) {
    		$select->limit($count, $offset);
    	}
    
    	$row = $select->where($where)->query()->fetchAll();
    	return $row ? $row : null;
    }
}
// End ^ Native EOL ^ UTF-8