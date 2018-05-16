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
class PersonalController extends AdminAbstract
{

    /**
     * 个人资料管理
     */
    public function info ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        /* 获取登录管理员信息 */
        $user_cache = Com_Admin_Authorization::getLoggedInUser();
        $userid = $user_cache['userid'];
        $user = UserModel::instance()->fetchByPK($userid);
        
        /* 获取用户组信息 */
        $user_group = UserGroupModel::instance()->fetchByFV('userid', $userid, array('groupid'));
        $groupids = array();
        foreach ($user_group as $v) {
            $groupids[] = $v['groupid'];
        }
        $groups = GroupModel::instance()->fetchByFV('groupid', $groupids);
        
        $data['user'] = $user;
        $data['groups'] = $groups;
        $data['userid'] = $userid;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'personal.info');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 密码管理
     */
    public function resetPassword ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->resetPasswordSave();
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'personal.resetpassword');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 更新个人资料 - 保存
     */
    public function editSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                /* 更新个人资料 */
                UserModel::instance()->updateUser($set['data'], $set['data']['userid']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('更新个人资料失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 更新个人资料－校验
     */
    private function _validate ()
    {
        $res = array(
                'status' => 0,
                'error' => null,
                'data' => null
        );
        
        $res['data'] = array(
                'userid' => $this->input->post('userid', ''),
                'nickname' => $this->input->post('nickname'),
                'gender' => $this->input->post('gender'),
                'email' => $this->input->post('email'),
                'mtime' => date(DATETIME_FORMAT)
        );
        
        /* 验证操作合法性 */
        $user_cache = Com_Admin_Authorization::getLoggedInUser();
        if ($user_cache['userid'] != $res['data']['userid']) {
            $res['status'] = 1;
            $res['error'] = '非法操作！不得随意篡改他人信息！';
            return $res;
        }
        
        return $res;
    }
    
    /**
     * 重置密码 - 保存
     */
    public function resetPasswordSave ()
    {
        $userid = $this->input->post('userid');
        $password_old = $this->input->post('password_old');
        $password = $this->input->post('password');
        $password_verify = $this->input->post('password_verify');
        
        try {
            
            /* 数据验证 */
            if (! $password_old || ! $password || ! $password_verify) {
                throw new Zeed_Exception('请填写完所有项目');
            }
            
            /* 验证新密码的两次输入是否一致 */
            if ($password_verify !== $password) {
                throw new Zeed_Exception('新密码两次输入不一致，请重新输入');
            }
            
            /* 检查新密码长度 */
            if (strlen($password) < 6) {
                throw new Zeed_Exception('新密码长度不得少于6位');
            }
            
            /* 校验原始密码是否正确 */
            $user = UserModel::instance()->fetchByPK($userid);
            if (Zeed_Encrypt::encode('Md5Md5', $password_old, $user[0]['salt']) != $user[0]['password']) {
                throw new Zeed_Exception('原始密码不正确');
            }
            
            /* 验证操作合法性 */
            $user_cache = Com_Admin_Authorization::getLoggedInUser();
            if ($user_cache['userid'] != $userid) {
                throw new Zeed_Exception('非法操作！不得随意篡改他人密码！');
            }
            
            /* 重置密码 */
            $set['password'] = Zeed_Encrypt::encode('Md5Md5', $password, $user[0]['salt']);
            UserModel::instance()->updateUser($set, $userid);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('更新个人资料失败 : ' . $e->getMessage());
            return false;
        }
        return true;
    }
}

// End ^ Native EOL ^ UTF-8