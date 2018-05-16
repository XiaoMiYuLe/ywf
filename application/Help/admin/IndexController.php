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
class IndexController extends HelpAdminAbstract
{
    public $perpage = 15;

    /**
     * 帮助管理
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
                $where[] = "help_title LIKE '%{$key}%'";
            }
        
            $order = 'ctime DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
        
            $content = Help_Model_List::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Help_Model_List::instance()->getCount($where);
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
            	    
                Help_Model_List::instance()->addForEntity($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add help failed : ' . $e->getMessage());
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
        $help_id = (int) $this->input->query('help_id');
      
        if (! $content = Help_Model_List::instance()->fetchByPK($help_id)) {
        	$this->setStatus(1);
            $this->setError('The help is not exist.');
            return self::RS_SUCCESS;
        }
        $content = $content[0];

        /* 获取所有页面信息 */
        $data['content'] = $content;
        $data['help_id'] = $help_id;
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
                if ($set['data']['type'] != 2) {
            	    $help_id = $set['data']['help_id'];
    		
	    			$where = "help_id = {$help_id}";
	    			
	    			Help_Model_List::instance()->update($set['data'], $where);
                }	   
            	
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit help failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 保存广告 － 校验
     */
    private function _validate ()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $res['data'] = array(
                'help_id' => (int)$this->input->post('help_id',0),
                'help_title' => $this->input->post('help_title'),
		        'help_content' => $this->input->post('help_content'),
                'mtime' => date(DATETIME_FORMAT),
        );
        
    	if (! $res['data']['help_title']) {
            $res['status'] = 1;
            $res['error'] = '帮助标题不能为空';
            return $res;
        }
        
        /* 处理添加时间 */
        if (! $res['data']['help_id']) {
            $res['data']['ctime'] = $res['data']['mtime'];
        }
        
        return $res;
    }
    
    /**
     * 删除广告
     */
    public function delete ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
        $help_id = (int) $this->input->query('help_id');
        
        if (! $help_id) {
            $this->setStatus(1);
            $this->setError('缺少参数，或参数错误');
            return self::RS_SUCCESS;
        }
        
        try {
            Help_Model_List::instance()->delete("help_id = {$help_id}");
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