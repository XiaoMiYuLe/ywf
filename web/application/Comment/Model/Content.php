<?php

/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright  Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 9, 2010
 * @version    SVN: $Id$
 */
class Comment_Model_Content extends Zeed_Db_Model
{
    /*
     * @var string The table name.
     */

    protected $_name = 'content';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'comment_';

    public function getStoreComment($store_id, $category_id, $page, $pagesize)
    {
        //要查找的字段
        $fields = 'BOI.item_id,BOI.`goods_name`,BOI.`goods_image`,BOI.`goods_id`,BOI.specification_id,BOI.order_id,CC.`rank_base`,CC.`rank_logistics`,CC.`rank_speed`';

        $sql = 'SELECT %s FROM comment_content AS CC INNER JOIN bts_order_items AS BOI ON BOI.item_id = CC.to_order_items_id ';
        $sql .= 'WHERE CC.to_store_id = :store_id AND CC.category_id = :category_id ORDER BY CC.ctime DESC ';

        $bind = array(':store_id' => $store_id, ':category_id' => $category_id);

        $count = $this->query(sprintf($sql, 'count(*) AS num'), $bind)->fetch();

        $pageTotal = ceil($count['num'] / $pagesize);
        if ($page > $pageTotal) {
            $page = $pageTotal;
        }
        if($page < 1){
            $page = 1;
        }

        $sql .= ' LIMIT ' . (($page - 1) * $pagesize) . ',' . $pagesize;
        $list = $this->query(sprintf($sql, $fields), $bind)->fetchAll();
        foreach ($list as $key => $value) {
            $where = 'category_id = :category_id AND to_id = :to_id AND to_order_items_id = :to_order_items_id';
            $bind = array(':category_id' => 1, ':to_id' => $value['specification_id'], ':to_order_items_id' => $value['item_id']);
            $list[$key]['survey'] = Faq_Model_Answer::instance()->getAdapter()->select()->from('faq_answer', array('answer_id', 'body'))->where($where)->bind($bind)->query()->fetchAll();
        }
        return array(
            'totalnum' => $count['num'],
            'currentpage' => $page,
            'totalpage' => $pageTotal,
            'info' => (array) $list
        );
    }

    /**
     * 获取单个商品的平均分
     * @param integer $to_id 被评论的记录ID
     * @param integer $category_id 被评论的类型
     * @return array
     */
    public function getAVG($to_id, $category_id)
    {
        $fields = 'id AS comment_id,AVG(rank_base) AS base,AVG(rank_logistics) AS logistics,AVG(rank_speed) AS speed';
        $where = 'to_id = :to_id AND category_id = ' . $category_id;
        $bind = array(':to_id' => $to_id);
        return $this->getAdapter()->select()->from('comment_content', $fields)->where($where)->bind($bind)->query()->fetch();
    }

    

    
    /**
     * 根据商品ID获取评论内容
     * @param integer $content_id 被评论的记录ID
     * @return array
     */
    public function fetchCommentByContentId($content_id, $page = 1, $perpage = 20)
    {
        $whereContent = 'cc.category_id=2 AND cc.user_type="cas" AND cc.is_del=0';
        if ($content_id) {
            $whereContent .= ' AND to_id=' . $content_id;
        }
        $selectContent = $this->getAdapter()->select()->from($this->getTable() . ' AS cc');
        $selectContent->joinLeft('cas_user AS cu', 'cu.userid=cc.userid', array('nickname', 'avatar'));
        $selectContent->where($whereContent)->limit($perpage, ($page-1)*$perpage);
        $row = $selectContent->query()->fetchAll();
    
        if ($row) {
            foreach ($row as &$v) {
                $whereAttach = 'ca.comment_id=' . $v['id'];
                $selectAttach = $this->getAdapter()->select()->from('comment_attachment AS ca');
                $selectAttach->joinLeft('trend_attachment AS ta', 'ca.attachmentid=ta.attachmentid');
                $selectAttach->where($whereAttach);
                $v['attachment'] = $selectAttach->query()->fetchAll();
            }
        }
    
        return $row ? $row : null;
    }
    
    /**
     * 获取评论数量
     * @param integer $content_id 被评论的记录ID
     * @return array
     */
    public function fetchCountCommentByContentId($content_id)
    {
        $where = 'category_id=2 AND user_type="cas" AND is_del=0';
        if ($content_id) {
            $where .= ' AND to_id=' . $content_id;
        } else {
            return 0;
        }
    
        $select = $this->getAdapter()->select()->from($this->getTable(), array('count_num' => "COUNT(*)"));
        $row = $select->where($where)->query()->fetch();
    
        return $row ? $row["count_num"] : 0;
    }
    
    /**
     * 根据用户ID获取评论数量
     * @param integer $userid 被评论的记录ID
     * @return array
     */
    
    public function fetchCountCommentByUserId($userid, $order = null, $count = null, $offset = null)
    {
        $where = 'category_id=2 AND user_type="cas" AND is_del=0';
        if ($userid) {
            $where .= ' AND userid=' . $userid;
        } else {
            return 0;
        }
        
        $select = $this->getAdapter()->select()->from($this->getTable(), array('count_num' => "COUNT(*)"));
        
        if ($order !== null) {
        	$select->order($order);
        }
        if ($count !== null || $offset !== null) {
        	$select->limit($count, $offset);
        }
        
        $row = $select->where($where)->query()->fetch();
    
        return $row ? $row["count_num"] : 0;
    }
    
    /**
     * 根据用户ID获取评论内容
     * @param integer $content_id 被评论的记录ID
     * @return array
     */
    public function fetchCommentByUserId($userid, $order = null, $count = null, $offset = null, $cols = null)
    {
        $where = 'user_type="cas" AND is_del=0';
        if (!$userid) {
            return null;
        }
        $where .= ' AND userid=' . $userid;
        $select = $this->getAdapter()->select()->from($this->getTable() . ' AS cc');
        $select->joinLeft('comment_category AS ca', 'ca.category_id=cc.category_id', array('category_title' => 'title'));

        if ($order !== null) {
        	$select->order($order);
        }
        
        if ($count !== null || $offset !== null) {
        	$select->limit($count, $offset);
        }
        
        $comment = $select->where($where)->query()->fetchAll();
        
        foreach ($comment as $k => &$v) {
            switch ($v['category_id']) {
                case 1: {
    
                }
                case 2: {
                	unset($where);
      				$where['category_id'] = $v['category_id']; 
                    $content = Goods_Model_Content::instance()->fetchGoodsDetailById($v['to_id']);
                    $category = Comment_Model_Category::instance()->fetchByWhere($where);
                    //var_dump($category);exit;
                    if($content){
                   		$v['relate_content'] = $content;
                   		$v['relate_content']['href'] = '/goods/index/detail?content_id=' . $content['content_id'];
                    }
                    if($category){
                    	$v['category_title'] = $category[0]['title'];
                    }	
                }
            }
        }
    
        if ($comment) {
            foreach ($comment as $k => &$v) {
                $whereAttach = 'ca.comment_id=' . $v['id'];
                $selectAttach = $this->getAdapter()->select()->from('comment_attachment AS ca');
                $selectAttach->joinLeft('trend_attachment AS ta', 'ca.attachmentid=ta.attachmentid');
                $selectAttach->where($whereAttach);
                $v['attachment'] = $selectAttach->query()->fetchAll();
            }
        }
    
        return $comment ? $comment : null;
    }
    
    /**
     * 根据商品ID获取评论内容
     * @param integer $content_id 被评论的记录ID
     * @return array
     */
    public function deleteCommentById($id)
    {
        $set['is_del'] = 1;
        $row = $this->updateForEntity($set, $id);
    
        return $row ? $row : null;
    }

    /**
     * @return Comment_Model_Content
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
