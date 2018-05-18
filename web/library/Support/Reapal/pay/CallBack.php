<?php
/**
 * 回调处理
 */

class Support_Reapal_pay_CallBack
{
	/**
	 * 确认支付接口
	 *
	 * @param string $data 请求数据
	 * @return data
	 */
	protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
	
	public static function run($data,$encryptkey,$merchant_id)
	{
		if (! $data) {
			return false;
		}
		require_once dirname(dirname(__FILE__)).'/util.php'; 
		require_once dirname(dirname(__FILE__)).'/config.php';
		   
	$encryptkey = RSADecryptkey($encryptkey,$merchantPrivateKey);
	$decryData = AESDecryptResponse($encryptkey, $data);

	$jsonObject = json_decode($decryData,true);
	
	$merchant_id = $jsonObject['merchant_id'];
	$trade_no = $jsonObject['trade_no'];
	$order_no = $jsonObject['order_no'];
	$total_fee = $jsonObject['total_fee'];
	$status = $jsonObject['status'];
	$result_code = $jsonObject['result_code'];
	$result_msg = $jsonObject['result_msg'];
	$sign = $jsonObject['sign'];
	$notify_id = $jsonObject['notify_id'];
	$paramarr = array(
		'merchant_id' => $merchant_id,
		'trade_no' => $trade_no,
		'order_no' => $order_no,
		'total_fee' => $total_fee,
		'status' => $status,
		'result_code' => $result_code,
		'result_msg' => $result_msg,
		'notify_id' => $notify_id
	);
	$mtime =  date('Y-m-d H:i:s');
	$mysign = createSign($paramarr, $apiKey);

	//成功
	if ($mysign === $sign){
		self::addPaynum($order_no,1);
		if($status === "TRADE_FINISHED"){
			self::addPaynum($order_no,2);
		    //Cas_pay表
		    $order = Cas_Model_Pay::instance()->fetchByWhere("order_no = '{$order_no}' and numthree<>'0'");
		    if(empty($order)){
		    Cas_Model_Pay::instance()->update(array('code'=>'0000','msg'=>'成功','mtime'=>$mtime),"order_no = '{$order_no}'");
			//单位转换
		    $total_fee = $total_fee/100;
			$res['data']= array(
			    'order_no' =>$order_no,
			    'total_fee' =>$total_fee,
			);
          
            self::addPaynum($order_no,3);
			//后续处理
			if ($_res['status'] == 0) {
			    try {
			       $set = Support_Reapal_pay_TestCallBack::run($res['data']);
			    } catch(Zeed_Exception $e) {
			       /*  Cas_Model_Pay::instance()->rollBack();
			        Cas_Model_Bank::instance()->rollBack();
    			    Cas_Model_User::instance()->rollBack();
    			    Cas_Model_Record_Log::instance()->rollBack();
    			    Bts_Model_Order::instance()->rollBack();
    			    Goods_Model_List::instance()->rollBack();
    			    Cas_Model_User_Voucher::instance()->rollBack();
    			    Recharge_Model_List::instance()->rollBack(); */
			        $res['status'] = 1;
			        $res['error'] = '错误信息：' . $e->getMessage();
			        return $_res;
			    }
			}
		   }
		   $verifyStatus = "success";
		}else {
			//失败
			Cas_Model_Pay::instance()->update(array('code'=>$result_code,'msg'=>$result_msg,'mtime'=>$mtime),"order_no = '{$order_no}'");
			$verifyStatus = "success";
		}
		
	}else {
		//失败签名错误
		$verifyStatus = "fail";
	}
	    echo $verifyStatus;
// 		return $verifyStatus;
		
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