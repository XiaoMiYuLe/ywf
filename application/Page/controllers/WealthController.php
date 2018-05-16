<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class WealthController extends IndexAbstract {
    
    /**
     * 财富知识主页
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');

        /* 财富知识 9-记住吧兄弟*/
        $order = ' ctime DESC';
        $article = Article_Model_Content::instance()->fetchByWhere('category = 10 and status = 1',$order);
        if (!empty($article)) {
            foreach ($article as $k=>&$v) {
                $detail = Article_Model_Content_Detail::instance()->fetchByWhere("content_id = {$v['content_id']}");
                $v['body'] = $detail ? $detail[0]['body'] : '';
            }
        }
        
        $data['articles'] = $article ? $article : array();
        /* echo '<pre>';
        print_r($article);die; */
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'wealth.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 财富知识详情,跳链接这里用不上了
     */
    public function detail(){
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $id = $this->input->query('id');
        
        $detail1 = Article_Model_Content::instance()->fetchByWhere("content_id = {$id} and status = 1"  );
        
        if ($detail1) {
            $detail2 = Article_Model_Content_Detail::instance()->fetchByWhere("content_id = {$id}");
            
            $detail = array_merge($detail1[0], $detail2[0]);
        }

        $data['detail'] = $detail ? $detail : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'wealth.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /* json 获取财富知识列表*/
    public function getWealth() {
        $this->addResult(self::RS_SUCCESS,'json');

        //category_id
        $category_id = 10; //财富知识默认分类是 9，写死

        // 分页
        $psize = $this->input->query('pageSize');  //每页显示数
        $p =  $this->input->query('pageIndex'); //当前第几页

        $page = $p;
        $offset = $page * $psize;
        $order = ' ctime DESC';
        //list
        $list = Article_Model_Content::instance()->fetchByWhere("category = {$category_id} and status = 1", $order, $psize, $offset);

        //拼出详情内容
        if (!empty($list)) {
            foreach ($list as $k => &$v) {
                $content_id = $v['content_id'];
                $detail = Article_Model_Content_Detail::instance()->fetchByWhere("content_id = {$content_id}");
                if ($detail) {
                    $v['body'] = $detail[0]['body'];
                } else {
                    $v['body'] = '';
                }
            }
        }

        $data['totalnum'] = Article_Model_Content::instance()->getCount("category = {$category_id} and status = 1");
        $data['list'] = $list ? $list : array();
        
        return $data;
        return self::RS_SUCCESS;
    }
    
}

