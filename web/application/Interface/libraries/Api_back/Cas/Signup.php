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
class Api_Cas_Signup
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    protected static $_allowFields = array( 'phone', 'password', 'recommender');

    /**
     * 接口运行方法
     *
     * @param string $params
     * @throws Zeed_Exception
     * @return string Ambigous multitype:number, multitype:number string ,
     *         unknown, multitype:>
     */
    public static function run($params = null)
    {
         $res = self::validate($params);
         if ($res['status'] === 0) {
            try {
                
                /* 判断手机格式 */
                Util_Validator::test_mobile($res['data']['phone'],"请填写正确的手机号码");
                
                /* 判断是否传password */
                Util_Validator::test_pwd(trim($res['data']['password']),"密码必须为6-16位，同时包含数字和字母两种,444");
                //验证用户是否注册过
                $exist_phone = Cas_Model_User::instance()->fetchByWhere( "phone = '{$res['data']['phone']}'");

                if($exist_phone){
                    throw new Zeed_Exception('手机号已注册！');
                }
               
                //电话号码
                $data['phone'] = $res['data']['phone'];
                
    			//加密
    			$data['salt'] = Zeed_Util::genRandomString(10);
    			$data['encrypt'] = 'Md5Md5';
    			$data['password'] = self::encrypt($res['data']['password'],$data['salt']);
    			
                //推荐人是否为空
        		if(!empty($res['data']['recommender'])) {
        		    $where = "user_code='{$res['data']['recommender']}' or phone='{$res['data']['recommender']}'";
        		    
        		    $exist_recommender = Cas_Model_User::instance()->fetchByWhere($where);
        		    if(empty($exist_recommender)){
        		        throw new Zeed_Exception('该推荐人不存在，请检查输入！');
        		    }else{
        		        if($exist_recommender[0]['phone']==$res['data']['phone']){
        		            throw new Zeed_Exception('推荐人不能是自己');
        		        }
        		    }
    		        $data['parent_id'] = $exist_recommender[0]['userid'];
        		    
        		}
                
    			//邀请码不重复
    			$data['user_code'] = self::userCode();
    			//注册时间
    			$data['ctime'] = date('Y-m-d H:i:s');
    			//入库
    			$userid = Cas_Model_User::instance()->addForEntity($data);
    			
    			if($userid){
                	$user_info = Cas_Model_User::instance()->fetchByWhere("userid=$userid");
                    $res['data'] = (is_array($user_info) && count($user_info)>0)? $user_info[0]: null;
                    $res['status'] = 0;
                    $res['msg'] = '注册成功';
                     //发送优惠券
                    Cas_Model_User_Voucher::instance()->sendCoupon(1,$userid);
                } else {
                    $res['status'] = 1;
                    $res['msg'] = '注册失败';
                }

         } catch (Exception $e) {
                $res['status'] = 1;
                $res['error'] = "失败。错误信息：" . $e->getMessage();
                return $res;
            }
        }

        return $res;
    }

    /**
     * 验证参数
     */
    public static function validate($params)
    {
        if (! isset($params['phone']) || ! $params['phone']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 phone未提供';
            return self::$_res;
        }
        
        if (! isset($params['password']) || ! $params['password']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 password未提供';
            return self::$_res;
        }
        
        /* 组织数据 */
        $set = array();
        foreach (self::$_allowFields as $f) {
            $set[$f] = isset($params[$f]) ? $params[$f] : null;
        }
        self::$_res['data'] = $set;

        return self::$_res;
    }
    
    /*
     * 加密算法
     */
    public static function encrypt($str='',$salt='')
    {
        return MD5(MD5($str).$salt);
    }
    /*
     * 生成推荐码：某一秒加上其毫秒，不采用
     */
    public function getInviteCode() {
        list($s1, $s2) = explode(' ', microtime());
        return sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
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

// End ^ Native EOL ^ encoding
