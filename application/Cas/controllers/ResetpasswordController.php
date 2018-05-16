<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ResetpasswordController extends CasAbstract {

    /**
     * 修改登录密码
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');

        // 数据类型判断
        if ($this->input->isAJAX()) {
            // 准备参数
            $params = array(
                'userid' => $_SESSION['userid'],
                'oldpassword' => $this->input->post('oldpassword'),
                'newpassword' => $this->input->post('newpassword'),
            );

            // 请求接口
            $result = Api_Cas_ResetPassword::run($params);

            /* 增强安全性 */
            if ($result['status'] == 0) {
                unset($result['data']);
            } else {
                unset($result['data']);
            }

            return $result;
        }

        // 多类型返数据 加载页面
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'resetpassword.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 修改交易密码
     */
    public function resetPaypwd() {
        $this->addResult(self::RS_SUCCESS, 'json');

        // 数据类型判断
        if ($this->input->isAJAX()) {
            //准备参数
            $params = array(
                'userid' => $_SESSION['userid'],
                'oldpay_pwd' => (string) $this->input->post('oldpassword'),
                'pay_pwd' => (string) $this->input->post('newpassword'),
                'repeatpay_pwd' => (string) $this->input->post('newpasswordOne'),
            );

            // 请求接口
            $result = Api_Cas_ResetPaypwd::run($params);

            if ($result['status'] == 0) {
                unset($result['data']);
            } else {
                unset($result['data']);
            }

            return $result;
        }

        // 多类型返数据 加载页面
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'resetpassword.resetpaypwd');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 找回交易密码
     */
    public function forgotPaypwd() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //isAJAX()
        if ($this->input->isAJAX()) {
            // 准备参数
            $params = array(
                'userid' => $_SESSION['userid'], //userid
                'pay_pwd' => (string) $this->input->post('pay_pwd'), // 新的交易密码
                'repeatpay_pwd' =>(string) $this->input->post('repeatpay_pwd'), //交易密码确认
                'test_idcard' => (string) $this->input->post('test_idcard'), // 用户身份证号
                'code' => $this->input->post('code'), //验证码
            ); 

            // 请求忘记交易密码接口 Api_Cas_ForgotPaypwd
            $result = Api_Cas_ForgotPaypwd::run($params);

            if ($result['status'] == 0) {
                unset($result['data']);
            }

            return $result;
        }

        $data['phone'] = $_SESSION['phone'];
        // 多类型返数据 加载页面
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'resetpassword.forgotpaypwd');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 忘记交易密码 发送验证码
     */
    public function sendCode() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $param = array(
            'send_to' => $_SESSION['phone'],
            'action' => 3, // 3 重置交易密码
        );
        //重置交易密码 请求发送验证码接口
        $res = Api_Cas_SendCode::run($param);

        // 发送成功
        if ($res['status'] == 0) {
            unset($res['data']);
        } else {// 失败
            return $res;
        }
        
        return self::RS_SUCCESS;
    }
}
