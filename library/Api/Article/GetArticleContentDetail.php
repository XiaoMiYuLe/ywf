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

class Api_Article_GetArticleContentDetail
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    /**
     * 允许输出的字段
     */
    protected static $_allowFields = array('content_id', 'title', 'parent_id', 'subtitle', 'image', 'body');
    
    /**
     * 接口运行方法
     *
     * @param string $params
     * @throws Zeed_Exception
     * @return string Ambigous multitype:number, multitype:number string ,
     *         unknown, multitype:>
     */
    public static function run($params = null)
    {
    	
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                /* $redis_set = Trend_Model_Setting::instance()->fetchByFV('name', 'is_open_redis','val');
                $redis_val = empty($redis_set) ? 0 : $redis_set[0]['val'];
                
                $cache_key = md5(json_encode($res['data']));
                $data      = $redis_val == 1 ? Trend_Model_Redis::instance()->get($cache_key) : false;
                if (!$data) { */
                
                    if ($data = Article_Model_Content::instance()->fetchByPK($params['content_id'], array(
                            'content_id',
                            'title',
                            'parent_id',
                            'subtitle',
                            'image'
                    ))) {
                        $content = Article_Model_Content_Detail::instance()->fetchByWhere("content_id = {$params['content_id']}", null);
                        $data[0]['body'] = $content[0]['body'];
                        $data[0]['image'] = Support_Image_Url::getImageUrl($data[0]['image']);
                        
                        $rs = json_encode($data[0]);
                        if ($redis_val == 1){ Trend_Model_Redis::instance()->set($cache_key, $rs);}
                    } else {
                        throw new Zeed_Exception('查无此文章');
                    }
                    
                //}
                
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '查询资讯详情出错。错误信息：' . $e->getMessage();
                return $res;
            }
            
            if (! empty($data)) {
            	$res['data'] = $data;
            } else {
            	$res['data'] = array();
            }
        }
            
        return $res;
    }
    
    /**
     * 验证方法
     * @param unknown $params
     * @return multitype:number string
     */
    public static function validate($params)
    {
        ksort($params);
        if (! isset($params['content_id']) || ! $params['content_id'] || ! intval($params['content_id'])) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 content_id 未提供或提供有误';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
