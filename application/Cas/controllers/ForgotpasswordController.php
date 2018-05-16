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
 * @since      Jun 24, 2010
 * @version    SVN: $Id: ForgotPasswordController.php 11993 2011-10-14 06:02:26Z nroe $
 */

/**
 *
 *
 * @category   Zeed
 * @package    Zeed_CAS
 * @subpackage Security
 * @since      Jun 24, 2010
 */
class ForgotpasswordController extends Zeed_Controller_Action {

    public $formActionAddr = null;
    public $captchaId = null;
    public $error = null;

    /**
     * 使用安全问题重置通行证密码返回状态
     *
     * @var FQA_QA_AUTH_FAILED 安全问题验证失败
     */
    const FQA_SUCCESS = 0;
    const FQA_UNKNOWN_ERROR = 1;
    const FQA_QA_AUTH_FAILED = 2;
    const FQA_AUTH_FAILED = 3;

    /**
     * 使用邮箱重置通行证密码返回状态
     *
     * @var FMAIL_MAIL_INVAILED 重置通行证密码帐户邮箱未设置或未验证
     */
    const FMAIL_SUCCESS = 0;
    const FMAIL_UNKNOWN_ERROR = 1;
    const FMAIL_MAIL_INVAILED = 2;
    const FMAIL_USERNAME_NOT_EXIST = 3;

    /**
     * 重置通行证密码返回状态
     */
    const RP_SUCCESS = 0;
    const RP_UNKNOWN_ERROR = 1;
    const RP_CODE_INVAILED = 2;
    const CAPTCHA_SESSION_LIFETIME = 10;
    const CAPTCHA_TAG = 'Security.Captcha';
    const CAPTCHA_QA_TAG = 'ForgotPassword.Captcha.QA';
    const CAPTCHA_FP_TAG = 'ForgotPassword.Captcha.Forgot';
    const CAPTCHA_EB_TAG = 'ForgotPassword.Captcha.EmailBind';

    public $controllerName = '';

    protected function _init() {
        $this->controllerName = $this->_request->getControllerName();
    }

    /**
     * 重置通行证密码
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $this->addResult(self::RS_SUCCESS, 'php', 'forgotpassword.index');
        return self::RS_SUCCESS;
    }

    /**
     * 调用获取验证码接口 
     * 忘记密码找回 action 2
     */
    public function sendCode() {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isAJAX()) {
            /* 准备发送验证码参数 */
            $params = array();
            $params['send_to'] = $this->input->post('send_to');
            $params['action'] = '2';

            // 调用发送验证码接口
            $result = Api_Cas_SendCode::run($params);

            // unset 掉 'data'
            unset($result['data']);
            return $result;
        }

        return self::RS_SUCCESS;
    }

    /**
     * 调用找回密码下一步验证接口
     */
    public function NextPassword() {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isAJAX()) {
            // 参数
            $params = array(
                'phone' => $this->input->post('phone'),
                'code' => $this->input->post('code'),
            );

            /* 设置Cookie*/
            setcookie('phone',$params['phone']);
            setcookie('code',$params['code']);

             // 下一步密码接口
            $result = Api_Cas_NextPassword::run($params);
            return $result;
        }

        return self::RS_SUCCESS;
    }

    /**
     * 重置密码
     */
    public function resetPassword() {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isAJAX()) {
            // 参数
            $params = array(
                'phone' => $_COOKIE['phone'],
                'code' => $_COOKIE['code'],
                'newpassword' => $this->input->post('newpassword'),
            );

            // 下一步密码接口
            $result = Api_Cas_ForgotPassword::run($params);
            
            if ($result['status'] == 0) {
                unset($result['data']['newpassword']);

                // username userid phone 写入session
                $userInfo = Cas_Model_User::instance()->fetchByFV('phone', $result['data']['phone'],array('userid','phone','username'));
                $_SESSION['userid'] = $userInfo[0]['userid'];
                $_SESSION['username'] = $userInfo[0]['username'];
                $_SESSION['phone'] = $userInfo[0]['phone'];
            }
            
            return $result;
        }

        $this->addResult(self::RS_SUCCESS, 'php', 'forgotpassword.resetpassword');
        return self::RS_SUCCESS;
    }

    /**
     * 重置成功页面提示
     */
    public function forgotPrompt() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $this->addResult(self::RS_SUCCESS, 'php', 'forgotpassword.forgotprompt');
        return self::RS_SUCCESS;
    }

}

// End ^ Native EOL ^ encoding
