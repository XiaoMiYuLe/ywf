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
class StoreAdminAbstract extends AdminAbstract
{
    /**
     * 保存商户前的预处理
     */
    protected function prepareSave()
    {
        /**
         * raw data for $_POST
         */
        $data = $this->input->post();
        
        /**
         * validating company name
         */
        if (! isset($data['company_name']) || ! $data['company_name']) {
            $this->setStatus(1);
            $this->setError('请填写公司名称');
            return $this->_data;
        }
        
        if ($data['company_name'] != $data['company_name_edit'] && ! Store_ContentHelper::isCompanynameAvailable($data['company_name'])) {
            $this->setStatus(1);
            $this->setError('该公司名称已被注册，请重新填写');
            return $this->_data;
        }
        
        /**
         * validating store name
         */
        if ($data['store_name'] && $data['store_name'] != $data['store_name_edit'] && ! Store_ContentHelper::isStorenameAvailable($data['store_name'])) {
            $this->setStatus(1);
            $this->setError('该店铺名称已被注册，请重新填写');
            return $this->_data;
        }
        
        /**
         * validating user account
         * 
         * 1、添加时，接收账户信息，并进行校验
         * 2、编辑时，若填写了重置密码，则对重置密码进行校验
         */
        if (! $data['store_id']) {
            /* 校验用户名的合法性 */
            if (! Cas_Validator::username($data['username'])) {
                $this->setStatus(1);
                $this->setError('管理员帐号无效');
                return $this->_data;
            }
            
            /* 校验用户名是否已存在 */
            $userExists = Cas_Model_User::instance()->isUserExistent($data['username']);
            if ($userExists) {
                $this->setStatus(1);
                $this->setError('管理员帐号已存在，请重新填写');
                return $this->_data;
            }
            
            /* 校验密码 */
            if (true !== Cas_Validator::password($data['password'])) {
                $this->setStatus(1);
                $this->setError('管理员密码无效');
                return $this->_data;
            }
            if (isset($data['repassword']) && $data['password'] != $data['repassword']) {
                $this->setStatus(1);
                $this->setError('两次输入密码不一致');
                return $this->_data;
            }
        } elseif (isset($data['password']) && $data['password']) {
            if (true !== Cas_Validator::password($data['password'])) {
                $this->setStatus(1);
                $this->setError('管理员密码无效');
                return $this->_data;
            }
        }
        
        /**
         * validating UUID
         *
         * @todo 商户信息自动保存时需要用到该字段
         */
        if (! isset($data['uuid'])) {
            $data['uuid'] = Zeed_Util_UUID::generate();
        } elseif (0) {
            $this->setStatus(1);
            $this->setError('uuid error');
        }
    
        $this->_data['data'] = $data;
    
        return $this->_data;
    }
}