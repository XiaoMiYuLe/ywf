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
class IndexController extends CommentAdminAbstract
{
    protected $perpage = 15;

    /**
     * 评论后台首页
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
            
            $where = "is_del = 0 AND category_id in ('" . self::COMMENT_CATEGORY_GOODS . "', '" . self::COMMENT_CATEGORY_COUPON . "')";
            if ($key) {
                $where .= " AND `content` Like '%" . $key . "%' ";
            }
            
            $order = 'id DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
            
            $contents = Comment_Model_Content::instance()->fetchByWhere($where, $order, $perpage, $offset);            
            $data['count'] = Comment_Model_Content::instance()->getCount($where);           
            
            if (! empty($contents)) {
                /* 获取评论人信息、及评论对象的标题 */
                foreach ($contents as &$v) {
                    // 获取评论人信息
                    $user_info = Cas_Model_User::instance()->getUserByUserid($v['userid']);
                    $v['username'] = $user_info['nickname'] ? $user_info['nickname'] : $user_info['username'];
                    
                    // 获取评论对象标题
                    $v['to_name'] = '';
                    if ($v['category_id'] == self::COMMENT_CATEGORY_GOODS) {
                        $goods = Goods_Model_Content::instance()->fetchByPk($v['to_id'], array('name'));
                        $v['to_name'] = $goods ? $goods[0]['name'] : '';
                    } elseif ($v['category_id'] == self::COMMENT_CATEGORY_COUPON) {
                        $coupon = Coupon_Model_Content::instance()->fetchByPk($v['to_id'], array('coupon_name'));
                        $v['to_name'] = $coupon[0]['coupon_name'];
                    }
                }
            }
            
            $data['contents'] = $contents ? $contents : array();
        }
        
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 评价详情
     */
    public function detail()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        $id = (int) $this->input->query('id');
    
        try {
            /* 获取评论主体信息 */
            if (! $comment = Comment_Model_Content::instance()->fetchByPK($id)) {
                throw new Zeed_Exception('查无此评论');
            }
            $comment = $comment[0];
            
            /* 获取晒单信息 */
            $comment_attachment = Comment_Model_Attachment::instance()->fetchByFV('comment_id', $id);
            if (! empty($comment_attachment)) {
                $attachmentid = array();
            	foreach ($comment_attachment as $k => $v) {
            		$attachmentid[$k] = $v['attachmentid'];
            	}
            	$attachment = Trend_Model_Attachment::instance()->fetchByAttchmentid($attachmentid);
            	if (! empty($attachment)) {
            	    $comment['attachment'] = $attachment;
            	}
            }
             
            /* 获取评论对象信息 */
        	if ($comment['category_id'] == self::COMMENT_CATEGORY_GOODS) {
        	    // 评论（商品）信息
        	    $comment_object = Goods_Model_Content::instance()->fetchByPk($comment['to_id']);
        		$comment['comment_object'] = $comment_object[0];
        	} elseif ($comment['category_id'] == self::COMMENT_CATEGORY_COUPON) {
        	    // 评论（优惠券）信息
        	    $comment_object = Coupon_Model_Content::instance()->fetchByPk($comment['to_id']);
        		$comment['comment_object'] = $comment_object[0];
        	}
            
        	$data['comment'] = $comment;
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('查询评价详情失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 发布
     */
    public function publish ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $id = (int) $this->input->post('id');
        
        /* 获取评论内容 */
        if (! $comment = Comment_Model_Content::instance()->fetchByPK($id)) {
            $this->setStatus(1);
            $this->setError('查无此评论');
            return self::RS_SUCCESS;
        }
            
        /* 执行审核 */
        if ($comment[0]['status'] == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $set = array('status' => $status);
        Comment_Model_Content::instance()->updateForEntity($set, $id);
    
        return self::RS_SUCCESS;
    }
    
    /**
     * 删除 - 扔进回收站
     */
    public function delete ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $id = $this->input->post('id');
        if (is_string($id)) {
            if (strpos($id, ',')) {
                $id = explode(',', $id);
            } else {
                $id = array((int) $id);
            }
        }
        $ids = implode(',', $id);
    
        try {
            $set = array('is_del' => 1);
            $where = "id IN ({$ids})";
            Comment_Model_Content::instance()->update($set, $where);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除评论失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8