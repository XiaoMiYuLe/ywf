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
 * 获取广告
 * @todo 读取表 advert_content
 */
class Api_Advert_GetContent
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                
                /*查询条件*/
               	$where = "status = 1";
                $cols = array('content_id','title', 'link_url' ,'image');
                $advert = Advert_Model_Content::instance()->fetchByWhere($where, null, null, null, $cols);
                /*处理广告图片*/
                if(!empty($advert)){
	                foreach ($advert as $k => &$v) {
	                	$thumbnail_upload = Support_Attachment::_generateThumbnailsUrl($v['image'],'image/', 'AY');
	                	$config = Zeed_Config::loadGroup('urlmapping');
	                	$advert[$k]['image']  = $config['upload_cdn'].$thumbnail_upload;
	                    
	                }
                }
                $res['data'] = $advert ? $advert : array();
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '查询广告出错。错误信息：' . $e->getMessage();
                return $res;
            }
            
        }
        
        return $res;
    }
    
    public static function validate($params)
    {
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
