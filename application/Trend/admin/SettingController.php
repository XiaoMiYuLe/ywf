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

class SettingController extends TrendAdminAbstract
{
    /**
     * 系统设置列表
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 获取所有的设置信息 */
        $settings = Trend_Model_Setting::instance()->getAllSettings();
        
        /* 设置基本信息处理 */
        if (! empty($settings)) {
            foreach ($settings as &$v) {
                // 处理 checkbox 的设置值
                if ($v['val_inputtype'] == 'checkbox') {
                    $v['val'] = $v['val'] ? explode(',', $v['val']) : array();
                }
                
                // 处理设置中的可选值
                if (! $v['val_options']) {
                    continue;
                }
                
                $options = explode(',', $v['val_options']);
                foreach ($options as $kk => $vv) {
                    $options_single = explode(':', $vv);
                    $v['val_options_arr'][$kk]['option_title'] = $options_single[0];
                    $v['val_options_arr'][$kk]['option_value'] = $options_single[1];
                }
            }
        }
        
        /* 获取设置分组信息 */
        $data['setting_group'] = Trend_Model_Setting_Group::instance()->fetchAll()->toArray();
        
        /* 获取所有设置值 */
        $data['settings'] = $settings ? Util_Custom::setArrayIndex($settings, 1, 'name') : array();
        
        /* 有效期 */
        $data['validity_arr'] = SettingHelper::getValidityList();
        
        /* 评价奖励 */
        $data['award_comment'] = SettingHelper::getAwardCommentList();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 设置设置值
     */
    public function setVal()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isAJAX()) {
            $this->setStatus(1);
            $this->setError('请求错误');
            return self::RS_SUCCESS;
        }
    
        /* 接收参数 */
        $setting_name = $this->input->query('setting_name');
        $setting_val  = $this->input->query('val');
        $group_id     = $this->input->query('group_id');
        $setting_note = $this->input->query('note');
        
        try {
            /* 获取设置的基本信息 */
            $setting = Trend_Model_Setting::instance()->fetchByFV('name', $setting_name);
            
            $set = NULL;
            $set['group_id'] = $group_id;
            $set['val']      = $setting_val;
            $set['note']     = $setting_note;
            
            if (empty($setting)) { // 添加
                $set['name'] = $setting_name;
                Trend_Model_Setting::instance()->addForEntity($set);
                $this->setError('添加成功！');
            } else { // 修改
                Trend_Model_Setting::instance()->update($set, "name='" . $setting_name . "'");
                $this->setError('更新成功！');
            }
            $this->setStatus(0);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('操作失败！');
            return false;
        }
        
        return self::RS_SUCCESS;
    }
    
    /**
     * 上传图片
     */
    public function uploadFile()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $setting_id = $this->input->query('setting_id');
        $name = $this->input->query('name');
        $files = $_FILES[$name];
        
        /* 执行上传，并更新设置值 */
        if ($files['name']) {
            $files_upload = Support_Attachment::upload($files);
            if ($files['error'] == UPLOAD_ERR_OK) {
                Trend_Model_Setting::instance()->updateForEntity(array('val' => $files_upload['filepath']), $setting_id);
                
                $data['url'] = $files_upload['url'];
                $this->setData('data', $data);
            } else {
                $this->setStatus(1);
                $this->setError('好像发生一些意外错误呢');
                return self::RS_SUCCESS;
            }
        }
        
        return self::RS_SUCCESS;
    }
    
    /**
     * 删除图片
     */
    public function dropFile()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $setting_id = (int) $this->input->post('setting_id');
    
        try {
            Trend_Model_Setting::instance()->updateForEntity(array('val' => ''), $setting_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除图片失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
    
    /**
     * 添加 - 仅供开发者使用
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
    
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存 - 仅供开发者使用
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                if (! $setting_id = Trend_Model_Setting::instance()->addForEntity($set['data'])) {
                    throw new Zeed_Exception('添加设置项失败');
                }
                $this->setData('setting_id', $setting_id);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加设置项失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存－校验 - 仅供开发者使用
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $res['data'] = $this->input->post();
        
        try {
            
            /* 数据验证 */
            if (empty($res['data']['name']) || empty($res['data']['label_name'])) {
                throw new Zeed_Exception('请填写完所有必填项');
            }
            
            if ($res['data']['val_inputtype'] == 'select' || $res['data']['val_inputtype'] == 'radio' || $res['data']['val_inputtype'] == 'checkbox') {
                throw new Zeed_Exception('请填写完所有必填项');
            }
            
            if (empty($res['data']['val_options'])) {
                throw new Zeed_Exception('请填写完所有必填项');
            }
            
            /* 校验参数名的唯一性 */
            if (Trend_Model_Setting::instance()->fetchByFV('name', $res['data']['name'])) {
                throw new Zeed_Exception('参数名已被占用，请重新填写');
            }
            
        } catch (Zeed_Exception $e) {
            $res['status'] = 1;
            $res['error'] = $e->getMessage();
            return $res;
        }
        
        return $res;
    }
}

// End ^ Native EOL ^ UTF-8