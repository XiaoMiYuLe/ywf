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
class IndexController extends StoreAdminAbstract
{
    public $perpage = 15;

    /**
     * 已签约商户列表
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
        $is_verify = (int) $this->input->get('is_verify');
        $status = (int) $this->input->get('status');
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
            
            $where[] = "status > -1";

            if ($is_verify > -1) {
                $where['is_verify'] = $is_verify;
            }
            if ($status > -1) {
                $where['status'] = $status;
            }
            if (! empty($key)) {
                $where[] = "(store_name LIKE '%{$key}%' OR company_name LIKE '%{$key}%' OR legalp_name LIKE '%{$key}%' OR tel LIKE '%{$key}%')";
            }
            
            $order = 'ctime DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
            
            $contents = Store_Model_Content::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Store_Model_Content::instance()->getCount($where);
            
            /* 处理一些基本信息 */
            if (! empty($contents)) {
                foreach ($contents as &$v) {
                    // 处理所属商户，即会员用户名
                    $user_info = Cas_Model_User::instance()->fetchByPK($v['userid'], array('username'));
                    if (! empty($user_info)) {
                        $v['username'] = $user_info[0]['username'];
                    }
                    
                    // 处理所属地区
                    $v['region_name'] = Trend_Helper_Region::getNameAllByRegionid($v['region_id']);
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
     * 添加
     */
    public function add ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $data = array();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加 - 保存
     */
    public function addSave ()
    {
        $data = $this->prepareSave();
        if ($data['status'] == 0) {
            if (! $store_id_insert = Store_ContentHelper::addContent($data['data'])) {
                $this->setStatus(1);
                $this->setError('添加商户失败。');
                return false;
            }
            $data['data']['store_id'] = $store_id_insert;
            $this->setData('data', $data['data']);
            return true;
        }
        
        return false;
    }

    /**
     * 修改
     */
    public function edit ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $store_id = (int) $this->input->query('store_id');
        
        /* 查询店铺主体信息 */
        if (! $store = Store_Model_Content::instance()->fetchByPK($store_id)) {
            $this->setStatus(1);
            $this->setError('查无此商户');
            return self::RS_SUCCESS;
        }
        $store = $store[0];
        
        /* 处理所在地区信息 */
        $region = Trend_Model_Region::instance()->fetchByPK($store['region_id']);
        if (! empty($region)) {
            $store['region_hid_arr'] = explode(':', $region[0]['hid']);
        }
        
        /* 处理发货地区信息 */
        $region_ship = Trend_Model_Region::instance()->fetchByPK($store['region_id_ship']);
        if (! empty($region_ship)) {
            $store['region_hid_ship_arr'] = explode(':', $region_ship[0]['hid']);
        }
        
        /* 查询所属商户，即用户信息 */
        $user_info = Cas_Model_User::instance()->fetchByPK($store['userid'], array('username'));
        
        $data['store_id'] = $store_id;
        $data['store'] = $store;
        $data['user_info'] = $user_info ? $user_info[0] : null;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 修改 - 保存
     */
    public function editSave ()
    {
        $data = $this->prepareSave();
        if ($data['status'] == 0) {
            if (! Store_ContentHelper::updateContentByStoreid($data['data']['store_id'], $data['data'])) {
                $this->setStatus(1);
                $this->setError('编辑商户失败。');
                return false;
            }
            return true;
        }
        
        return false;
    }

    /**
     * 审核
     */
    public function verify ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
        
        $store_id = (int) $this->input->post('store_id');
        
        try {
            /* 检查店铺是否存在 */
            if (! $store = Store_Model_Content::instance()->fetchByPK($store_id)) {
                throw new Zeed_Exception('查无此店铺');
            }
            
            /* 执行审核 */
            if ($store[0]['is_verify'] == 1) {
                $is_verify = 0;
            } else {
                $is_verify = 1;
            }
            
            $set = array('is_verify' => $is_verify);
            Store_Model_Content::instance()->updateForEntity($set, $store_id);
            
            $data['is_verify'] = $is_verify;
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('更新状态失败 : ' . $e->getMessage());
            return false;
        }
        
        $this->setData('data', $data);
        return self::RS_SUCCESS;
    }

    /**
     * 店铺详情
     */
    public function detail ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $store_id = (int) $this->input->get('store_id');
        
        try {
            /* 查询店铺主体信息 */
            if (! $store = Store_Model_Content::instance()->fetchByPK($store_id)) {
                throw new Zeed_Exception('查无此店铺');
            }
            $store = $store[0];
            
            /* 处理所属地区 */
            $store['region_name'] = Trend_Helper_Region::getNameAllByRegionid($store['region_id']);
        
            /* 处理发货地区 */
            $store['region_name_ship'] = Trend_Helper_Region::getNameAllByRegionid($store['region_id_ship']);
            
            /* 查询所属商户，即用户信息 */
            $user_info = Cas_Model_User::instance()->fetchByPK($store['userid'], array('username'));
            
            $data['store'] = $store;
            $data['user_info'] = $user_info ? $user_info[0] : null;
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('获取店铺详情失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 校验公司名称是否可用
     */
    public function isCompanynameAvailable()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $company_name = trim($this->input->query('company_name'));
        
        if (Store_Model_Content::instance()->fetchByFV('company_name', $company_name)) {
            $this->setStatus(1);
            $this->setError('该公司名称已被注册，请重新填写');
            return self::RS_SUCCESS;
        }
        
        return self::RS_SUCCESS;
    }
    
    /**
     * 校验店铺名称是否可用
     */
    public function isStorenameAvailable()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $store_name = trim($this->input->query('store_name'));
        
        if (Store_Model_Content::instance()->fetchByFV('store_name', $store_name)) {
            $this->setStatus(1);
            $this->setError('该店铺名称已被注册，请重新填写');
            return self::RS_SUCCESS;
        }
        
        return self::RS_SUCCESS;
    }
}