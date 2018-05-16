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
class IndexController extends NewsAdminAbstract
{
    public $perpage = 15;

    /**
     * 消息管理
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
        
            $where[] = "1 = 1";
            if ($key) {
                $where[] = "news_title LIKE '%{$key}%'";
            }
        
            $order = 'ctime DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
        
            $content = News_Model_List::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = News_Model_List::instance()->getCount($where);
            $data['content'] = $content ? $content : array();
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
     * 添加
     */
    public function add ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加 - 保存
     */
    public function addSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
            	    
                News_Model_List::instance()->addForEntity($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add news failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 编辑
     */
    public function edit ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        /* 接收参数 */
        $news_id = (int) $this->input->query('news_id');
        
        if (! $content = News_Model_List::instance()->fetchByPK($news_id)) {
        	$this->setStatus(1);
            $this->setError('The news is not exist.');
            return self::RS_SUCCESS;
        }
        $content = $content[0];

        /* 获取所有页面信息 */
        $data['content'] = $content;
        $data['news_id'] = $news_id;
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 编辑 - 保存
     */
    public function editSave ()
    {
        $set = $this->_validate();
        
        if ($set['status'] == 0) {
            try {
            	    $news_id = $set['data']['news_id'];
    		
	    			$where = "news_id = {$news_id}";
	    			News_Model_List::instance()->update($set['data'], $where);
            	
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit news failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 保存消息 － 校验
     */
    private function _validate ()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $res['data'] = array(
                'news_id' => (int)$this->input->post('news_id', 0),
                'news_title' => $this->input->post('news_title'),
		        'news_content' => $this->input->post('news_content'),
        );
        
    	if (! $res['data']['news_title']) {
            $res['status'] = 1;
            $res['error'] = '消息标题不能为空';
            return $res;
        }
        
        /* 处理添加时间 */
        if (! $res['data']['news_id']) {
            $res['data']['ctime'] = date(DATETIME_FORMAT);
        }
        
        return $res;
    }
    
    /**
     * 删除消息
     */
    public function delete ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
        $news_id = (int) $this->input->query('news_id');
        
        if (! $news_id) {
            $this->setStatus(1);
            $this->setError('缺少参数，或参数错误');
            return self::RS_SUCCESS;
        }
        
        try {
            News_Model_List::instance()->delete("news_id = {$news_id}");
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除帮助失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }

}

// End ^ Native EOL ^ UTF-8