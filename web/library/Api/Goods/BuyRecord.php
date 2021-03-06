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
 * 获取产品投资记录
 */
class Api_Goods_BuyRecord
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
            	/*检验产品是否有效*/
                if (!$goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$res['data']['goods_id']} and is_del = 0")){
                	throw new Zeed_Exception('该产品不存在或已被删除');
                } 
                
                /*获取该产品相关订单*/
               	$content = Bts_Model_Order::instance()->fetchByWhere("goods_id = {$res['data']['goods_id']} and is_pay = 1","ctime desc",20,0,array('phone','buy_money','ctime'));
               	$list['content'] = (array)$content;
               	
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取产品投资记录出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            self::$_res['data'] = $list;
        }
        return self::$_res;
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
    	if (!isset($params['goods_id']) || strlen($params['goods_id'])<1) {
    		self::$_res['status'] = 1;
    		self::$_res['error'] = '参数产品ID goods_id 未提供';
    		return self::$_res;
    	}
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
