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
 * 添加经纪人
 */
class Api_Cas_MyBankcard
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_allowFields = array('userid');
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		/* 检查用户是否存在 */
                $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['userid']}'");
    	        if (!$userExists) {
    	            throw new Zeed_Exception('该用户不存在，请重新输入');
    	        }
    	        
    	        /* 检查用户状态 */
    	        if($userExists[0]['status'] == 1 ){
    	            throw new Zeed_Exception('该账号已禁用，请重新输入');
    	        }
    	        
    	        $userExists = current($userExists);

    	        /*银行卡列表*/
    	        if(!empty($userExists['bank_id'])){
    	            $bank_card =Cas_Model_Bank::instance()->fetchByWhere("bank_id={$userExists['bank_id']} and is_del=0 and is_use=1",'ctime DESC',null,null,array('bank_id','bank_code','bank_no','bank_name','phonebankcard'));
    	        }
               
    	        //银行卡图片路径
    	       if(!empty($bank_card)){
                    foreach ($bank_card as $k=>&$v){
                        //银行卡尾号
                        $v['bank_no']= substr($v['bank_no'], -4);
                        //限额
                        $url = Zeed_Config::loadGroup('urlmapping');
                        $v['link'] = $url['upload_cdn'].'/static/cas/img/'.$v['bank_code'].'.png';
                        switch ($v['bank_code']){
                             //工商银行
                            case ICBC:
                                $v['quota'] = '单笔5万,单日5万,单月无限额';
                                $v['quotaone'] = '5';
                                $v['quotaday'] = '5';
                                $v['quotamonth']='';
                                $v['quotanum']='';
                            break;
                            //农业银行
                            case ABC:
                               $v['quota'] = '单笔2万,单日5万,单月无限额';
                               $v['quotaone'] = '2';
                               $v['quotaday'] = '5';
                               $v['quotamonth']='';
                               $v['quotanum']='';
                            break;
                            //中国银行
                            case BOC:
                                $v['quota'] = '单笔1万,单日1万,单月无限额';
                                $v['quotaone'] = '1';
                                $v['quotaday'] = '1';
                                $v['quotamonth']='';
                                $v['quotanum']='';
                            break;
                            //建设银行
                            case CCB:
                                $v['quota'] = '单笔5万,单日100万,单月无限额';
                                $v['quotaone'] = '5';
                                $v['quotaday'] = '100';
                                $v['quotamonth']='';
                                $v['quotanum']='';
                            break;
                            //邮政储蓄银行
                            case PSBC:
                                $v['quota'] = '单笔5万,单日5万,单月无限额';
                                $v['quotaone'] = '5';
                                $v['quotaday'] = '5';
                                $v['quotamonth']='';
                                $v['quotanum']='';
                            break;
                            //中信银行
                            case CITIC:
                                $v['quota'] = '单笔30万,单日100万,单月无限额';
                                $v['quotaone'] = '30';
                                $v['quotaday'] = '100';
                                $v['quotamonth']='';
                                $v['quotanum']='';
                            break;
                            //光大银行
                            case CEB:
                                 $v['quota'] = '单笔30万,单日100万,单月无限额';
                                 $v['quotaone'] = '30';
                                 $v['quotaday'] = '500';
                                 $v['quotamonth']='';
                                 $v['quotanum']='';
                            break;
                            //民生银行
                            case CMBC:
                                $v['quota'] = '单笔30万,单日100万,单月无限额';
                                $v['quotaone'] = '30';
                                $v['quotaday'] = '100';
                                $v['quotamonth']='';
                                $v['quotanum']='';
                            break;
                            //平安银行
                            case PAYH:
                                $v['quota'] = '单笔30万,单日100万,单月无限额';
                                $v['quotaone'] = '30';
                                $v['quotaday'] = '100';
                                $v['quotamonth']='';
                                $v['quotanum']='';
                            break;
                            //兴业银行
                            case CIB:
                                 $v['quota'] = '单笔5万,单日5万,单月无限额';
                                 $v['quotaone'] = '5';
                                 $v['quotaday'] = '5';
                                 $v['quotamonth']='';
                                 $v['quotanum']='';
                            break;
                            //招商银行
                            case CMB:
                                 $v['quota'] = '单笔20万,单日100万,单月无限额';
                                 $v['quotaone'] = '20';
                                 $v['quotaday'] = '100';
                                 $v['quotamonth']='';
                                 $v['quotanum']='';
                            break;
                            //广发银行
                            case CGB:
                                $v['quota'] = '单笔30万,单日50万,单月无限额';
                                $v['quotaone'] = '30';
                                $v['quotaday'] = '50';
                                $v['quotamonth']='';
                                $v['quotanum']='';
                            break;
                            //华夏银行
                            case HXB:
                                 $v['quota'] = '单笔1万,单日100万,单月无限额';
                                 $v['quotaone'] = '1';
                                 $v['quotaday'] = '100';
                                 $v['quotamonth']='';
                                 $v['quotanum']='';
                            break;
                            //浦发银行
                            case SPDB:
                                 $v['quota'] = '单笔5000元,单日5000元,单月无限额';
                                 $v['quotaone'] = '0.5';
                                 $v['quotaday'] = '0.5';
                                 $v['quotamonth']='';
                                 $v['quotanum']='';
                            break;
                            break;
                            //交通银行
                            case BOCM:
                                 $v['quota'] = '单笔1万,单日100万,单月无限额';
                                 $v['quotaone'] = '1';
                                 $v['quotaday'] = '100';
                                 $v['quotamonth']='';
                                 $v['quotanum']='';
                            break;
                            
                        }
                    }
                    $res['data'] = $bank_card;
    	        }else{
    	            $res['data'] = '';
    	        } 
    	        
		       
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '申请经纪人。错误信息：' . $e->getMessage();
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
        /* 校验必填项 */
        if (! isset($params['userid']) || ! $params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '用户id未提供';
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
}

// End ^ Native EOL ^ encoding
