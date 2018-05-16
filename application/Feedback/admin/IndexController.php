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
class IndexController extends FeedbackAdminAbstract
{

    protected $perpage = 20;

    protected $count;

    protected $page;

    /**
     * 后台在线反馈列表
     *
     * @return string
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $key = trim($this->input->get('key'));
        $page = (int) $this->input->get('pageIndex', 0);
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $hasName = intval($this->input->get('hasName'));
        
        /* 分页处理 */
        $page += 1;
        $perpage = $this->input->get('pageSize', $this->perpage);
        $offset = ($page - 1) * $perpage;
        
        /* 搜索条件，1为匿名用户搜索条件，2为非匿名用户搜索条件 */
        $where = "1=1";
        if ($hasName == 1) {
            $where = 'fbc.userid=0';
        } else 
            if ($hasName == 2) {
                $where = 'fbc.userid!=0';
            }
        
        /* 关键字模糊搜索 */
        if ($key) {
            $where .= " AND (fbc.title LIKE '%{$key}%' OR fbc.body LIKE '%{$key}%')";
        }
        
        /* 排序 */
        $order = 'content_id DESC';
        if ($ordername) {
            $order = $ordername . " " . $orderby;
        }
        
        /* 获取反馈列表 */
        $feedback = Feedback_Model_Content::instance()->getFeedback($where, $order, $perpage, $offset);
        
        $data['feedback'] = $feedback ? $feedback : array();
        $data['page'] = $page;
        $data['count'] = Feedback_Model_Content::instance()->getFeedbackCount($where);
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 反馈信息详情
     *
     * @return string
     */
    public function detail ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $content_id = $this->input->get('content_id', 0);
        
        $feedback = Feedback_Model_Content::instance()->fetchFeedbackByContentId($content_id);
        
        if ($feedback) {
            $data['feedback'] = $feedback;
        }
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }
}
