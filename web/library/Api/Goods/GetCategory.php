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
 * 获取商品分类列表
 * @todo 读取表 goods_category，以 sort_order 顺序排序
 */
class Api_Goods_GetCategory
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            try {
            	//查询条件与排序
            	$where = " status = 1 ";
            	$order = " sort_order ASC ";
            	if( $res['data']['parent_id'] ) {
            		$where .= " AND parent_id = {$res['data']['parent_id']} ";
            	} else {
            		$where .= " AND parent_id = 0 ";
            	}
            	
            	/* 已知口袋晋城商品分类为二级分类  */
            	// 要求显示全部分类信息
            	$categorys = Goods_Model_Category::instance()->fetchByWhere($where, $order, 200, 0, array('category_id', 'parent_id', 'category_name'));
            	
            	// 判断分类是否存在
            	if (! $categorys) {
                	throw new Zeed_Exception('分类不存在');
            	}
            	
            	foreach ($categorys as $k => $v) {
            	    $where = " status = 1 ";
            	    $where .=  " and parent_id = {$v['category_id']}" ;
            	    $order = " sort_order ASC ";
            	    $categorys[$k]['second'] = Goods_Model_Category::instance()->fetchByWhere($where, $order, 200, 0, array('category_id', 'parent_id', 'category_name'));
            	     
            	}
            	
            	$categorys = json_encode($categorys);
            	
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取商品分类出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            
            if (! empty($categorys)) {
            	self::$_res['data'] = json_decode($categorys,true);
            } else {
            	self::$_res['data'] = array();
            }
        }

        return self::$_res;
    }
    
    public static function validate($params)
    {
        ksort($params);
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
