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

class Goods_Model_Property extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'property';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'goods_property_id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'goods_';
    
    
    /**
     * @return Goods_Model_Property
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
    
    /**
     * 更具商品id获取商品属性
     *
     * @param int $content_id
     * @return null
     */
    public function getPropertyByContentId($content_id = 0)
    {
        $where = '1=1';
        if($content_id){
            $where .= ' AND content_id='.$content_id;
        }
        $select = $this->getAdapter()->select()->from($this->getTable().' AS gp');
        $select->joinLeft('trend_property AS tp','gp.property_id=tp.property_id',array('label_name'));
        $select->joinLeft('trend_property_value AS tpv','gp.property_value_id=tpv.property_value_id',array('property_value','property_image'));
        $select->where($where);
        $row = $select->query()->fetchAll();
        return $row ? $row :null;
    }
    
    /**
     * 根据分类获取相关联的属性信息
     */
    public function getPropertyByCategoryid($category_id, $is_spec = 0)
    {
        if (! Goods_Model_Category::instance()->fetchByPK($category_id)) {
             return ;
        }
    
        /* 获取关联分类的属性或属性组 */
        $where = "category_id = {$category_id}";
        $order = "sort_order ASC";
        if (! $property_category = Goods_Model_Property_Category::instance()->fetchByWhere($where, $order)) {
            return ;
        }
        
        /* 遍历结果，以取得属性的可选值 */
        return array_map(function($v){
        	if ($v['property_group_id'] <= 0){
        	    // 处理属性
        	    // 获取属性额外信息
        	    $property = Trend_Model_Property::instance()->fetchByPK($v['property_id']);
        	    
        	    // 判断是否是读取规格
        	    if ($is_spec > 0 && $property[0]['is_spec'] != $is_spec) {
        	        continue;
        	    }
        	    
        	    $v['label_name'] = $property[0]['label_name'];
        	    
        	    // 获取属性值
        	    $where_property_value = "property_id = {$v['property_id']} AND status = 1";
        	    $order_property_value = "sort_order ASC";
        	    $v['values'] = Trend_Model_Property_Value::instance()->fetchByWhere($where_property_value, $order_property_value);
        	    
        	    return $v;
        	}
        	
        	// 获取分组下属性列表
        	$property_listing = Trend_Model_Property_To_Group::instance()->getPropertyInfo($v['property_group_id']);
        	
        	// 获取属性值
        	if (! empty($property_listing)) {
        	    foreach ($property_listing as &$vv) {
        	
        	        // 判断是否是读取规格
        	        if ($is_spec > 0 && $vv['is_spec'] != $is_spec) {
        	            continue;
        	        }
        	
        	        $where_property_value = "property_id = {$vv['property_id']} AND status = 1";
        	        $order_property_value = "sort_order ASC";
        	        $vv['values'] = Trend_Model_Property_Value::instance()->fetchByWhere($where_property_value, $order_property_value);
        	
                    return $vv;
        	    }
        	}
        }, $property_category);
    }
    
    
}

// End ^ LF ^ encoding
