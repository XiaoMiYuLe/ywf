<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */

class IndexController extends IndexAbstract
{
    /**
     * 全站首页
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
         
        //广告
        $advert = Api_Advert_GetContent::run($params);
        
        //新手产品
        $newgoods = Goods_Model_List::instance()->fetchByWhere('goods_pattern=1 and is_del=0',null,null,null);
        foreach ($newgoods as $k => &$v) {
            if ($v['yield']) {
                $v['yield'] = round($v['yield'],0);
            } else {
                $v['yield'] = 0;
            }
        } 
                
        //精选产品
        $where = "is_del = 0 and goods_status = 1 and goods_id not in(1,109,117) and goods_pattern=2";
        $order[] = "sort desc";
        $filed = array('goods_id','goods_name','yield','financial_period','low_pay','comment','goods_type','goods_pattern','goods_status','all_fee','spare_fee','increasing_pay','is_voucher','is_interest');
        $goods = Goods_Model_List::instance()->fetchByWhere($where,$order,3,0,$filed); 
        
         foreach ($goods as $k => &$v) {
            if ($v['yield']) {
                $v['yield'] = round($v['yield'],0);
            } else {
                $v['yield'] = 0;
            }
        
            if ($v['all_fee'] > 0) {
                $v['schedule'] = (($v['all_fee']-$v['spare_fee'])/$v['all_fee'])*100;
            }
            if ($v['schedule'] < 1 && $v['schedule'] > 0) {
                $v['schedule'] = 1;
            } else {
                $v['schedule'] = floor($v['schedule']);
            }
        } 
         
        //财富知识汇
        $order = array(
    	    'recommended desc',
    	    'pinned desc',
    	    'ctime desc',);
                	
        $article_9 = Article_Model_Content::instance()->fetchByWhere('status=1 and category=10',$order,3,0);

        if(!empty($article_9)){
            foreach ($article_9 as $k=>&$v){
                $v['image'] = Support_Image_Url::getImageUrl($v['image']);
            }
        }
        
        //媒体报道
        $article_11 = Article_Model_Content::instance()->fetchByWhere('status=1 and category=11',$order,3,0);
        if(!empty($article_11)){
            foreach ($article_11 as $k=>&$v){
                $v['image'] = Support_Image_Url::getImageUrl($v['image']);
                
                if(strlen($v['alias']>80)){
                    $v['alias'] = mb_substr($v['alias'],0,80,'utf-8').'......';
                }
               
                
            }
        }
        
        //精选活动
        $article_12 = Article_Model_Content::instance()->fetchByWhere('status=1 and category=12',$order,1,0);
        if(!empty($article_12)){
            foreach ($article_12 as $k=>&$v){
                $v['image'] = Support_Image_Url::getImageUrl($v['image']);
            }
        }
        
        //理财公告
        $order1 = array(
            'ctime desc');
        $article_13 = News_Model_List::instance()->fetchByWhere('1=1',$order1,7,0);
        
        //常见问题
        $article_14 = Article_Model_Content::instance()->fetchByWhere('status=1 and category=22',$order,7,0);
        
        $data['advert'] = $advert['data'];
        $data['goods'] = $goods;
        $data['article_9'] = $article_9;
        $data['article_11'] = $article_11;
        $data['article_12'] = $article_12;
        $data['article_13'] = $article_13;
        $data['article_14'] = $article_14;
        $data['newgoods'] = $newgoods;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
}

// End ^ Native EOL ^ UTF-8
