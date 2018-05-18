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
class BoardController extends AdvertAdminAbstract
{
    public $perpage = 15;

    /**
     * 广告位管理
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
        $page_id = (int) $this->input->get('page_id');
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
        
            $where[] = "1 = 1";
            if ($page_id) {
                $where['page_id'] = $page_id;
            }
            if ($key) {
                $where[] = "name LIKE '%{$key}%'";
            }
        
            $order = 'ctime DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
        
            $boards = Advert_Model_Board::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Advert_Model_Board::instance()->getCount($where);
        
            $data['boards'] = $boards ? $boards : array();
        }
        
        /* 获取所有页面信息 */
        $data['pages'] = Advert_Model_Page::instance()->fetchAll()->toArray();
        
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'board.index');
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
        
        /* 获取所有页面信息 */
        $data['pages'] = Advert_Model_Page::instance()->fetchAll()->toArray();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'board.edit');
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
                Advert_Model_Board::instance()->addForEntity($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加广告位失败 : ' . $e->getMessage());
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
        
        $board_id = (int) $this->input->query('board_id');
        
        if (! $board = Advert_Model_Board::instance()->fetchByPK($board_id)) {
        	$this->setStatus(1);
            $this->setError('查无此广告位');
            return self::RS_SUCCESS;
        }
        $data['board'] = $board[0];
        
        /* 获取所有页面信息 */
        $data['pages'] = Advert_Model_Page::instance()->fetchAll()->toArray();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'board.edit');
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
                Advert_Model_Board::instance()->updateForEntity($set['data'], $set['data']['board_id']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑广告位失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 保存 － 校验
     */
    private function _validate ()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $res['data'] = array(
                'board_id' => $this->input->post('board_id',0),
                'page_id' => $this->input->post('page_id'),
                'type' => $this->input->post('type'),
                'name' => $this->input->post('name'),
                'memo' => $this->input->post('memo'),
                'width' => $this->input->post('width'),
                'height' => $this->input->post('height'),
                'sort_order' => $this->input->post('sort_order', 255),
                'status' => $this->input->post('status'),
                'mtime' => date(DATETIME_FORMAT)
        );
        
        /* 数据验证 */
    	if (! $res['data']['page_id']) {
            $res['status'] = 1;
            $res['error'] = '请选择所属页面';
            return $res;
        }
        
    	if (! $res['data']['type']) {
            $res['status'] = 1;
            $res['error'] = '请选择广告类型';
            return $res;
        }
        
    	if (! $res['data']['name']) {
            $res['status'] = 1;
            $res['error'] = '广告位名称不能为空';
            return $res;
        }
        
        /* 处理添加时间 */
        if (! $res['data']['board_id']) {
            $res['data']['ctime'] = $res['data']['mtime'];
        }
        
        return $res;
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
    
    	$board_id = (int) $this->input->post('id');
    
    	/* 获取内容 */
    	if (! $board = Advert_Model_Board::instance()->fetchByPK($board_id)) {
    		$this->setStatus(1);
    		$this->setError('查无此数据');
    		return self::RS_SUCCESS;
    	}
    
    	/* 执行审核 */
    	if ($board[0]['status'] == 1) {
    		$status = 0;
    	} else {
    		$status = 1;
    	}
    	$set = array('status' => $status);
    	Advert_Model_Board::instance()->updateForEntity($set, $board_id);
    
    	return self::RS_SUCCESS;
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
        
        $board_id = (int) $this->input->post('board_id',0);
        
        if (! $board_id) {
            $this->setStatus(1);
            $this->setError('缺少参数，或参数错误');
            return self::RS_SUCCESS;
        }
        
        try {
            /* 校验当前广告位是否可删除 */
            $contents = Advert_Model_content::instance()->fetchByFV('board_id', $board_id);
            if (! empty($contents)) {
                $this->setStatus(1);
                $this->setError('当前广告位下还有正在使用的广告，所以无法删除');
                return self::RS_SUCCESS;
            }
            
            /* 执行删除 */
            Advert_Model_Board::instance()->deleteByPK($board_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除广告位 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8