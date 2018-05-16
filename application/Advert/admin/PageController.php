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
class PageController extends AdvertAdminAbstract
{
    public $perpage = 15;

    /**
     * 广告页管理
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
        
            $where = NULL;
            if ($key) {
				$where = "title LIKE '%{$key}%'";
    		}
        
            $order = 'ctime DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
        
            $pages = Advert_Model_Page::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Advert_Model_Page::instance()->getCount($where);
            
            $data['pages'] = $pages ? $pages : array();
        }
        
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        $data['key'] = $key;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'page.index');
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
        
        $this->addResult(self::RS_SUCCESS, 'php', 'page.edit');
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
                Advert_Model_Page::instance()->addForEntity($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加广告页面失败 : ' . $e->getMessage());
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
        
        $page_id = (int) $this->input->query('page_id');
        
        if (! $page = Advert_Model_Page::instance()->fetchByPK($page_id)) {
        	$this->setStatus(1);
            $this->setError('查无此页面');
            return self::RS_SUCCESS;
        }
        $data['page'] = $page[0];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'page.edit');
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
                Advert_Model_Page::instance()->updateForEntity($set['data'], $set['data']['page_id']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑页面失败 : ' . $e->getMessage());
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
                'page_id' => $this->input->post('page_id', 0),
                'title' => $this->input->post('title')
        );
        
        /* 数据验证 */
    	if (! $res['data']['title']) {
            $res['status'] = 1;
            $res['error'] = '页面标题不能为空';
            return $res;
        }
        
        /* 处理添加时间 */
        if (! $res['data']['page_id']) {
            $res['data']['ctime'] = date(DATETIME_FORMAT);
        }
        
        return $res;
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
        
        $page_id = (int) $this->input->post('page_id', 0);
        
    	if (! $page_id) {
            $this->setStatus(1);
            $this->setError('缺少参数，或参数错误');
            return self::RS_SUCCESS;
        }
        
        try {
            /* 校验当前页面是否可删除 */
            $boards = Advert_Model_Board::instance()->fetchByFV('page_id', $page_id);
            if (! empty($boards)) {
                $this->setStatus(1);
                $this->setError('当前页面下还有正在使用的广告位，所以无法删除');
                return self::RS_SUCCESS;
            }
            
            /* 执行删除 */
            Advert_Model_Page::instance()->deleteByPK($page_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除页面失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8