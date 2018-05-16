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
 *富友网银充值回调地址
 * @author Administrator
 *
 */
class WebCallBackController extends IndexAbstract 
{
	/**
     * 默认返回数据
     */
	protected static $_res = array('status' => 0, 'msg' => '', 'data' => '');
	  
    public  function wangyin($params = null)
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    	// http://0/xxx.jsp?mchnt_cd=商户代码&order_id=商户订单号&order_date=订单日期&
    	//order_amt=交易金额&order_st=订单状态&order_pay_code=错误代码&order_pay_error=错误中文描述
    	//&resv1=保留字段&fy_ssn=富友流水号&md5=MD5摘要数据。
    	$mchnt_cd= $this->input->post('mchnt_cd');
    	$order_id = $this->input->post('order_id');
    	$order_date = $this->input->post('order_date');
    	$order_amt = $this->input->post('order_amt');
    	$order_st= $this->input->post('order_st');
    	$order_pay_code= $this->input->post('order_pay_code');
    	$order_pay_error = $this->input->post('order_pay_error');
    	$resv1 = $this->input->post('resv1');
    	$fy_ssn = $this->input->post('fy_ssn');
    	$md5 = $this->input->post('md5');
    	
    	
    	$_mchnt_key = "b9i7ehwb2ngm1vdp412vzgr76vnrkpjf"; //商户密钥
    	
    	//拼接数据
    	$_data = "";
    	$_data .= $mchnt_cd."|";
    	$_data .= $order_id."|";
    	$_data .= $order_date."|";
    	$_data .= $order_amt."|";
    	$_data .= $order_st."|";
    	$_data .= $order_pay_code."|";
    	$_data .= $order_pay_error."|";
    	$_data .= $resv1."|";
    	$_data .= $fy_ssn."|";
    	$_data .= $_mchnt_key;
    	$_md5 = MD5($_data); //签名数据
    	
    	// order_st
    /* 	‘00’ – 订单已生成(初始状态)
    	‘01’ – 订单已撤消
    	‘02’ – 订单已合并
    	‘03’ – 订单已过期
    	‘04’ – 订单已确认(等待支付)
    	‘05’ – 订单支付失败
    	‘11’ – 订单已支付
    	‘18’ – 已发货
    	‘19’ – 已确认收货 */
    	
    	
    	if(strcasecmp($md5,$_md5) ==0){
            $mtime =  date('Y-m-d H:i:s');
            $order_no =  $order_id;
            self::addPaynum($order_no,1);
    	    if($order_st=='11'){
                self::addPaynum($order_no,2);
                $order = Cas_Model_Pay::instance()->fetchByWhere("order_no = '{$order_no}' and numthree<>'0' and pay_type=2");
                if(empty($order)){
                    Cas_Model_Pay::instance()->update(array('code'=>'0000','msg'=>'成功','mtime'=>$mtime),"order_no = '{$order_no}' and pay_type=2");

    	            $order_amt = $order_amt/100;
    	            $res['data']= array(
    	               'order_no' =>$order_id,
    	               'total_fee' =>$order_amt,
    	            );
                    //充值记录
    	            self::addPaynum($order_no,3);
    	            try {
    	                $set = Support_Reapal_pay_TestCallBack::run($res['data']);
    	                /* 返回错误信息  */
    	            } catch(Zeed_Exception $e) {
    	                echo 200;
    	            }
                }
    	        echo 200;
    	    }else{
                Cas_Model_Pay::instance()->update(array('code'=>$order_pay_code,'msg'=>$order_pay_error,'mtime'=>$mtime),"order_no = '{$order_no}' and pay_type=2");
    	        echo 200;
    	    }
    	  
    	}else{
            echo 'shibai';
    	}
    	
    	/* $r['news_title'] = $md5;
		$r['news_content'] = $_md5;
    	News_Model_List::instance()->insert($r);  */
		}

        public static function addPaynum($order_no,$numtype){
        $pay= Cas_Model_Pay::instance()->fetchByWhere("order_no='{$order_no}'");
        if($numtype==1){
            $num = $pay[0]['numone']+1;
            Cas_Model_Pay::instance()->update(array('numone'=>$num),"order_no = '{$order_no}'");
        }elseif($numtype==2){
            $num = $pay[0]['numtwo']+1;
            Cas_Model_Pay::instance()->update(array('numtwo'=>$num),"order_no = '{$order_no}'");
        }elseif($numtype==3){
            $num = $pay[0]['numthree']+1;
            Cas_Model_Pay::instance()->update(array('numthree'=>$num),"order_no = '{$order_no}'");
        }
       
    }
}