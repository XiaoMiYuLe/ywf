<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      Aug 16, 2010
 * @version    SVN: $Id: Cas_Registrar.php 10911 2011-07-26 07:08:20Z woody $
 */

class Cas_Registrar
{
    private $status = 1;
    private $data;
    private $error;
    private $_parameters;
    private $_userdata = array();
    private $_validated = false;
    
    private $_usersource;
    
    private $_notStrict = false;
    
    /**
     * Constructor
     *
     * @param array $parameters 用户数据
     * @param boolean $_notStrict
     */
    public function __construct($parameters, $_notStrict = false)
    {
        $this->_parameters = $parameters;
        $this->_notStrict = $_notStrict;
    }
    
    /**
     * 注册用户
     *
     * @return boolean
     */
    public function register()
    {
        if (! $this->_validated && ! $this->validate()) {
            return false;
        }
        $casSettins = Zeed_Config::loadGroup('cas');
        $userStatus = isset($casSettins['default_status']) ? $casSettins['default_status'] : 1;
        $passwordEncrypt = $casSettins['default_encrypt'];
        $passwordSalt = Zeed_Encrypt::generateSalt();
        if ($this->_userdata['password'] == '') {
            $password = '';
        } else {
            $password = Zeed_Encrypt::encode($passwordEncrypt, $this->_userdata['password'], $passwordSalt);
        }
        
        $userSet = array(
                'username' => $this->_userdata['username'], 
                'user_group_id' => $this->_userdata['user_group_id'], 
                'user_lv_id' => $this->_userdata['user_lv_id'], 
                'username' => $this->_userdata['username'], 
                'thirdid' => isset($this->_userdata['thirdid']) ? $this->_userdata['thirdid'] : null, 
                'password' => $password, 
                'phone' => $this->_userdata['phone'], 
                'salt' => $passwordSalt, 
                'encrypt' => $passwordEncrypt, 
                'email' => $this->_userdata['email'], 
                'realname' => $this->_userdata['realname'], 
                'idcard' => $this->_userdata['idcard'], 
                'company_name' => $this->_userdata['company_name'], 
                'status' => $userStatus, 
                'ctime' => date(DATETIME_FORMAT), 
                'source' => (isset($this->_userdata['source']) ? $this->_userdata['source'] : ''));
        
        if (strlen($this->_userdata['idcard']) == 18) {
            $birthday = substr($this->_userdata['idcard'], 6, 8);
            $birthday = strtotime($birthday);
        } elseif (strlen($this->_userdata['idcard']) == 15) {
            $birthday = '19' . substr($this->_userdata['idcard'], 6, 6);
            $birbthday = strtotime($birthday);
        } else {
            $birthday = time();
        }
        $userSet['birthday'] = date(DATETIME_FORMAT, $birthday);
        
        try {
            foreach ($userSet as $k => $v) {
                if (empty($v)) {
                    unset($userSet[$k]);
                }
            }
            $userid = Cas_Model_User::instance()->add($userSet);
        } catch (Zeed_Exception $e) {
            $this->status = -1;
            $this->error['_system'] = '系统忙，注册失败，请重试' . $e->getMessage();
            return false;
        }
        
        $this->status = 0;
        $this->data = array('userid' => $userid, 'username' => $this->_userdata['username']);
        return true;
    }
    
    /**
     * 获取错误
     */
    public function getError()
    {
        return $this->error;
    }
    
    /**
     * 获取返回数据
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * 获取返回状态
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * 验证用户信息
     * 
     * 注册方式（根据字段 _simple 判断）：
     * 1：个性帐号注册；
     * 2：手机注册；
     * 9：邮箱注册；
     */
    public function validate()
    {
        if ($this->_notStrict) {
            $this->_userdata['invitation_code'] = trim($this->_parameters['invitation_code']);
            $this->_userdata['username'] = trim($this->_parameters['username']);
            $this->_userdata['phone'] = trim($this->_parameters['phone']);
            $this->_userdata['idcard'] = trim($this->_parameters['idcard']);
            $this->_userdata['realname'] = trim($this->_parameters['realname']);
            $this->_userdata['password'] = trim($this->_parameters['password']);
            $this->_userdata['email'] = trim($this->_parameters['email']);
            $this->_usersource['ip'] = isset($this->_parameters['ip']) ? trim($this->_parameters['ip']) : Zeed_Util::clientIP();
            return true;
        }
        
        $this->_userdata['password'] = $this->_parameters['password'];
        if (true !== Cas_Validator::password($this->_userdata['password'])) {
            $this->error['password'] = '密码无效';
            return false;
        }
        
        if (isset($this->_parameters['repassword']) && $this->_userdata['password'] != $this->_parameters['repassword']) {
            $this->error['repassword'] = '两次输入密码不一致';
            return false;
        }
        
        $mode = isset($this->_parameters['_simple']) ? $this->_parameters['_simple'] : 1;
        
        switch ($mode) {
            case 9:
                $this->_userdata['email'] = trim($this->_parameters['email']);
                
                if (! Cas_Validator::email($this->_userdata['email'])) {
                    $this->error['email'] = '电子邮箱地址无效';
                    return false;
                }
                $userExists = Cas_Model_User::instance()->verifyUserExistent($this->_parameters['email'], 'email');
                if ($userExists) {
                    $this->error['email'] = '电子邮箱已存在';
                    return false;
                }
                break;
            case 2:
                $this->_userdata['phone'] = trim($this->_parameters['phone']);
                
                if (($validatorResult = Cas_Validator::phone($this->_userdata['phone'])) && is_array($validatorResult)) {
                    $this->error['phone'] = array_pop($validatorResult);
                    return false;
                }
                $userExists = Cas_Model_User::instance()->verifyUserExistent($this->_parameters['phone'], 'phone');
                if ($userExists) {
                    $this->error['phone'] = '手机号已存在';
                    return false;
                }
                break;
            case 1:
                $this->_userdata['email'] = '';
                $this->_userdata['realname'] = '';
                $this->_userdata['idcard'] = '';
                $this->_userdata['username'] = trim($this->_parameters['username']);
                
                if (! Cas_Validator::username($this->_userdata['username'])) {
                    $this->error['username'] = '用户名无效';
                    return false;
                }
                $userExists = Cas_Model_User::instance()->isUserExistent($this->_parameters['username']);
                if ($userExists) {
                    $this->error['username'] = '用户名已存在';
                    return false;
                }
                break;
        }
        
        return $this->_validated = true;
    }
}

// End ^ Native EOL ^ encoding
