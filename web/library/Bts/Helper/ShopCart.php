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
 * @since      Jun 24, 2010
 * @version    SVN: $Id$
 */
class Bts_Helper_ShopCart
{
	protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
	
	/**
	 * 添加到购物车
	 */
	public static function addToCart ($content_id, $userid, $quantity)
	{
		// 验证	
	    $res = self::validate($content_id);
	    
	    if ($res['status'] != 0) {
	        return self::$_res;
	    }
	    
	    try {
            $quantity = max($quantity, 1);
            
            /* 判断所选商品是否存在于购物车 */
            $cart_info = Bts_Model_Cart::instance()->isExistGoodsByUserId($userid, $content_id, true);
            
            if (! empty($cart_info)) {
                /* 更新产品数量 */
    			$res = self::_updateCartProductNumByUserId($content_id, $userid, $quantity, 1, 
                            $cart_info[0]['quantity']);
                if (!$res) {
                    throw new Zeed_Exception('服务器处理异常，请稍候重试');
                }
            } else {
                /* 将商品加入购物车 */
                $cart_item = array(
                        'userid' => $userid,
                        'session_id' => '',
                        'quantity' => $quantity,
                        'content_id' => $content_id,
                        'ctime' => date(DATETIME_FORMAT)
                );
                Bts_Model_Cart::instance()->addToCart($cart_item);
            }
        } catch (Exception $e) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '信息错误：' . $e->getMessage();
            return self::$_res;
        }
	    
		/* 消息提示 */
		self::$_res['status'] = 0;
		self::$_res['error'] = '';
		self::$_res['data'] = '加入成功';

		return self::$_res;
	}
	
	/**
	 * 从购物车删除
	 */
	public static function dropFromCart ($cart_id, $userid)
	{
        if (self::deleteCart($cart_id, $userid)) {
            self::$_res['status'] = 0;
            self::$_res['error'] = '删除成功';
            return self::$_res;
        } else {
            self::$_res['status'] = 1;
            self::$_res['error'] = '删除失败';
            return self::$_res;
        }
	
	}
	
	/**
	 * 实现更新购物车中某产品的数量
	 *
	 * @param integer $goods_id
	 * @param integer $quantity
	 * @param integer $user_id
	 * @return boolean
	 */
	private static function _updateCartProductNumByUserId ($content_id, $userid ,$quantity, $type = 0, $cart_num = 0)
	{
		// 这里不用判断数量 因为在这一步前面 必须判断 商品库存 这里不判断是否暴库
		$where = ' content_id ='. $content_id;
        if (empty($userid)) {
            return false;
        } 
        $where .= ' and userid=' . $userid;
        
		switch ($type) {
			case 1:
				/* 增加数量 */
				$quantity = $cart_num + $quantity;
				break;
			case 2:
				/* 减少数量 */
				$quantity = $cart_num - $quantity;
				break;
			default:
				break;
		}
	
		$set['quantity'] = $quantity;
	
		$res = Bts_Model_Cart::instance()->updateByWhere($where, $set);
	
		return $res;
	}
	
	/**
	 * 购物车删除商品
	 * @param 特定组合STRING $goods_id
	 * @param 用户ID $userid
	 * @return boolean
	 */
	private static function deleteCart($cart_id,$userid)
	{
		try {
            /* 用户ID不存在直接返回 */
            if (empty($userid)) {
                return false;
            }
            // '1,2,3,' 判断是否是多个进行购物车匹配删除
            if (is_string($cart_id)) {
                if (strpos($cart_id, ',')) {
                    $cart_id = explode(',', $cart_id);
                    foreach ($cart_id as $value) {
                        $where = ' userid=' . $userid;
                        $where .= " and cart_id ={$value}";
                        $res = Bts_Model_Cart::instance()->delete($where);
                    }
                    return true;
                } else {
                    /* 单个 */
                    $where = ' userid=' . $userid;
                    $where .= " and cart_id ={$cart_id}";
                    $res = Bts_Model_Cart::instance()->delete($where);
                    return true;
                }
            }
		}catch (Zeed_Exception $e){
			return false;
		}
		return false;
	}
	
	/**
	 * 验证方法
	 * 
	 * @param int $content_id
	 * @return multitype:number string |string
	 */
	private static function validate($content_id) {
	    
	    /* 校验参数  */
	    
	    try {
                /* 判断商品ID是否传入 */
            if (! is_numeric($content_id) || $content_id < 1) {
                throw new Zeed_Exception('加入购物车失败：您所选择的商品不存在');
            }
            
            /* 获取商品主体信息 */
            $content = Goods_Model_Content::instance()->fetchByPK($content_id, 
                    array(
                            'content_id',
                            'name',
                            'is_shelf'
                    ));
            
            if (! $content) {
                throw new Zeed_Exception('加入购物车失败：您所选择的商品不存在');
            }
            
            $content = $content[0];
            
            /* 判断所选商品是否已下架 */
            if ($content['is_shelf'] == 0) {
                throw new Zeed_Exception('加入购物车失败：您所选择的商品已下架');
            }
            
	    } catch (Exception $e) {
	        self::$_res['status'] = 1;
	        self::$_res['error'] = '信息错误：' . $e->getMessage();
	    }

	    $res['status'] = 0;
		return $res;
	}
	
}