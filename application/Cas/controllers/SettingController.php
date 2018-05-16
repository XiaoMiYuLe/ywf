<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SettingController extends CasAbstract {

    /**
     * 设置首页
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');

        /*
         * 需要接口
         * Api_Cas_GetUserInfo
         */

        //ajax 请求 执行此处
        if ($this->input->isAJAX()) {
            //参数
            $params = array(
                'userid' => $_SESSION['userid'], //用户id
            );

            $result = Api_Cas_GetUserInfo::run($params);

            if ($result['status'] == 0) {
                unset($result['data']);
            } else {
                unset($result['data']);
            }

            return $result;
        }

        /* 直接请求 */
        $params = array(
            'userid' => $_SESSION['userid'], //用户id
        );

        $result = Api_Cas_GetUserInfo::run($params);

        $data['userInfo'] = $result['data'];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /* 修改联系方式 */

    public function contact() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //ajax 请求 执行此处
        if ($this->input->isAJAX()) {
            //参数
            $params = array(
                'userid' => $_SESSION['userid'], //用户id
                'address' => $this->input->post('address'), //详细地址
                'zip_code' => $this->input->post('zip_code'), //邮编
                'contacts_person' => $this->input->post('contacts_person'), //联系人
                'contacts_phone' => $this->input->post('contacts_phone'), //联系人电话
            );

            $result = Api_Cas_UpdateInfo::run($params);

            // 安全过滤
            if ($result['status'] == 0) {
                //写入session
                $_SESSION['contact']['address'] = $result['data']['address'];
                $_SESSION['contact']['zip_code'] = $result['data']['zip_code'];
                $_SESSION['contact']['contacts_person'] = $result['data']['contacts_person'];
                $_SESSION['contact']['contacts_phone'] = $result['data']['contacts_phone'];

                unset($result['data']);
            } else {
                unset($result['data']);
            }
            return $result;
        }

        // 数据放入页面
        $data['contact'] = $_SESSION['contact'] ? $_SESSION['contact'] : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.contact');
        return self::RS_SUCCESS;
    }

    /**
     * 修改推荐人
     */
    public function recommender() {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isAJAX()) {
            // 准备参数
            $params = array(
                'userid' => $_SESSION['userid'], //当前登录用户
                'recommender' => $this->input->post('recommender'), //推荐人手机号
            );

            // 请求接口
            $result = Api_Cas_UpdateRecommender::run($params);

            if ($result['status'] == 0) {
                unset($result['data']);
            }

            return $result;
        }

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.recommender');
        return self::RS_SUCCESS;
    }

    /**
     * 二维码页面
     */
    public function qrCode() {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isAJAX()) {
            // 参数
            $params = array(
                'userid' => $_SESSION['userid'],
            );

            //请求接口 
            $result = Api_Cas_GetErCode::run($params);

            if ($result['status'] == 0) {
                unset($result['data']['userid']);
            }

            return $result;
        }

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.qrcode');
        return self::RS_SUCCESS;
    }

    /**
     * 设置交易密码
     */
    public function tradePwd() {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isAJAX()) {
            //params
            //最好能够再次对传过来的数据进行验证 6位纯数字 
            $params = array(
                'userid' => $_SESSION['userid'],
                'pay_pwd' => (string) $this->input->post('pwd'),
                'repeatpay_pwd' => (string) $this->input->post('repwd'),
            );
            
            //request
            $result = Api_Cas_SetPaypwd::run($params);
            
            if ($result['status'] == 0) {
                unset($result['data']);
            }
            
            return $result;
        }

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.tradepwd');
        return self::RS_SUCCESS;
    }

}
