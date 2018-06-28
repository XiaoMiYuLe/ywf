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
class UserController extends CasAbstract {

    /**
     * 用户中心
     * @return type
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //参数
        $params = array(
            'userid' => $_SESSION['userid'],
        );

        $userInfo = Cas_Model_User::instance()->fetchByWhere("userid = {$_SESSION['userid']}", array('parent_id'));
        $parent_id = $userInfo[0]['parent_id'];
        if ($parent_id) {
            $userInfo = Cas_Model_User::instance()->fetchByWhere("userid = {$parent_id}", array('username'));
            $username = $userInfo[0]['username'];
        }

        //用户加入经纪人
        $user = Api_Cas_ConfirmEcoman::run($params);
        //资金流水接口
        $record = Api_Cas_GetRecordLog::run($params);

        // 获取用户资金页面信息
        $money = Api_Cas_GetUserMoneyDetail::run($params);
        $money1 = Api_Cas_GetMytotalassets::run($params);
        if ($money && $money1) {
            $arr1 = $money['data'] ? $money['data'] : array();
            $arr2 = $money1['data'] ? $money1['data'] : array();

            $arr_merage = array_merge($arr1, $arr2) ? array_merge($arr1, $arr2) : array();
        }
        var_dump($arr_merage);
      
        $data['username'] = $username ? $username : '';
        $data['invitaiton'] = $user['data']['is_invitaiton'] ? $user['data']['is_invitaiton'] : array();
        $data['is_ecoman'] = $user['data']['is_ecoman'] ? $user['data']['is_ecoman'] : array();
        $data['money'] = $arr_merage;
        $data['record'] = $record['data'] ? $record['data'] : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'user.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /*
     * 用户收益记录
     */

    public function income() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'user.income');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /* 获取用户收益 */

    public function getincome() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //params
        $params = array(
            'p' => ($this->input->query('pageIndex')) + 1, //当前第几页
            'psize' => $this->input->query('pageSize'), //每页显示数
            'userid' => $_SESSION['userid'],
        );

        //request
        $request = Api_Cas_GetEarnings::run($params);

        //这里的content里面的数据比价特殊，需要纠正
        // filter
        $request = $request['data'] ? $request['data'] : array();
        return $request;
        return self::RS_SUCCESS;
    }

    /* 获取用户资金流水详情 */
    public function getRecordLog() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //参数
        $params = array(
            'status' => (int) $this->input->query('status',3),      //状态 记录类型：1：充值 2：提现 3：购买理财产品4：理财产品收益 5：佣金
            'start_time' => $this->input->query('start_time'),//开始时间
            'end_time' => $this->input->query('end_time'),    //结束时间
            'p' => ($this->input->query('pageIndex')) + 1,  //当前第几页
            'psize' => $this->input->query('pageSize'),     //每页显示数
            'userid' => $_SESSION['userid'], //用户userid
        );

        //请求参数
        $result = Api_Cas_GetRecordLog::run($params);
        
        return $result['data'];
        return self::RS_SUCCESS;
    }

    /**
     * broker 经纪人中心
     */
    public function broker() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //Api_Cas_GetAgentInfo cas_user cas_user_brokerage
        $params = array(
            'userid' => $_SESSION['userid'],
        );
        $broker = Api_Cas_GetAgentInfo::run($params);

        $data['broker'] = $broker['data'] ? $broker['data'] : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'user.broker');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 客户交易
     */
    public function customRecord() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //Api_Cas_GetAgentInfo cas_user cas_user_brokerage
        $params = array(
            'userid' => $_SESSION['userid'],
        );
        $broker = Api_Cas_GetAgentInfo::run($params);

        $data['broker'] = $broker['data'] ? $broker['data'] : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'user.customrecord');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 我的客户
     */
    public function myClient() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //Api_Cas_GetAgentInfo cas_user cas_user_brokerage
        $params = array(
            'userid' => $_SESSION['userid'],
        );
        $broker = Api_Cas_GetAgentInfo::run($params);

        $data['broker'] = $broker['data'] ? $broker['data'] : array();
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'user.myclient');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 我的佣金列表
     */
    public function brokerList() {
        $this->addResult(self::RS_SUCCESS, 'json');

        // params
        $params = array(
            'p' => ($this->input->query('pageIndex')) + 1, //当前第几页
            'psize' => $this->input->query('pageSize'), //每页显示数
            'userid' => $_SESSION['userid'], //用户userid
            'brokerage_status' => $this->input->query('brokerage_status', 1), //佣金状态 1-已结 2-未结
        );

        //request
        $result = Api_Cas_GetUserBrokerage::run($params);
        // filter
        $result = $result['data'] ? $result['data'] : array();

        //se
        unset($result['userid']);
        return $result;
        return self::RS_SUCCESS;
    }

    /**
     * 客户交易
     */
    public function getCustomRecord() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //$params
        $params = array(
            'p' => ($this->input->query('pageIndex')) + 1, //当前第几页
            'psize' => $this->input->query('pageSize'), //每页显示数
            'userid' => $_SESSION['userid'], //用户userid
            'tag' => $this->input->query('tag', 1), //tag 1-计息中 2-已结息
        );

        //request
        $result = Api_Cas_GetCustomReCord::run($params);

        // filter
        $result = $result['data'] ? $result['data'] : array();

        //se
        unset($result['userid']);
        return $result;
        return self::RS_SUCCESS;
    }

    /**
     * 客户列表
     */
    public function getClientList() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //$params
        $params = array(
            'p' => ($this->input->query('pageIndex')) + 1, //当前第几页
            'psize' => $this->input->query('pageSize'), //每页显示数
            'userid' => $_SESSION['userid'], //用户userid
            'status' => $this->input->query('status', 0), //status 1-已加入 2-未加入
        );

        //request
        $result = Api_Cas_GetClientList::run($params);

        // filter
        $result = $result['data'] ? $result['data'] : array();

        //se
        unset($result['userid']);
        return $result;
        return self::RS_SUCCESS;
    }

    /**
     * 邀请
     */
    public function invitation() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //$params
        $params = array(
            'userid' => $_SESSION['userid'], //邀请人
            'to_userid' => $this->input->post('user'), //被邀请人
        );

        //request
        $request = Api_Cas_Invitation::run($params);

        if ($request['status'] == 0) {
            unset($request['data']);
        }

        return $request;
        return self::RS_SUCCESS;
    }

    /**
     * 优惠券
     */
    public function coupon() {
        $this->addResult(self::RS_SUCCESS, 'json');


        $this->addResult(self::RS_SUCCESS, 'php', 'user.coupon');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /* 获取代金券列表 */

    public function couponList() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //$params
        $params = array(
            'p' => ($this->input->query('pageIndex')) + 1, //当前第几页
            'psize' => $this->input->query('pageSize'), //每页显示数
            'userid' => $_SESSION['userid'],
            'voucher_status' => $this->input->query('status', 1), //代金券状态 1-未使用 2-已使用 3-已过期
        );


        //request
        $result = Api_Two_GetUserVoucher::run($params);

        // filter
        $result = $result['data'] ? $result['data'] : array();

        //se
        unset($result['userid']);
        return $result;
        return self::RS_SUCCESS;
    }

    /**
     * 经理体验金使用
     */
    public function managerUse() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //params
        $params = array(
            'userid' => $_SESSION['userid'],
            'voucher' => $this->input->post('voucher'), //id
        );

        //request
        $request = Api_Two_UseExperienceByManager::run($params);

        unset($request['data']);
        return $request;
        return self::RS_SUCCESS;
    }

    /**
     * 获取推广金产品信息
     */
    public function getTuiInfo() {
        $this->addResult(self::RS_SUCCESS, 'json');

        //request
        $request = Api_Two_GetExperienceGood::run($params);


        $request['data'] = $request['data'][0] ? $request['data'][0] : array();
        return $request;
        return self::RS_SUCCESS;
    }

}

// End ^ LF ^ encoding
