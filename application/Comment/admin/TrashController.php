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
class TrashController extends CommentAdminAbstract
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
            
            $where = "is_del = 1 AND category_id in ('" . self::COMMENT_CATEGORY_GOODS . "', '" . self::COMMENT_CATEGORY_COUPON . "')";
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
        $this->addResult(self::RS_SUCCESS, 'php', 'trash.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 删除
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
            $where = "id in ({$ids})";
            Comment_Model_Content::instance()->update($set, $where);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除评论失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }


    /**
     * 修改
     */
    public function edit()
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    
    	if ($this->input->isPOST()) {
    		$this->editSave();
    		return self::RS_SUCCESS;
    	}
    
    	$id = (int) $this->input->query('id');
    
    	$comment = Comment_Model_Content::instance()->fetchByPK($id);
    	if (empty($comment)) {
    		$this->setStatus(1);
    		$this->setError('该评论不存在');
    		return self::RS_SUCCESS;
    	}
    	
    	$data['id'] = $id;
    	$data['comment'] = $comment ? $comment[0] : null;
    
    	$this->setData('data', $data);
    	$this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
    	return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改 - 保存
     */
    public function editSave()
    {
    	$set = $this->_validate();
    	if ($set['status'] == 0) {
    		try {
    			$files = $set['data']['content'];
    			if ($files['name']) {
    				$files_upload = Support_Attachment::upload($files);
    				if ($files['error'] == UPLOAD_ERR_OK) {
    					$set['data']['content'] = $files_upload['filepath'];
    				} else {
    					throw new Zeed_Exception('好像发生一些意外错误呢');
    				}
    			} else {
    				unset($set['data']['content']);
    			}
    			 
    			Comment_Model_Content::instance()->updateForEntity($set['data'], $set['data']['id']);
    		} catch (Zeed_Exception $e) {
    			$this->setStatus(1);
    			$this->setError('编辑评论失败 : ' . $e->getMessage());
    			return false;
    		}
    		return true;
    	}
    	 
    	$this->setStatus($set['status']);
    	$this->setError($set['error']);
    	return false;
    }
    
    private function _validate ()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $res['data'] = array('id' => (int) $this->input->post('id'), 'is_audit' => (int) $this->input->post('is_audit'));
        
        if (empty($res['data']['id'])) {
            $res['status'] = 1;
            $res['error'] = '评论不存在';
            return $res;
        }
        
        if (! in_array($res['data']['is_audit'], array('1', '2'))) {
            $res['status'] = 1;
            $res['error'] = '审核状态不正确';
            return $res;
        }
        return $res;
    }
    
    /**
     * 评价详情
     */
    public function detail()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        $id = (int) $this->input->query('id');
    
        try {
            if (! $comment = Comment_Model_Content::instance()->fetchByPK($id)) {
                throw new Zeed_Exception('查无此评论');
            }
    
            $where = "comment_id={$comment[0]['id']}";
            $order = "attachmentid ASC";
            $attachment = Comment_Model_Attachment::instance()->fetchByWhere($where, $order);
            
            $trend_attachment_id = '';
            $comment[0]['attachment1']['attachment1'] = '';
            if(! empty($attachment)){
            	foreach ($attachment as $k => $v){
            		$trend_attachment_id .= $v['attachmentid'];
            		$comment[0]['attachment1']['attachment1'] .= $v['attachmentid'] . ',';
            	}
            }
             
            if($comment[0]['attachment1']['attachment1']){
            	$comment[0]['attachment1']['list'] = Trend_Model_Attachment::instance()->fetchByWhere("attachmentid in({$comment[0]['attachment1']['attachment1']}0)", "attachmentid ASC");
            }
            
            $data['comment'] = $comment[0];
            
            	if ( $data['comment']['category_id'] == self::COMMENT_CATEGORY_GOODS) {
            	    // 评论（商品）信息
            	    $result = Goods_Model_Content::instance()->fetchByPk( $data['comment']['to_id']);
            		$data['comment']['title'] = $result[0]['name'];
            		$data['comment']['obj_id'] = $result[0]['goods_id'];
            	} elseif ( $data['comment']['category_id'] == self::COMMENT_CATEGORY_COUPON) {
            	    // 评论（优惠券）信息
            	    $result = Coupon_Model_Content::instance()->fetchByPk( $data['comment']['to_id']);
            		$data['comment']['title'] = $result[0]['coupon_name'];
            		$data['comment']['obj_id'] = $result[0]['coupon_id'];
            	}
            	
            	// 问卷调查信息
            	$qwhere['category_id'] = $data['comment']['category_id'];
            	$qwhere['userid'] = $data['comment']['userid'];
            	$qwhere['status'] = 1;
            	$question = Faq_Model_Question::instance()->fetchByWhere($qwhere);
            	
            	foreach ($question as $k=>$v){
            	    $awhere['category_id'] = $v['category_id'];
            	    $awhere['question_id'] = $v['question_id'];
            	    $awhere['to_id'] = $data['comment']['to_id'];
            	    $answer = Faq_Model_Answer::instance()->fetchByWhere($awhere);
            	    $question[$k]['answer'] = $answer[0]['body'];
            	}
            	
            	$data['comment']['faq'] = $question;
            	
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('查看详情失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }
}

// End ^ Native EOL ^ UTF-8