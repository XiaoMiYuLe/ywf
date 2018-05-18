<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HelpController extends IndexAbstract {

    /**
     * 帮助中心
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');

        // 查出帮助中心下面的所有子分类 title='帮助中心'
        $parent_id = 15;
        $articles = Article_Model_Category::instance()->fetchByWhere("parent_id = {$parent_id}", array('category_id', 'title', 'title'));

        $data['navigation'] = $articles ? $articles : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'help.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    /**
     * 平台公告详情
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
        $this->addResult(self::RS_SUCCESS, 'php', 'help.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 帮助中心点击左侧导航，右边具体文章内容
     */
    public function getHelpContent() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //category_id
        $category_id = $this->input->query('category_id');

        // 分页
        $psize = $this->input->query('pageSize');  //每页显示数
        $p =  $this->input->query('pageIndex') + 1; //当前第几页

        $page = $p - 1;
        $offset = $page * $psize;

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
