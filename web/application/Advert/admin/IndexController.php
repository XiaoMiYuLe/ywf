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
class IndexController extends AdvertAdminAbstract
{
    public $perpage = 15;

    /**
     * 广告管理
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
                $where[] = "title LIKE '%{$key}%'";
            }
        
            $order = 'ctime DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
        
            $content = Advert_Model_Content::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Advert_Model_Content::instance()->getCount($where);
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
            	if ($set['data']['type'] != 2) {
            	    $files = $set['data']['image'];
            	    
            	    if ($files['name']) {
            	    	$files_upload = Support_Attachment::upload($files);
            	    	
            	           
            	    	if ($files['error'] == UPLOAD_ERR_OK) {
            	    		$set['data']['image'] = $files_upload['filepath'];
            	    	} else {
            	    		throw new Zeed_Exception ('上传图片出现一些意外');
            	    	}
            	    } else {
            	    	unset($set['data']['image']);
            	    }
            	}
                Advert_Model_Content::instance()->addForEntity($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add advert failed : ' . $e->getMessage());
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
        $content_id = (int) $this->input->query('content_id');
        
        if (! $content = Advert_Model_Content::instance()->fetchByPK($content_id)) {
        	$this->setStatus(1);
            $this->setError('The advert is not exist.');
            return self::RS_SUCCESS;
        }
        $content = $content[0];

        /* 获取广告相关附件 */
        if ($content['attachmentid']) {
        	$attachment = Trend_Model_Attachment::instance()->fetchByAttchmentid($content['attachmentid']);
        	$data['attachment'] = $attachment[0];
        }
        /* 获取所有页面信息 */
        $data['content'] = $content;
        $data['content_id'] = $content_id;
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
            	    $content_id = $set['data']['content_id'];
    		
	    		/* 处理图片上传 */
	                $files = $set['data']['image'];
	                if ($files['name']) {
	                    $files_upload = Support_Attachment::upload($files);
	                    if ($files['error'] == UPLOAD_ERR_OK) {
	                        $set['data']['image'] = $files_upload['filepath'];
	                    } else {
	                        throw new Zeed_Exception('好像发生一些意外错误呢');
	                    }
	                } else {
	                    unset($set['data']['image']);
	                }
	                
	    			$where_store = "content_id = {$content_id}";
	    			Advert_Model_Content::instance()->update($set['data'], $where_store);
                }	   
            	
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit advert failed : ' . $e->getMessage());
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
                'content_id' => (int)$this->input->post('content_id', 0),
                'type' => $this->input->post('type'),
                'title' => $this->input->post('title'),
		        'link_url' => $this->input->post('link_url'),
                'image' => $_FILES['image'],
                'count' => (int)$this->input->post('count', 1),
		        'sort_order' => $this->input->post('sort_order'),
		        'status' => $this->input->post('status', 1),
		        'mtime' => date(DATETIME_FORMAT)
        );
        
    	if (! $res['data']['type']) {
            $res['status'] = 1;
            $res['error'] = '请选择广告类型';
            return $res;
        }
        
    	if (! $res['data']['title']) {
            $res['status'] = 1;
            $res['error'] = '广告名称不能为空';
            return $res;
        }
        
        /* 处理添加时间 */
        if (! $res['data']['content_id']) {
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
        $content_id = (int) $this->input->query('content_id');
        
        if (! $content_id) {
            $this->setStatus(1);
            $this->setError('缺少参数，或参数错误');
            return self::RS_SUCCESS;
        }
        
        try {
            Advert_Model_Content::instance()->deleteByPK($content_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除广告失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }

}

// End ^ Native EOL ^ UTF-8