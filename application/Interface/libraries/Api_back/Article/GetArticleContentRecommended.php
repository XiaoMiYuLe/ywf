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
 * 获取推荐文章列表
 * @todo 读取表 article_content
 */
class Api_Article_GetArticleContentRecommended
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
     * @return string Ambigous multitype:number, multitype:number string ,
     *         unknown, multitype:>
     */
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                $redis_set = Trend_Model_Setting::instance()->fetchByFV('name', 'is_open_redis','val');
                $redis_val = empty($redis_set) ? 0 : $redis_set[0]['val'];
                
                $cache_key = md5(json_encode($res['data']));
                $contents  = $redis_val == 1 ? Trend_Model_Redis::instance()->get($cache_key) : false;
                if (!$contents) {
                	// 分页
                	if(empty($res['data']['p'])) {
                		$res['data']['p'] = 1;
                	}
                	
                	if(empty($res['data']['psize'])) {
                		$res['data']['psize'] = 20;
                	}
                	
                	$page = $res['data']['p'] - 1;
                	$offset = $page * $res['data']['psize'];
                	
                	//查询条件与排序
                	$where = " status != -1 AND recommended = 1";
                	if(! empty($res['data']['parent_id'])){
                		$where .= " AND parent_id = {$res['data']['parent_id']}";
                	}
    
                	$order = " content_id DESC ";
                	
                	$contents = Article_Model_Content::instance()->fetchByWhere($where, $order, $res['data']['psize'], $offset, array('content_id', 'title', 'subtitle', 'image', 'userid', 'ctime'));
                	
                	//图片路径组合
                	if (! empty($contents)) {
                		foreach ($contents as $k => &$v) {
                			$v['image'] = Support_Image_Url::getImageUrl($v['image']);
                		}
                	}
                	$contents = json_encode($contents);
                	if ($redis_val == 1){ Trend_Model_Redis::instance()->set($cache_key, $contents);}
                }
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取文章列表出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            
            if (! empty($contents)) {
            	self::$_res['data'] = json_decode($contents,true);
            } else {
            	self::$_res['data'] = array();
            }
        }

        return self::$_res;
    }
    
    /**
     * 验证方法
     * @param unknown $params
     * @return multitype:number string
     */
    public static function validate($params)
    {
        ksort($params);
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
