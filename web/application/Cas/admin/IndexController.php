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
class IndexController extends CasAdminAbstract
{
    public $perpage = 15;

    /**
     * 会员列表管理
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        /* 接收参数 */
        $key = trim($this->input->get('key'));
        $pageIndex = $this->input->get('pageIndex', 0);
        $pageSize = $this->input->get('pageSize', 15);
        $orderName = $this->input->get('ordername', "");
        $orderBy = $this->input->get('orderBy', "");
        $status = $this->input->get('status', -1);

        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $pageIndex * $pageSize;

            $where = array();

            if ($status != -1) {
                $where[] = "status=" . $status;
            } 

            if ($key) {
                $where[] = "(username LIKE '%" . $key . "%' OR idcard LIKE '%" . $key . "%' OR phone LIKE '%" . $key . "%')";
            }

            $order = 'ctime DESC';
            if ($orderName) {
                $order = $orderName . " " . $orderBy;
            }

            $field = array('userid','ctime', 'username', 'phone', 'idcard', 'asset', 'is_ecoman', 'status');
            $users = Cas_Model_User::instance()->fetchByWhere($where, $order, $pageSize, $offset, $field);
            if(!empty($users)){
                foreach ($users as &$v){
                    if(empty($v['username'])){
                        $v['username']='';
                    }
                    
                    if(empty($v['idcard'])){
                        $v['idcard']='';
                    }
                }
            }
            $data['count'] = Cas_Model_User::instance()->getCount($where);

            $data['users'] = $users ? $users : array();
        }

        $data['orderName'] = $orderName;
        $data['orderBy'] = $orderBy;

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }

        $userid = (int)$this->input->query('userid');

        $user_info = Cas_Model_User::instance()->getUserByUserid($userid);
        if (empty($user_info)) {
            $this->setStatus(1);
            $this->setError('查无此会员');
            return self::RS_SUCCESS;
        }

        /* 获取会员其他详细信息 */
        $user_detail = Cas_Model_User_Detail::instance()->fetchByPK($userid);
        if (! empty($user_detail)) {
            $user_info['birthday'] = substr($user_detail[0]['birthday'], 0, 10);
            $user_info['region_id'] = $user_detail[0]['region_id'];
            $user_info['address'] = $user_detail[0]['address'];
        }

        /* 处理所在地区信息 */
        $region = Trend_Model_Region::instance()->fetchByPK($user_info['region_id']);
        if (! empty($region)) {
        }

        /* 获取用户等级信息 */

        $data['user_info'] = $user_info;

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 编辑 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $password = $set['data']['password'];

                unset($set['data']['password']);

                /* 更新用户信息 */
                Cas_Model_User::instance()->updateForEntity($set['data'], $set['data']['userid']);

                /* 更新用户密码 */
                if ($password) {
                    Cas_Model_User::instance()->modifyPassword($set['data']['userid'], $password);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑会员信息失败。错误信息： : ' . $e->getMessage());
                return false;
            }
            return true;
        }

        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 改变用户激活状态
     */
    public function changeStatus()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        $userid = $this->input->post('userid');
        $status = $this->input->post('status');
        if (! $userid) {
            $this->setError('用户Id不存在');
            $this->setStatus(1);
            return parent::multipleResult(self::RS_SUCCESS);
        }

        Cas_Model_User::instance()->updateForEntity(array("status" => $status), $userid);
        $data['userid'] = $userid;

        $this->setData('data', $data);
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 保存－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);

        $res['data'] = array(
            'userid' => $this->input->post('userid', 0),
            'password' => $this->input->post('password'),
            'realname' => $this->input->post('realname'),
            'gender' => $this->input->post('gender'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email'),
            'mtime' => date(DATETIME_FORMAT)
        );

        /* 校验手机的唯一性 */
        if ($res['data']['phone']) {
            $where_phone['phone'] = $res['data']['phone'];
            if ($res['data']['userid']) {
                $where_phone[] = "userid != {$res['data']['userid']}";
            }
            $phone_existent = Cas_Model_User::instance()->fetchByWhere($where_phone);
            if (! empty($phone_existent)) {
                $res['status'] = 1;
                $res['error'] = '该手机号码已被其他用户占用，请重新填写';
                return $res;
            }
        }

        /* 校验邮箱的唯一性 */
        if ($res['data']['email']) {
            $where_email['email'] = $res['data']['email'];
            if ($res['data']['userid']) {
                $where_email[] = "userid != {$res['data']['userid']}";
            }
            $email_existent = Cas_Model_User::instance()->fetchByWhere($where_email);
            if (! empty($email_existent)) {
                $res['status'] = 1;
                $res['error'] = '该电子邮箱已被其他用户占用，请重新填写';
                return $res;
            }
        }

        return $res;
    }

    /**
     * 会员详情
     */
    public function detail()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        $userid = (int)$this->input->get('userid');
        try {
            $user = Cas_Model_User::instance()->getUserByUserid($userid);
            if (empty($user)) {
                throw new Zeed_Exception('查无此用户');
            }
            //代金券
            $user_voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("userid = {$userid}");
            if(!empty($user_voucher)){
                $user['voucher_status'] = $user_voucher[0]['voucher_status'];
                $user['voucher_id'] = $user_voucher[0]['voucher_id'];
                switch ($user['voucher_status']){
                    case 1:
                        $user['voucher_status'] = '未使用';
                        break;
                    case 2:
                        $user['voucher_status'] = '已使用';
                        break;
                    case 3:
                        $user['voucher_status'] = '已过期';
                        break;
                }
            }else{
                $user['voucher_status'] = '无';
                $user['voucher_id'] = '无';
            }
          
            //推荐人
            if(!empty($user['parent_id'])){
                $user_recommender = Cas_Model_User::instance()->fetchByWhere("userid = {$user['parent_id']}");
                if($user_recommender[0]['username']){
                    $user['recommender']=$user_recommender[0]['username'];
                }elseif ($user_recommender[0]['phone']){
                    $user['recommender']=$user_recommender[0]['phone'];
                }
            }else{
                $user['recommender']="无";
            }
            //用户银行卡列表
            $bank_list = Cas_Model_Bank::instance()->fetchByWhere("userid = '{$user['userid']}' and is_use=1");
            $data['user'] = $user;
            $data['bank'] = $bank_list;
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('Fetch failed : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.detail');
        return parent::multipleResult(self::RS_SUCCESS);

    }

    /**
     * 重置会员密码（暂不启用）
     */
    public function resetPassword()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }

        $userid = (int)$this->input->post('userid');
        $password = trim($this->input->post('password'));
        $repassword = trim($this->input->post('repassword'));

        if ($repassword && strcmp($password, $repassword) !== 0) {
            $this->setStatus(1);
            $this->setError('您输入的确认密码与密码不一致，请重新输入');
            return self::RS_SUCCESS;
        }

        try {
            Cas_Model_User::instance()->modifyPassword($userid, $password);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('修改会员密码失败。错误信息 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }

        $this->setError('修改成功');
        return self::RS_SUCCESS;
    }

    /**
     * 设为失效
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        $bank_id= (int)$this->input->post('bank_id');
        try {
            Cas_Model_Bank::instance()->updateForEntity(array('is_del' => 1), $bank_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除用户失败。错误信息 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }

        $this->setError('删除成功');
        return self::RS_SUCCESS; 
    }
}

// End ^ LF ^ encoding
