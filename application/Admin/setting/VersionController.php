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
 * @since      2010-12-6
 * @version    SVN: $Id$
 */
class VersionController extends AdminAbstract
{

    public $perpage = 15;

    /**
     * 版本管理
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $platform = trim($this->input->get('platform'));
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
            
            $where = null;
            if ($platform) {
                $where = "platform = '{$platform}'";
            }
            
            $order = "ctime DESC";
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
            
            $versions = Trend_Model_Version::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Trend_Model_Version::instance()->getCount($where);
            
            $data['versions'] = $versions ? $versions : array();
        }
        
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'version.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加版本
     */
    public function add ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $this->addResult(self::RS_SUCCESS, 'php', 'version.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加版本 - 保存
     */
    public function addSave ()
    {
        $set = $this->_validate();
        
        if ($set['status'] == 0) {
            try {
                Trend_Model_Version::instance()->addForEntity($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加版本失败 : ' . $e->getMessage());
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
        
        $id = $this->input->query('id');
        
        $version = Trend_Model_Version::instance()->fetchByPK($id);
        if (empty($version)) {
            $this->setStatus(1);
            $this->setError('查无此版本');
            return self::RS_SUCCESS;
        }
        
        $data['version'] = $version[0];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'version.edit');
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
                /* 根据 ID删除商品 */
                $id = $set['data']['id'];
                unset($set['data']['id']);
                Trend_Model_Version::instance()->updateForEntity($set['data'], $id);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('修改失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 保存版本－校验
     */
    private function _validate ()
    {
        $res = array(
                'status' => 0,
                'error' => null,
                'data' => null
        );
        
        $res['data'] = array(
                'id' => $this->input->post('version_id', 0),
                'platform' => $this->input->post('platform', 'ipad'),
                'v_code' => $this->input->post('v_code'),
                'v_name' => $this->input->post('v_name'),
                'filepath' => $this->input->post('filepath'),
                'size' => $this->input->post('size'),
                'content' => $this->input->post('content'),
                'status' => $this->input->post('status'),
                'mtime' => date(DATETIME_FORMAT)
        );
        
        try {
            
            /* 类型验证 */
            if (empty($res['data']['platform']) || empty($res['data']['v_code'])) {
                throw new Zeed_Exception('平台类型不能为空 , 版本号不能为空');
            }
            
            if (empty($res['data']['v_name']) || empty($res['data']['filepath'])) {
                throw new Zeed_Exception('版本名称不能为空, 文件路径不能为空');
            }
            
            if (empty($res['data']['size'])) {
                throw new Zeed_Exception('安装包大小不能为空');
            }
            
            /* 处理创建时间字段 */
            if (! $res['data']['id']) {
                $res['data']['ctime'] = $res['data']['mtime'];
            }
        } catch (Zeed_Exception $e) {
            $res['status'] = 1;
            $res['error'] = $e->getMessage();
            return $res;
        }
        
        return $res;
    }

    /**
     * 删除版本
     */
    public function delete ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
        
        $id = (int) $this->input->post('id');
        
        try {
            /* 删除版本 */
            Trend_Model_Version::instance()->deleteByPK($id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除版本失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
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
        
        /* 获取版本内容 */
        if (! $version = Trend_Model_Version::instance()->fetchByPK($id)) {
            $this->setStatus(1);
            $this->setError('查无此版本');
            return self::RS_SUCCESS;
        }
        
        /* 执行审核 */
        if ($version[0]['status']) {
            $status = 0;
        } else {
            $status = 1;
        }
        $this->changeStatus($id, $status);
        
        return self::RS_SUCCESS;
    }

    /**
     * 更新版本状态
     */
    private function changeStatus ($id, $status = 0, $status_name = 'status')
    {
        try {
            $set = array(
                    $status_name => $status
            );
            
            if (is_string($id)) {
                if (strpos($id, ',')) {
                    $id = explode(',', $id);
                } else {
                    $id = array(
                            (int) $id
                    );
                }
                $id = implode(',', $id);
            }
            
            $where = "id in ({$id})";
            Trend_Model_Version::instance()->update($set, $where);
            
            $data['status'] = $status;
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('更新状态失败 : ' . $e->getMessage());
            return false;
        }
        
        $this->setError('更新成功');
        $this->setData('data', $data);
        return true;
    }
}

// End ^ Native EOL ^ UTF-8