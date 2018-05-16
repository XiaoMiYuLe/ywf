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
class SignController extends Zeed_Controller_Action {

    public $captchaId;
    public $error;
    public $continue;

    //登录
    public function index() {
        Zeed_Util_Redirector::factory('header', '/cas/sign/in', 0)->output();
    }

    //登录
    public function in() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $this->continue = $this->getContinue('/index/index');
        if (Cas_Authorization::getLoggedInUserid() > 0) {
            //已登录
            header('Location: ' . $this->continue);
            exit;
        }

        /**
         *  调用登录接口
         */
        if ($this->input->isAJAX()) {
            $phone = $this->input->post('phone');
            $password = $this->input->post('password');
            $code = $this->input->post('code');

            /* 验证码验证 */
            if (!Zeed_Captcha_Image::isValid('', $code)) {
                $d['status'] = 1;
                $d['error'] = '验证码填写错误';
                return $d;
            }

            $params = array(
                'phone' => $phone,
                'password' => $password,
            );

            /* 取数据 */
            $result = Api_Cas_Login::run($params);

            if ($result['status'] == 0) {
                /* 写入session */
                $_SESSION['userid'] = $result['data'][0]['userid'];
                $_SESSION['username'] = $result['data'][0]['username'];
                $_SESSION['phone'] = $result['data'][0]['phone'];
                unset($result['data']);
            }

            return $result;
        }

        $this->addResult(self::RS_SUCCESS, 'php', 'sign.in.php');
        return self::RS_SUCCESS;
    }

    /**
     * 登录
     */
    public function login() {
        if (!$this->input->isAJAX()) {
            return $this->in();
        }

        $rd = array('status' => 1, 'data' => null, 'error' => null);
        $rs = $this->loginUser();
        if ($rs) {
            //登录成功
            $rd['status'] = 0;
            $rd['data'] = $this->getContinue();
        } else {
            $rd['error'] = $this->error;
            $_SESSION['signinerrorcount'] ++;
        }
        exit(json_encode($rd));
    }

    /**
     * 用户详细资料缓存
     * @var array
     */
    protected $_userDetail;

    /**
     * 获取用户详细资料
     * @param integer $userid
     * @return array|null
     */
    protected function _getUserDetail($userid) {
        if (isset($this->_userDetail[$userid])) {
            $this->_userDetail[$userid] = Cas_Model_User_Detail::instance()->getUserDetailByUserid($userid);
        }

        return $this->_userDetail[$userid];
    }

    /**
     * 登录用户，记录用户SESSION
     */
    private function loginUser() {
        $username = trim($this->input->query('username'));
        if (empty($username)) {
            $this->error = '用户名密码不得为空';
            return false;
        }

        $timenow = DATENOW;
        $ip = Zeed_Util::clientIP();

        /* 检查同IP出错次数记录 */
        $errorTryCount = Cas_Model_Login_Error::instance()->getRecentCountByIpAndUsername($ip, $username);
        if ($errorTryCount > 10) {
            $this->error = '已尝试太多次，请过一会儿再试';
            return false;
        } else if ($errorTryCount >= 3) {
            $_SESSION['signinerrorcount'] = max($_SESSION['signinerrorcount'], $errorTryCount);
        } else {
            // 检查出错次数记录
            $errorTryCount = Cas_Model_Login_Error::instance()->getRecentCountByIpAndUsername($ip, $username);
            if ($errorTryCount >= 3) {
                $_SESSION['signinerrorcount'] = max($_SESSION['signinerrorcount'], $errorTryCount);
            }
        }

        /* 检查验证码 */
//         if ($_SESSION['signinerrorcount'] >= 3) {
//             if (! Zeed_Captcha_Image::isValid($this->input->query('captchaId'), $this->input->query('captcha'))) {
//                 $this->error = '验证码不正确';
//                 return false;
//             }
//         }

        /* 检查用户是否存在 */
        $username_type = null;
        if (Cas_Validator::email($username)) {
            $username_type = 'email';
        } else if (true === Cas_Validator::phone($username)) {
            $username_type = 'phone';
        } else if (true === Cas_Validator::idcard($username)) {
            $username_type = 'idcard';
        }
        if (!$userExists = Cas_Model_User::instance()->fetchByUsernameType($username, $username_type)) {
            $this->error = '该用户不存在';
            return false;
        }

        /* 检查用户是否被封停 */
//         if($userExists['status'] <= 0 && (empty($userExists['ban_etime']) || $userExists['ban_etime'] > $timenow) ) {
//         	$this->error = '账号已被封停';
//             return false;
//         }

        /* 检查用户密码 */
        if (!Cas_Model_User::instance()->checkPassword($userExists['userid'], $this->input->query('password'), $this->input->query('encrypted'))) {
            $loginErrorSet = array('sessionid' => session_id(), 'username' => $userExists['username'],
                'ctime' => $timenow, 'ip' => $ip);
            Cas_Model_Login_Error::instance()->addForEntity($loginErrorSet);
            $this->error = '用户名或密码不正确';
            return false;
        }

        Cas_Model_User::instance()->updateLastLogin($userExists['userid'], $timenow, $ip);

        /* 密码正确，记录用户SESSION */
        $lastLogin = array('last_login_time' => $timenow, 'last_login_ip' => $ip);
        Cas_Authorization::logInUser($userExists, $lastLogin);
        $sessionid = Zeed_Session::getID();
        $logSet = array('ip' => $ip, 'userid' => $userExists['userid'], 'username' => $userExists['username'] ? $userExists['username'] : $username,
            'sessionid' => $sessionid, 'ua' => $_SERVER['HTTP_USER_AGENT'], 'ctime' => $timenow);
        Cas_Model_Login_Log::instance()->addForEntity($logSet);

        return true;
    }

    public $username;

    //注册
    public function up() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $this->continue = $this->getContinue('/index/index');
        if (Cas_Authorization::getLoggedInUserid() > 0) {
            //已登录
            header('Location: ' . $this->continue);
            exit;
        }

        if ($this->input->isPost()) {
            $rs = $this->regUser();
            if ($rs) {
                $sessionid = Zeed_Session::getID();
                $ip = Zeed_Util::clientIP();

                return self::RS_SUCCESS;
            } else {
                $data['error'] = $this->error;
                $data['data'] = null;
                $data['status'] = 1;
                $this->setData($data);
                return self::RS_SUCCESS;
            }
        }
        $this->addResult(self::RS_SUCCESS, 'php', 'sign.up.php');
        return self::RS_SUCCESS;
    }

    //发送验证码 
    public function sendcode() {
        if ($this->input->isAJAX()) {
            $arr = array();
            $arr['send_to'] = $this->input->post('send_to');
            $arr['action'] = '1';

            $result = Api_Cas_SendCode::run($arr);

            $data['status'] = $result['status'];
            $data['data'] = '';
            $data['error'] = $result['error'];

            return $data;
        }
    }

    /**
     * 注册
     * @return type
     */
    public function signup() {
        if ($this->input->isAJAX()) {
            $arr = array();
            $arr['phone'] = $this->input->post('phone');
            $arr['password'] = $this->input->post('pwd');
            $arr['recommender'] = $this->input->post('recommender');
            $arr['repwd'] = $this->input->post('repwd');
            $code = $this->input->post('code','');
            
            //图形验证码
//            $imgCode = $this->input->post('imgcode');
            
            /* 验证码验证 */
//            if (!Zeed_Captcha_Image::isValid('', $imgCode)) {
//                $d['status'] = 1;
//                $d['error'] = '图形验证码填写错误';
//                return $d;
//            }
            
            //验证码校验
            $where = " send_to = '{$arr['phone']}' AND action = 1 AND code = '{$code}'";
            $order = ' ctime desc';
            $code_arr = Cas_Model_Code::instance()->fetchByWhere($where, $order, 1);

            if (!empty($code_arr)) {
                if ($code_arr[0]['code'] != $code) {
                    $res['status'] = 1;
                    $res['error'] = "短信验证码不正确，请检查输入或重新获取";
                    return $res;
                }

                if ($code_arr[0]['code'] == $code) {
                    if (strtotime("-1800 seconds") > strtotime($code_arr[0]['ctime'])) {
                        $res['status'] = 1;
                        $res['error'] = "验证信息已失效，请重新发起。";
                        return $res;
                    }
                }
            } else {
                $res['status'] = 1;
                $res['error'] = "短信验证码不正确，请检查输入或重新获取";
                return $res;
            }
            
            /* 密码格式 */
            $str= "/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{6,16}$/";
            if (!preg_match($str, $arr['repwd'])) {
                $res['status'] = 1;
                $res['error'] = "密码必须为6-16位，同时包含数字和字母两种";
                return $res;
            }
            
            if($arr['password'] !=$arr['repwd']){
                $res['status'] = 1;
                $res['error'] = "两次密码输入不一致";
                return $res;
            }

            $result = Api_Cas_Signup::run($arr);

            if ($result['status'] == 0) {
                $_SESSION['userid'] = $result['data']['userid'];
                $_SESSION['username'] = $result['data']['username'];
                $_SESSION['phone'] = $result['data']['phone'];
                
                //注册成功后 ，修改注册方式为 1 0-手机 1-web
                $info = Cas_Model_User::instance()->update(array('reg_type'=>1),"phone = {$result['data']['phone']}");
                
            }

            $data['status'] = $result['status'];
            $data['data']['userid'] = '';
            $data['error'] = $result['error'];
            return $data;
        }
    }
    
    /**
     * 检查注册的图形验证码是否正确
     */
    public function checkImg(){
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $code = $this->input->post('imgcode','');
       
        /* 验证码验证 */
        if (!Zeed_Captcha_Image::isValid('', $code)) {
            $d['status'] = 1;
            $d['error'] = '请输入正确的图形验证码';
            return $d;
        }else {
            $d['status'] = 0;
            $d['error'] = '';
            return $d;
        }
        
        return self::RS_SUCCESS;
    }

    /**
     * 验证用户名是否可注册接口
     */
    public function isUsernameAvailable() {
        $username = trim($this->input->post('username'));
        if (!Cas_Validator::username($username)) {
            exit('false');
        }
        $userExists = Cas_Model_User::instance()->isUserExistent($username);
        if ($userExists) {
            exit('false');
        }
        exit('true');
    }

    /**
     * 验证手机号码是否可注册接口
     */
    public function isPhoneAvailable() {
        $phone = trim($this->input->post('phone'));
        $userExists = Cas_Model_User::instance()->isPhoneExistent($phone);
        if ($userExists) {
            exit('false');
        }
        exit('true');
    }

    /**
     * 验证身份证是否可注册接口
     */
    public function isEmailAvailable() {
        $email = trim($this->input->post('email'));
        $userExists = Cas_Model_User::instance()->isEmailExistent($email);
        exit('true');
    }

    /**
     * Register user
     */
    protected function regUser() {
        $set = $this->input->post();

        if (!$this->validateReg()) {
            return false;
        }
        $password = Cas_Model_User::instance()->GetPassWord($set['password']);
        $set['password'] = $password['password'];
        $set['salt'] = $password['passwordSalt'];
        $set['encrypt'] = $password['passwordEncrypt'];
        $set['user_group_id'] = 1;
        $set['ctime'] = date('Y-m-d H:i:s');
        $res = Cas_Model_User::instance()->add($set);
        if (!$res) {
            $data['status'] = 1;
            $data['error'] = '注册失败';
            $this->setData($data);
            return self::RS_SUCCESS;
        }
        $user = $this->loginUser();
        if (!$user) {
            $data['status'] = 1;
            $data['error'] = '登录失败';
            $this->setData($data);
            return self::RS_SUCCESS;
        }
        $data['status'] = 0;
        $data['data'] = '/goods/index/index';
        $this->setData($data);
        return self::RS_SUCCESS;
    }

    /**
     * Validate user post data
     */
    protected function validateReg() {
        $username = trim($this->input->post('username'));
        if (!Cas_Validator::username($username)) {
            $this->error = '用户名无效';
            return false;
        }

        $userExists = Cas_Model_User::instance()->isUserExistent($username);
        if ($userExists) {
            $this->error = '用户已存在';
            return false;
        }

        $emailExists = Cas_Model_User::instance()->isEmailExistent($this->input->post('email'));
        if ($emailExists) {
            $this->error = '邮箱已存在';
            return false;
        }

        $telphpneExists = Cas_Model_User::instance()->isTelphoneExistent($this->input->post('telphone'));
        if ($telphpneExists) {
            $this->error = '手机已存在';
            return false;
        }

        $nicknameExists = Cas_Model_User::instance()->isNickNameExistent($this->input->post('nickname'));
        if ($nicknameExists) {
            $this->error = '昵称已存在';
            return false;
        }

        if (($pwdVaildResult = Cas_Validator::password($this->input->post('password'))) &&
                is_array($pwdVaildResult)
        ) {
            $this->error = array_pop($pwdVaildResult);
            return false;
        }

        if ($this->input->post('password') != $this->input->post('repassword')) {
            $this->error = '两次输入密码不一致';
            return false;
        }

        $email = trim($this->input->post('email'));
        if (!Cas_Validator::email($email)) {
            $this->error = '无效的电子邮箱地址';
            return false;
        }

        $realname = trim($this->input->post('realname'));

        if (($validatorResult = Cas_Validator::realname($realname)) && is_array($validatorResult)) {
            $this->error = array_pop($validatorResult);
            return false;
        }
        return true;
    }

    //登出
    public function out() {
        //var_dump($_SESSION);
        //$session_unset = array('userid', 'username', 'nickname', 'lastLogin', 'lastLoginIp', 'third_username', 'third_domain');
        
        $arrs = array(
                'login_time' => null
        );       
        Cas_Model_User::instance()->update($arrs, "userid = {$_SESSION['userid']}");
        unset($_SESSION['userid']);
        unset($_SESSION['username']);
        unset($_SESSION['phone']);
        unset($_SESSION['hidephone']);
        unset($_SESSION['is_invitaiton']);
        unset($_SESSION['is_ecoman']);
        //Zeed_Session::unsetSession($session_unset); 这段代码无法执行，所以使用php原生语法删除session
        //var_dump($_SESSION);exit;
        //登出成功
        header('Location: ' . '/index/index');
        exit;
    }

    /**
     * 获取后续跳转地址FOR登入登出
     */
    private function getContinue($default = '/') {
        $continue = $this->input->get('continue');
        if (empty($continue)) {
            $continue = $this->input->post('continue');
        }
        if (empty($continue)) {
            $continue = $default;
        }

        //@todo 获取REFER并判断处理

        return $continue;
    }

}
