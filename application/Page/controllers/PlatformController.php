<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class PlatformController extends IndexAbstract {
    
    /**
     * 平台公告列表
     */
    public function index(){
        $this->addResult(self::RS_SUCCESS, 'json');

        /* 平台公告 13-记住吧兄弟*/
        $order = 'mtime DESC';
        $article = Article_Model_Content::instance()->fetchByWhere('category = 13 and status = 1',$order);
        if (!empty($article)) {
            foreach ($article as $k=>&$v) {
                $detail = Article_Model_Content_Detail::instance()->fetchByWhere("content_id = {$v['content_id']}");
                $v['body'] = $detail ? $detail[0]['body'] : '';
            }
        }
        
        $data['articles'] = $article ? $article : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'platform.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 平台公告详情
     */
    public function detail(){
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $id = $this->input->query('id');
        
        $detail = News_Model_List::instance()->fetchByWhere("news_id = {$id}"  );
        
        $data['detail'] = $detail ? $detail[0] : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'platform.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /* json 获取平台公告列表*/
    public function getPlatform() {
        $this->addResult(self::RS_SUCCESS,'json');

        //category_id
        $category_id = 13; //平台公告  13 写死

        // 分页
        $psize = $this->input->query('pageSize');  //每页显示数
        $p =  $this->input->query('pageIndex'); //当前第几页

        $page = $p;
        $offset = $page * $psize;

        //list
        $list = News_Model_List::instance()->fetchByWhere("1=1", "ctime desc", $psize, $offset);

        $data['totalnum'] = News_Model_List::instance()->getCount("1=1");
        $data['list'] = $list ? $list : array();
        
        return $data;
        return self::RS_SUCCESS;
    }
    
}
