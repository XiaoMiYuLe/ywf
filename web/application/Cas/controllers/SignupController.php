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
class SignupController extends IndexAbstract
{
    /**
     * 注册页
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $user_code = trim($this->input->query('user_code',''));
        $data['user_code'] = $user_code;
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    /*
     * 发送验证码
     * */
    public function sendCode()
    {
          $this->addResult(self::RS_SUCCESS, 'json');
            
            $send_to = trim($this->input->query('phone',''));
            
            /*  接受发送手机号参数*/
            $res['data'] = array (
                'send_to' => $send_to,   //接收手机号
                'type' =>'phone',
                'action' => 1,   //1：注册 
                'code' => rand(100000,999999),
                'ctime' => date ( DATETIME_FORMAT ),
            );
            
            $user = Cas_Model_User::instance()->fetchByWhere("phone = '{$res['data']['send_to']}'");
    	    if ($user) {
    			$res['status'] = 1;
    			$res['error'] = '该手机号已被注册';
    			return $res;
    		} 
    		$content1 = "尊敬的用户，您的验证码为".$res['data']['code']."，本验证码30分钟内有效，感谢您的使用。";
    		$gets = Sms_SendSms::testSingleMt('86'.$res['data']['send_to'], $content1);
    		
    		$id = Cas_Model_Code::instance()->addForEntity($res['data']);
    		if (!$id) {
    		    throw new Zeed_Exception('发送失败');
    		}		
            return self::RS_SUCCESS;
    }
    
    /*
     * 注册用户
     * */
    public function add(){
        $this->addResult(self::RS_SUCCESS, 'json');
        //成功后跳转地址
        $url = Trend_Model_Version::instance()->fetchByWhere("id=1");
        $set = $this->_validate();
        if ($set['status'] == 0) {
            $userid  = Cas_Model_User::instance()->addForEntity($set['data']);
            //发送优惠券
            Cas_Model_User_Voucher::instance()->sendCoupon($userid);
            
        } 
        $set['guide_url'] = $url[0]['guide_url'];
        return $set;
    }
    /*数据验证*/
    private function _validate ()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        
        $phone = $this->input->post('phone');
        $code = $this->input->post('code');
        $pwd = $this->input->post('pwd');
        $repwd = $this->input->post('repwd');
        $recommender = $this->input->post('recommender');
        
        /* 判断手机格式 */
        $reg = "/^1[34578][0-9]{1}[0-9]{8}$/";
        if (!preg_match($reg, $phone)) {
            $res['status'] = 1;
            $res['error'] = "请填写正确的手机号码";
            return $res;
        } 
        
        //验证用户是否注册过
        $exist_phone = Cas_Model_User::instance()->fetchByWhere( "phone = '{$phone}'");
        if(!empty($exist_phone)){
            $res['status'] = 1;
            $res['error'] = "该用户已注册";
            return $res;
        }
        //验证码校验
        $where = " send_to = '{$phone}' AND action = 1 AND code = '{$code}'";
        $order = ' ctime desc';
        $code_arr = Cas_Model_Code::instance()->fetchByWhere($where, $order, 1);

        if(!empty($code_arr)){
            if($code_arr[0]['code']!=$code){
                $res['status'] = 1;
                $res['error'] = "短信验证码不正确，请检查输入或重新获取";
                return $res;
            }
        
            if($code_arr[0]['code']==$code){
                if (strtotime("-1800 seconds") > strtotime($code_arr[0]['ctime'])) {
                    $res['status'] = 1;
                    $res['error'] = "验证信息已失效，请重新发起。";
                    return $res;
                }
            }
        }else{
            $res['status'] = 1;
            $res['error'] = "短信验证码不正确，请检查输入或重新获取";
            return $res;
        }
        /* 密码格式 */
        $regpwd = "/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{6,16}$/";
        if (!preg_match($regpwd, $pwd)) {
            $res['status'] = 1;
            $res['error'] = "密码必须为6-16位，同时包含数字和字母两种,331";
            return $res;
        }
       /*两次输入密码*/
        if($pwd !=$repwd){
            $res['status'] = 1;
            $res['error'] = "两次密码输入不一致";
            return $res;
        }
        //推荐人是否为空
        if(!empty($recommender)) {
            $where_recommender  = "user_code='{$recommender}' or phone='{$recommender}'";
        
            $exist_recommender = Cas_Model_User::instance()->fetchByWhere($where_recommender);
            if(empty($exist_recommender)){
                $res['status'] = 1;
                $res['error'] = "该推荐人不存在，请检查输入！";
                return $res;
            }else{
                if($exist_recommender[0]['phone']==$phone){
                    $res['status'] = 1;
                    $res['error'] = "推荐人不能是自己";
                    return $res;
                }
            }
            $parent_id= $exist_recommender[0]['userid'];
        
        }
        
        //加密
        $salt = Zeed_Util::genRandomString(10);
        $encrypt = 'Md5Md5';
        $password = self::encrypt($pwd,$salt);
        //注册时间
        $ctime = date('Y-m-d H:i:s');
        //邀请码不重复
        $user_code = self::userCode();

        $res['data'] = array(
            'phone' =>$phone,
            'password' =>$password,
            'encrypt'=>$encrypt,
            'salt'=>$salt,
            'ctime'=>$ctime,
            'user_code'=>$user_code,
            'parent_id' =>$parent_id?$parent_id:null,
            'user_code'=>$user_code,
        );
        
        return $res;
    }
    /*
     * 加密算法
     */
    public static function encrypt($str='',$salt='')
    {
        return MD5(MD5($str).$salt);
    }
    /*推荐码不重复*/
     public  static function userCode(){
         $result = self::getRandomString(7);
     
         while((self::returnCode($result))==false)
         {
             $result = self::getRandomString(7);
         };
         return $result;
     }
     
     public static function returnCode($result){
         $id = Cas_Model_User::instance()->fetchByWhere(" user_code='{$result}'");
         if($id){
             return false;
         }
         return true;
     }
    
    /*
     * 生成邀请码:随机生成数字字母组合，采用
     */
    public static function getRandomString($len, $chars=null)
    {
        if (is_null($chars)){
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        }
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
}

// End ^ Native EOL ^ UTF-8