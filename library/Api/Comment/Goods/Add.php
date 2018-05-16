<?php

/**
 * 添加商品评论
 *
 * @author hudongsheng <396256087@qq.com>
 */
class Api_Comment_Goods_Add
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                $res['data']['userid'] = Cas_Token::getUserIdByToken($res['data']['token']);
				
				$notime = date("Y-m-d H:i:s",time());
                $res['data']['ctime'] = $notime;
                $res['data']['mtime'] = $notime;
				
            	// 只是提供商端查看，无条件和排序
            	$res['id'] = Comment_Model_Content::instance()->addForEntity($res['data']);
            	
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '添加商品评论出错。错误信息：' . $e->getMessage();
                return $res;
            }
        }
        
        return $res;
    }
    
    public static function validate($params)
    {
    	if (! intval( $params['category_id'] )) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 category_id未提供';
            return self::$_res;
        }
        
    	if (! intval( $params['to_id'] )) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 to_id未提供';
            return self::$_res;
        }
        
    	if (! $params['to_order_item_id'] ) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 to_order_item_id未提供';
            return self::$_res;
        }
        
    	if (! $params['content']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 content未提供';
            return self::$_res;
        }
        
        if (! $params['token'] || ! Cas_Token::isTokenTime($params['token'])) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 token未提供或无效的token';
            return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}
