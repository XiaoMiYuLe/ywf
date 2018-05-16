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
class RegionController extends TrendAdminAbstract
{

    /**
     * 地区管理列表
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $where = 'pid = 0';
        $order = 'sort_order ASC';
        
        $data['regions'] = Trend_Model_Region::instance()->fetchByWhere($where, $order);
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'region.index');
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
        
        /* 获取参数 */
        $pid = $this->input->query('pid', 0);
        
        /* 获取父级地区信息 */
        $region_parent = Trend_Model_Region::instance()->fetchByPK($pid);
        
        $data['region_parent'] = ! empty($region_parent) ? $region_parent[0] : '';
        $data['pid'] = $pid;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'region.edit');
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
                if ($region_id = Trend_Model_Region::instance()->addForEntity($set['data'])) {
                    /* 更新 hid */
                    $setUpdate = array(
                            'hid' => $set['data']['hid'] . ':' . str_pad($region_id, 6, 0, STR_PAD_LEFT)
                    );
                    $setUpdate['grade'] = substr_count($setUpdate['hid'], ':');
                    Trend_Model_Region::instance()->updateForEntity($setUpdate, $region_id);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加地区失败 : ' . $e->getMessage());
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
        
        /* 获取参数 */
        $region_id = $this->input->query('region_id', 0);
        
        /* 获取地区信息 */
        $region = Trend_Model_Region::instance()->fetchByPK($region_id);
        if (empty($region)) {
            $this->setStatus(1);
            $this->setError('查无此地区。');
            return self::RS_SUCCESS;
        }
        $data['region'] = $region[0];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'region.edit');
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
                /* 去除不需更新的字段 */
                unset($set['data']['pid']);
                
                /* 执行更新 */
                Trend_Model_Region::instance()->updateForEntity($set['data'], $set['data']['region_id']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑地区失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 保存－校验
     */
    private function _validate ()
    {
        $res = array(
                'status' => 0,
                'error' => null,
                'data' => null
        );
        
        $res['data'] = array(
                'region_id' => $this->input->post('region_id', ''),
                'region_name' => trim($this->input->post('region_name')),
                'sort_order' => (int) $this->input->post('sort_order'),
                'pid' => (int) $this->input->post('pid', 0)
        );
        
        /* 数据验证 */
        if (empty($res['data']['region_name'])) {
            $res['status'] = 1;
            $res['error'] = '地区名称不能为空';
            return $res;
        }
        
        /* 构造 hid，仅在添加状态下生效 */
        $hid = 0;
        if ($res['data']['pid'] > 0) {
            $parent_region = Trend_Model_Region::instance()->fetchByPK($res['data']['pid']);
            if (is_array($parent_region) && count($parent_region) > 0) {
                $hid = $parent_region[0]['hid'];
            }
        }
        if (! $res['data']['region_id']) {
            $res['data']['hid'] = $hid;
        }
        
        return $res;
    }

    /**
     * 查看子级地区
     */
    public function getChilds ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $region_id = (int) $this->input->query('region_id');
        
        $where = "pid = {$region_id}";
        $order = "sort_order ASC";
        $data['regions'] = Trend_Model_Region::instance()->fetchByWhere($where, $order);
        
        $this->setData('data', $data);
        return self::RS_SUCCESS;
    }

    /**
     * 删除地区
     */
    public function delete ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 获取参数 */
        $region_id = (int) $this->input->query('region_id', 0);
        
        try {
            /* 有子地区的不能删除 */
            $where = array(
                    'pid' => $region_id
            );
            $region_children = Trend_Model_Region::instance()->fetchByWhere($where);
            if (! empty($region_children)) {
                throw new Zeed_Exception('该地区下有子级地区，不能删除');
            }
            
            /* 执行删除 */
            Trend_Model_Region::instance()->deleteByPK($region_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除地区失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setError('删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8