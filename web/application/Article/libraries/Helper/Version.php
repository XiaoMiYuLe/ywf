<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Cas
 * @package    Cas_Nickname
 * @subpackage Cas_Nickname
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @version    SVN: $Id: Nickname.php 11108 2011-08-09 02:33:42Z nroe $
 */
class Helper_Version
{

    /**
     * 版本库添加公用方法
     *
     * @param array $data            
     * @return boolean
     */
    public static function addVersion ($data, $type = 'all')
    {
        if ($type == 'content') {
            
            /* 取得主表信息 */
            $content_historys = array();
            $content_historys['title'] = $data['title'];
            $content_historys['user_type'] = $data['user_type'];
            $content_historys['userid'] = $data['userid'];
            $content_historys['ctime'] = $data['ctime'];
            $content_historys['rev'] = $data['rev'];
            $content_historys['data'] = serialize($data);
            
            // rev_body处理
            /* 获取文章主体 */
            $where = "status = 1 AND content_id = {$data['content_id']}";
            $order = "sort_order DESC";
            $rev_bodys = Article_Model_Content_Body::instance()->fetchByWhere($where, $order);
            
            $rev_bodys_string = array();
            
            if (! empty($rev_bodys)) {
                
                foreach ($rev_bodys as $key => $item) {
                    
                    $rev_bodys_string[] = $item['id'] . ":" . $item['rev'];
                }
                $content_historys['rev_body'] = implode(",", $rev_bodys_string);
            }
            
            Article_Model_Content_History::instance()->addForEntity($content_historys);
        }
        
        /* BODY添加版本库 */
        if ($type == 'body') {
            
            /* 操作body字表的版本库添加 */
            $data['data'] = serialize(array(
                    'body' => $data['body'],
                    'sort_order' => $data['sort_order'],
                    'status' => $data['status']
            ));
            
            $data['content_body_id'] = $data['id'];
            $data['ctime'] = date(DATETIME_FORMAT);
            unset($data['id']);
            Article_Model_Content_Body_History::instance()->addForEntity($data);
            
            /* 取得主表信息 */
            $contents = Article_Model_Content::instance()->fetchByPk($data['content_id']);
            $contents = $contents[0];
            
            /* 操作content_history字表的版本库添加 */
            $content_historys = array();
            $content_historys['title'] = $contents['title'];
            $content_historys['content_id'] = $contents['content_id'];
            $content_historys['user_type'] = $contents['user_type'];
            $content_historys['userid'] = $contents['userid'];
            $content_historys['ctime'] = date(DATETIME_FORMAT);
            $content_historys['rev'] = $contents['rev'];
            $content_historys['data'] = serialize($contents);
            
            /* 获取文章主体 */
            $where = " content_id = {$data['content_id']}";
            $order = "sort_order DESC";
            $rev_bodys = Article_Model_Content_Body::instance()->fetchByWhere($where, $order);
            
            if (! empty($rev_bodys)) {
                
                $rev_bodys_string = array();
                
                foreach ($rev_bodys as $key => $item) {
                    
                    if ($data['content_body_id'] == $item['id']) {
                        $rev_bodys_string[] = $data['content_body_id'] . ":" . $data['rev'];
                    } else {
                        
                        if ($item['status'] == - 1) {
                            continue;
                        } else {
                            $rev_bodys_string[] = $item['id'] . ":" . $item['rev'];
                        }
                    }
                }
                
                $content_historys['rev_body'] = implode(",", $rev_bodys_string);
            }
            
            Article_Model_Content_History::instance()->addForEntity($content_historys);
            
            /* 更新content表的版本 */
            $content_historys['rev'] = $content_historys['rev'] + 1;
            $set = array(
                    rev => $content_historys['rev']
            );
            Article_Model_Content::instance()->updateForEntity($set, $data['content_id']);
        }
        
        return true;
    }
}
