<?php
header("Content-Type:text/html;charset=utf-8");
require_once 'util.php'; 
require_once 'config.php';
require_once 'readExcel.php';

$time2 = date("y-m-d h:i:s",time());

$read = new ExcelToArrary();

if (! empty ( $_FILES ['file_stu'] ['name'] )) 

 {
    $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
    $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
    $file_type = $file_types [count ( $file_types ) - 1];

     /*判别是不是.xls文件，判别是不是excel文件*/
     if (strtolower ( $file_type ) != "xls")              
    {
          $this->error ( '不是Excel文件，重新上传' );
     }

    /*设置上传路径*/
     $savePath = $savepath;

    /*以时间来命名上传的文件*/
     $str = date ( 'Ymdhis' ); 
     $file_name = $str . "." . $file_type;

     /*是否上传成功*/
     if (! copy ( $tmp_file, $savePath . $file_name )) 
      {
          $this->error ( '上传失败' );
      }

    /*

       *对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中

      注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入

    */
  $res = $read->read ( $savePath . $file_name );




//参数数组
 $paramArr = array(
      'charset' => $charset,
	  'notify_url' => $notify_url,
	  'trans_time' => $time2,
	  'batch_no' => $_REQUEST['batch_no'],
	  'batch_count' => $_REQUEST['batch_count'],
	  'batch_amount' => $_REQUEST['batch_amount'],
	  'pay_type' => $_REQUEST['pay_type'],
	  'content' => $res,
   );
  
$url = $dsfUrl.'agentpay/pay';
echo $url,"\n";
$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id,$version);
print_r($result);
$response = json_decode($result,true);
print_r($response);
$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
echo AESDecryptResponse($encryptkey,$response['data']);
 }
?>