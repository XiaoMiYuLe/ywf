<?php
header("Content-Type:text/html;charset=utf-8");
ini_set("error_reporting","E_ALL & ~E_NOTICE"); 


$datm="";
class ExcelToArrary{
	

	 public function __construct() {
		 include_once('PHPExcel.php');
	 }

	 public function read($filePath){
	
			 $PHPExcel = new PHPExcel(); 
			/**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/ 
			$PHPReader = new PHPExcel_Reader_Excel2007(); 
	
		if(!$PHPReader->canRead($filePath)){ 
			$PHPReader = new PHPExcel_Reader_Excel5(); 
				if(!$PHPReader->canRead($filePath)){ 
					echo 'no Excel'; 
					return ; 
				} 
		} 
	
		$PHPExcel = $PHPReader->load($filePath); 
		/**读取excel文件中的第一个工作表*/ 
		$currentSheet = $PHPExcel->getSheet(0); 
		/**取得最大的列号*/ 
		$allColumn = $currentSheet->getHighestColumn(); 
		/**取得一共有多少行*/ 
		$allRow = $currentSheet->getHighestRow(); 
		/**从第二行开始输出，因为excel表中第一行为列名*/ 
		for($currentRow = 2;$currentRow <= $allRow;$currentRow++){ /**从第A列开始输出*/ 
			for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){ 
				$val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()字符转为十进制数*/ 
		
			/**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/ 
				//$datm = iconv('utf-8','utf-8', $val)."|";
				$datm .= $val.",";
			} 
			 $datm = $datm."|";
		} 
		return substr($datm,0,-1);
	 }
}

?>