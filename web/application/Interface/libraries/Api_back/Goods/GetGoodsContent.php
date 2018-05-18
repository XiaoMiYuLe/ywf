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
 * 获取商品列表
 */
class Api_Goods_GetGoodsContent
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
     *
     * @var int 默认条数 接口可以不传递该参数
     */
    protected static $_perpage = 3;

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
                    
                /*查询字段*/
                $feild = array(
                        'content_id',
                        'parent_id',
                        'brand_id',
                        'category',
                        'name',
                        'property_related',
                        'sku',
                        'price_market',
                        'price',
                        'stock',
                        'sales_volume',
                        'rank_average',
                        'is_shelf'
                );
                
                /* 获取商品数据 */
                $contents = Goods_Model_Content::instance()->fetchByPK($res['data']['content_id'], $feild);
                
                if (empty($contents)) {
                    self::$_res['status'] = 1;
                    self::$_res['error'] = '无此商品';
                    return self::$_res;
                }
                
                $contents = $contents[0];

                /* 判断是否下架 */
                if ($contents['is_shelf'] == 0) {
                    self::$_res['status'] = 1;
                    self::$_res['error'] = '此商品已下架';
                    return self::$_res;
                }

                /* 判断商品ID */
                $content_id = empty($contents['parent_id']) ? $res['data']['content_id'] : $contents['parent_id'];
                
                /* 获取商品详细信息 */
                $content_detail = Goods_Model_Content_Detail::instance()->fetchByPK($content_id);
                if (! empty($content_detail)) {
                    $content = array_merge($contents, $content_detail[0]);
                }

                /* 获取商品图片信息 */
                $config_attachment = Zeed_Storage::instance()->getConfig();
                if ($content['attachment']) {
                    $attachment_ids_temp = array();
                    $attachment_arr = explode(',', $content['attachment']);
                    $content_attachment = Trend_Model_Attachment::instance()->fetchByPK($attachment_arr);

                    if (! empty($content_attachment)) {
                        foreach ($content_attachment as $k => &$v) {
                            $v['thumbsrc'] = self::_generateThumbnailsUrl($v['filepath'], $v['mimetype'], 'AM');
                            $v['url'] = $config_attachment['url_prefix_b'] . $v['filepath'];
                            $img_info = Support_Image_Info::Wh( $v['filepath']);
                            $attachment_ids_temp[$k] = $img_info[0]['url_whole'];
                        }
                        $content['attachment'] = $content_attachment;
                    }
                    
                    $content['attachment_ids'] = implode('|', $attachment_ids_temp);
                }
                
                /* 读取属性 */
                $content['properties'] = Goods_Model_Property::instance()->getPropertyByCategoryid($res['data']['category']);

                /* 读取品牌信息 */
                $brand = Goods_Model_Brand::instance()->fetchByPK($content['brand_id'],array('brand_name'));
                $content['brand_name'] = $brand ? $brand[0]['brand_name'] : '';
                
                /*分类名称*/
                if ($category = Goods_Model_Category::instance()->fetchByPK($contents['category'], array('category_name'))) {
                    $content['category_name'] = $category[0]['category_name'];
                } else {
                    $content['category_name'] = '';
                }
                
                /*团购和抢购商品获取*/
                $content['bulk'] = '';
                $content['grab'] = '';
                
                /*是否安装抢购和团购模块，如果有该模块则获取团购抢购信息*/
                $groupApp = Admin_Model_App::instance()->fetchByPK('groupon');
                if ($groupApp){
                   
                    /*获取团购信息*/
                    $bulk = Groupon_Model_Bulk::instance()->fetchByFV('sku', $content['sku']);
                    
                    /*获取抢购信息*/
                    $grab = Groupon_Model_Grab::instance()->fetchByFV('sku', $content['sku']);
                    
                    $content['bulk'] = $bulk;
                    $content['grab'] = $grab;
                }
                
                /*活动数据获取*/
                $content['promotion'] = '';
                $promotionApp = Admin_Model_App::instance()->fetchByPK('promotion');
                if ($promotionApp){
                    
                	/* 根据content_id，获取活动数据，此处为content_id为goods_content主键 */
                    $promotion = Promotion_Model_Goods::instance()->GetPromotionByGoods($res['data']['content_id']);
                    $content['promotion'] = $promotion;
                }
                
                /* 收藏和点赞
                 * @todo用户登录，商品是否被收藏和点赞
                 * 依赖于点赞及收藏模块
                 * $content['is_favorite'] = 1 OR 0
                 * $content['is_agree'] = 1 OR 0
                 */
                $casApp = Admin_Model_App::instance()->fetchByPK('cas');
                if ($res['data']['token'] && $casApp) {
                    
                    /*获取用户userid*/
                    $userid = Cas_Token::getUserIdByToken($res['data']['token']);
                    /*获取是否收藏*/
                    $where = "content = {$content_id} and userid='{$userid}' and type = 'goods' ";
                    $content['is_favorite'] = self::_getFavorite($where);
                   
                    /*获取当前用户是否已经点赞*/
                    $where = "to_type = 'goods' and userid = {$userid} and to_id = {$res['data']['content_id']}";
                    $content['ctime'] = $contents['uptime'];
                    $content['is_agree'] = self::_getAction($where);
                    
                } else {
                    $content['is_agree'] = 0;
                    $content['is_favorite'] = 0;
                }

                /* 获取所有规格的该商品 */
                if ($contents['parent_id']){
                    $extGoods = Goods_Model_Content::instance()->fetchByFV('content_id', $contents['parent_id'], $feild);
                } else {
                    $extGoods = Goods_Model_Content::instance()->fetchByFV('parent_id', $contents['content_id'], $feild);
                }
                
                /*处理商品规格属性及扩展属性*/
                if ($extGoods){
                    array_push($extGoods, $contents);
                    array_walk($extGoods, function(&$v){
                        $v['property_related'] = Goods_Model_Content::instance()->fetchGoodsPropertyByContentId($v['content_id']);
                    });
                }
                
                if ($content['property']){
                    $extProperty = explode(',', $content['property']);
                    $content['property'] = array_map(function($v){
                        $extArr = explode('_', $v);
                        $extPropertyName = Trend_Model_Property::instance()->fetchByPK($extArr[0]);
                        $extPropertyValue = Trend_Model_Property_Value::instance()->fetchByPK($extArr[1]);
                        return array($extPropertyName[0]['label_name'] => $extPropertyValue[0]['property_value']);
                    }, $extProperty);
                }
                
                $content['allgoods'] = $extGoods;

                $content = json_encode($content);
               
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取商品出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            
            self::$_res['data'] = json_decode($content,true);
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
        ksort($params);
        if (! isset($params['content_id']) || strlen($params['content_id']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 content_id 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
    
    
    /**
     * 产生缩略图(JPEG格式)地址或文件类型图标地址 注意: 缩略图的扩展名可能不代表其真实的MIMEType
     *
     * @param boolean $filepath
     * @param string $mimetype 文件的MIMEType类型
     * @param string $thumbScheme 指定的已配置缩略图方案
     * @param string $urlPrefix 上传目录可访问地址
     * @return string 返回缩略图地址
     */
    protected static function _generateThumbnailsUrl($filepath, $mimetype, $thumbScheme, $urlPrefix = null)
    {
        if (substr($mimetype, 0, 6) != 'image/') {
            $configIconsAttachment = Zeed_Config::loadGroup('icon.attachment');
            if (! isset($configIconsAttachment['list'][$mimetype])) {
                $thumbUrl = $configIconsAttachment['default'];
            } else {
                $thumbUrl = $configIconsAttachment['list'][$mimetype];
            }
            return $thumbUrl;
        }
    
        $thumbUrl = '';
        $suffix = substr($filepath, strrpos($filepath, '.'));
        $thumbUrl = str_replace($suffix, '_' . $thumbScheme . $suffix, $filepath);
    
        if (is_null($urlPrefix)) {
            $config = Zeed_Storage::instance()->getConfig();
            $thumbUrl = $config['url_thumb_mng_prefix'] . $thumbUrl;
        } else {
            $thumbUrl = $urlPrefix . $thumbUrl;
        }
    
        return $thumbUrl;
    }
    
    /**
     * 依赖于Cas_User_Favorite
     * 
     * @todo 获取商品是否被收藏
     * @param string $content_id
     * @return integer 返回数据 1已收藏 0未收藏
     */
    protected static function _getFavorite($where = ''){}
    
    /**
     * 依赖于Cas_User_Action
     * 
     * @todo 获取商品是否被点赞
     * @param string $content_id
     * @return integer  返回数据 1已点赞 0未点赞
     */
    protected static function _getAction($where = ''){}

}

// End ^ Native EOL ^ encoding
