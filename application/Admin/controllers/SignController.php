<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 8, 2010
 * @version    SVN: $Id$
 */

class SignController extends Zeed_Controller_Action
{
    public $error;
    
    public function index()
    {
        echo 404;
        exit;
    }
    
    /**
     * 登录入口
     */
    public function in()
    {
        $this->continue = $this->getContinue();

        if (Com_Admin_Authorization::getLoggedInUser()) {
           // 已登录
           header('Location: ' . $this->continue);
           exit;
        }

        $this->addResult(self::RS_SUCCESS, 'php', 'sign.in');
        return self::RS_SUCCESS;
    }
    
    /**
     * 登录
     */
    public function login()
    {
        if (! $this->input->isAJAX()) {
            return $this->in();
        }
        
        $rd = array('status' => 1, 'data' => null, 'error' => null);
        $rs = $this->loginUser();
        if ($rs) {
            // 登录成功
            $rd['status'] = 0;
            $rd['data'] = $this->getContinue();
        } else {
            $rd['error'] = $this->error;
        }
        exit(json_encode($rd));
    }
    
    /**
     * 验证登录信息并登录用户
     */
    private function loginUser()
    {
        $tag = true;
        if (! Zeed_Captcha_Image::isValid('', $this->input->post('captcha'))) {
            $this->error = '验证码不正确';
        }
        
        $username = trim($this->input->post('username'));
        $password = trim($this->input->post('password'));
        
        if (strlen($username) < 3 || strlen($password) < 6) {
            $this->error = '请输入正确的用户名和密码';
            $tag = false;
        }
        
        $user = UserModel::instance()->fetchByUsername($username);
        if (empty($user)) {
            $this->error = '用户名不存在';
            $tag = false;
        } else if ($user['status'] < 1) {
            $this->error = '用户已被禁止';
            $tag = false;
        } else if ($user['domain'] != 'local') {
            $this->error = '非本地用户，暂不提供认证登录';
            $tag = false;
        }
        
        if (Zeed_Encrypt::encode('Md5Md5', $password, $user['salt']) != $user['password']) {
            $this->error = '用户名或密码不正确';
            $tag = false;
        }
        
        if (! $tag) {
            return $tag;
        }
        
        $user_group = UserGroupModel::instance()->fetchByPK($user['userid']);
        
        /* 处理头像 */
        if ($user['avatar']) {
            $user['avatar'] = Support_Image_Url::getImageUrl($user['avatar']);
        } else {
            if ($user['gender'] == 2) {
                $user['avatar'] = '/static/panel/img/avatar_default_female.jpg';
            } else {
                $user['avatar'] = '/static/panel/img/avatar_default.jpg';
            }
        }
        
        $userBasic = array('userid' => $user['userid'], 'username' => $user['username'], 'fullname' => $user['fullname'], 
                'avatar' => $user['avatar'], 'domain' => $user['domain'], 'groupid' => $user_group[0]['groupid']);
        Com_Admin_Authorization::logUserIn($userBasic);
        
        return $tag;
    }
    
    /**
     * 登出
     */
    public function out()
    {
        Zeed_Session::unsetSession('adminuser');
        // 登出成功
        header('Location: ' . $this->getContinue());
        exit;
    }
    
    /**
     * 获取后续跳转地址
     * 
     * @param string $default
     */
    private function getContinue($default = '/admin')
    {
        $continue = $this->input->get('continue');
        if (empty($continue)) {
            $continue = $this->input->post('continue');
        }
        if (empty($continue)) {
            $continue = $default;
        }

        return $continue;
    }
}

// End ^ LF ^ encoding
