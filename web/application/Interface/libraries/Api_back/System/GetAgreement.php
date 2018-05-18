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

/**
 * 获取协议规则等接口
 * 
 */
class Api_System_GetAgreement
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    /**
     * 接口运行方法
     *
     * @param string $params
     * @throws Zeed_Exception
     * @return string|Ambigous <string, multitype:number, multitype:number string , unknown, multitype:>
     */
    public static function run($params = null)
    {
        
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            try {
                $config = Zeed_Config::loadGroup('urlmapping');
            	if($res['data']['type'] == 1){//平台介绍、我们的愿景、经营理念
            	    $content = System_Model_Manage::instance()->fetchByWhere("1=1",  null, null, null, array('platform_introduce','our_vision','management_idea'));
            	}elseif($res['data']['type'] == 2){//公司介绍
            	    $content = System_Model_Manage::instance()->fetchByWhere("1=1",  null, null, null, array("company_introduce"));
            	}elseif($res['data']['type'] == 3){//安全保障
            	    $content = System_Model_Manage::instance()->fetchByWhere("1=1",  null, null, null, array("security_assurance"));
            	}elseif($res['data']['type'] == 4){//注册协议
            	    $content = System_Model_Manage::instance()->fetchByWhere("1=1",  null, null, null, array("register_agreement"));
            	}elseif($res['data']['type'] == 5){//经纪人背景图片
            	    $content = System_Model_Manage::instance()->fetchByWhere("1=1",  null, null, null, array("ecoman_image"));
            	    if($content[0]['ecoman_image']){
            	        $content[0]['ecoman_image'] = $config['upload_cdn'].'/uploads'.$content[0]['ecoman_image'];
            	    }
            	    //绑卡状态
            	    if(!empty($res['data']['userid'])){
            	        if(!$userExists = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")){
            	            throw new Zeed_Exception('该用户不存在或被冻结');
            	        }else{
            	            /*绑定银行卡信息*/
            	            $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$userExists[0]['bank_id']}' and is_use=1 and is_del=0");
            	            if(!empty($bank_info)){
            	                $content[0]['is_tiecard']= 1;
            	            }else{
            	                $content[0]['is_tiecard']= 0;
            	            }
            	        }
            	    }
            	}elseif($res['data']['type'] == 6){//经纪人协议
            	    $content = System_Model_Manage::instance()->fetchByWhere("1=1",  null, null, null, array("ecoman_agreement"));
            	}elseif($res['data']['type'] == 7){//推广协议
            	    $content = System_Model_Manage::instance()->fetchByWhere("1=1",  null, null, null, array("generalize_rule"));
            	}elseif($res['data']['type'] == 8){//短信推广内容模板
            	    $content = System_Model_Manage::instance()->fetchByWhere("1=1",  null, null, null, array("note_generalize"));
            	    if(!empty($res['data']['userid'])){
            	        if(!$user = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")){
            	            throw new Zeed_Exception('该用户不存在或被冻结');
            	        }else{
            	            if($user[0]['username']){
            	               $content[0]['note_generalize'] = str_replace('xx', "{$user[0]['username']}", "{$content[0]['note_generalize']}");
            	            }
            	            $content[0]['note_generalize'] = str_replace('yy', $config['upload_cdn'].'/cas/signup'.'?'.'user_code='.$user[0]['user_code'], $content[0]['note_generalize']);
            	        }
            	    }
            	    
            	
            	}elseif($res['data']['type'] == 9){//联系我们
            	    $content = System_Model_Manage::instance()->fetchByWhere("1=1",  null, null, null, array("contact_us"));
            	}
                
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取内容出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            
            if (! empty($content)) {
            	self::$_res['data'] = $content;
            } else {
            	self::$_res['data'] = array();
            }
            
        }

        return self::$_res;
    }
    
    /**
     * 数据校验
     *
     * @param unknown $params
     * @return multitype:number string
     */
    public static function validate($params)
    {
        ksort($params);
        if (! isset($params['type']) || strlen($params['type']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 type 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
