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
 * 获取品牌分类列表
 * 
 */
class Api_Goods_GetBrand
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    /**
     * 接口运行方法
     *
     * @param string $params
     * @throws Zeed_Exception
     * @return string|Ambigous <string, multitype:number, multitype:number string , unknown, multitype:>
     */
    public static function run($params = null)
    {
        
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            try {
                
            	/*获取所有商品分类*/
                if (! Goods_Model_Category::instance()->fetchByPK($res['data']['category_id'])) {
                    throw new Zeed_Exception('查无此分类');
                }
                
                /*根据商品分类获取商品品牌*/
                $brands = Goods_Model_Brand_Category::instance()->getBrandByCategoryid($res['data']['category_id']);
                $brands = json_encode($brands);
                
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取商品分类出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            
            if (! empty($brands)) {
            	self::$_res['data'] = json_decode($brands,true);
            } else {
            	self::$_res['data'] = array();
            }
            
        }

        return self::$_res;
    }
    
    /**
     * 数据校验
     *
     * @param unknown $params
     * @return multitype:number string
     */
    public static function validate($params)
    {
        ksort($params);
        if (! isset($params['category_id']) || strlen($params['category_id']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 category_id 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
