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
class InvitationController extends CasAdminAbstract
{
    public $perpage = 15;

    /**
     * 审核列表管理
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        /* 接收参数 */
        $key = trim($this->input->get('key'));
        $is_invitaiton = $this->input->get('pageIndex', 0);
        $pageSize = $this->input->get('pageSize', 15);
        $orderName = $this->input->get('ordername', "");
        $orderBy = $this->input->get('orderBy', "");
        $status = $this->input->get('status', -1);
        $is_invitaiton = $this->input->get('is_invitaiton', -1);
        

        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $pageIndex * $pageSize;

            $where = array();
           
            if($is_invitaiton<0){
                $where[] = "is_invitaiton in (2,3,4)"  ;
            }else{
                $where['is_invitaiton'] = $is_invitaiton;
            }

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

            $field = array('userid','ctime', 'username', 'phone', 'idcard', 'asset', 'is_ecoman', 'status','is_invitaiton','avatar','audit_time');
            $users = Cas_Model_User::instance()->fetchByWhere($where, $order, $pageSize, $offset, $field);
            if(!empty($users)){
                foreach ($users as &$v){
                    $brokerage = Cas_Model_User_Brokerage::instance()->fetchByWhere("userid = '{$v['userid']}' and brokerage_status=1 OR brokerage_status=2");
                    if(!empty($brokerage)){
                        foreach ($brokerage as $vv){
                            $brokerage_money += $vv['expected_money'];
                        }
                    }
                  
                    $v['brokerage_money'] = $brokerage_money ?$brokerage_money:'';
                    if(empty($v['username'])){
                        $v['username']='';
                    }
                    
                    if(empty($v['idcard'])){
                        $v['idcard']='';
                    }
                    
                    //图片地址
                    if(!empty($v['avatar'])){
                        $v['avatar'] =  Support_Image_Url::getImageUrl($v['avatar']);
                    }else{
                        $v['avatar'] = '';
                    }
                }
            }
            $data['count'] = Cas_Model_User::instance()->getCount($where);

            $data['users'] = $users ? $users : array();
        }

        $data['orderName'] = $orderName;
        $data['orderBy'] = $orderBy;

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'invitation.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        $userid = (int)$this->input->query('userid', null);
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }

        $user = Cas_Model_User::instance()->fetchByPk($userid);
        
        if (null === $user || !$user) {
            $this->setStatus(1);
            $this->setError('用户不存在');
            return self::RS_SUCCESS;
        }

        $data = $user[0];

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'invitation.edit');
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

                /* 基础数据处理 */
                $userid = $set['data']['userid'];
                $where = "userid ={$userid}";
                $set['data']['audit_time'] = date("Y:m:d H:i:s");
                
                if($set['data']['is_invitaiton']==5){
                    $set['data']['is_ecoman'] =1;
                    Cas_Model_User::instance()->updateForEntity($set['data'], $userid);
                    
                    //发短信
                    $user = Cas_Model_User::instance()->fetchByPk($userid,array('phone'));
                    $content1 = "尊敬的客户：您加入财富经纪人的申请已审核通过，感谢您的使用。";
                    $gets = Sms_SendSms::testSingleMt1('86'.$user[0]['phone'], $content1);
                     
                    $data = array(
                        'type' => 'phone',
                        'action'=>'6',
                        'send_to'=>$user[0]['phone'],
                        'content'=>$content1,
                        'ctime'=>date(DATETIME_FORMAT),
                    );
                     
                    $ID = Cas_Model_Code::instance()->addForEntity($data);
                    
                }elseif($set['data']['is_invitaiton']==4 || $set['data']['is_invitaiton']==3){
                    Cas_Model_User::instance()->updateForEntity($set['data'], $userid);
                    //发短信
                    $user = Cas_Model_User::instance()->fetchByPk($userid,array('phone'));
                    $content1 = "尊敬的客户：您加入财富经纪人的申请未通过，请登录app查看原因，感谢您的使用。";
                    $gets = Sms_SendSms::testSingleMt1('86'.$user[0]['phone'], $content1);
                     
                    $data = array(
                        'type' => 'phone',
                        'action'=>'6',
                        'send_to'=>$user[0]['phone'],
                        'content'=>$content1,
                        'ctime'=>date(DATETIME_FORMAT),
                    );
                    $ID = Cas_Model_Code::instance()->addForEntity($data);
                }else{
                    Cas_Model_User::instance()->updateForEntity($set['data'], $userid);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑学历失败: ' . $e->getMessage());
                return false;
            }
            return true;
        }
        $this->setStatus($set['status']);
        $this->setError($set['error']);
    }

    /**
     * 保存分－校验
     */
    private function _validate()
    {
        $res = array(
            'status' => 0,
            'error' => null,
            'data' => null
        );

        $res['data'] = array(
            'userid' => (int)$this->input->query('userid'),
            'is_invitaiton' => (int)$this->input->post('is_invitaiton'),
            'remarks' =>(string)$this->input->post('remarks'),
        );
        
         if(iconv_strlen($res['data']['remarks'],'utf-8')>13){
            $res['status'] = 1;
            $res['error'] = '备注不超过13个字';
            return $res;
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
            $bank_list = Cas_Model_Bank::instance()->fetchByWhere("userid = '{$userid}' and is_use=1");
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
     * 审核通过
     */
    public function tongguo()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        $userid= (int)$this->input->post('userid');
        try {
            Cas_Model_User::instance()->updateForEntity(array('is_invitaiton' => 5,'is_ecoman'=>1), $userid);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('审核失败。错误信息 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }

        $this->setError('审核成功');
        return self::RS_SUCCESS; 
    }
    
    /**
     * 重新上传
     */
    public function shangchuang()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        $userid= (int)$this->input->post('userid');
        try {
            Cas_Model_User::instance()->updateForEntity(array('is_invitaiton' => 3,'mtime'=>date("Y:m:d H:i:s")), $userid);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('操作失败。错误信息 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setError('操作成功');
        return self::RS_SUCCESS;
    }
    
    /**
     * 审核未通过
     */
    public function weitongguo()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        $userid= (int)$this->input->post('userid');
        try {
            Cas_Model_User::instance()->updateForEntity(array('is_invitaiton' => 4,'mtime'=>date("Y:m:d H:i:s")), $userid);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('操作失败。错误信息 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setError('操作成功');
        return self::RS_SUCCESS;
    }
    
}

// End ^ LF ^ encoding
