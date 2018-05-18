<?php
/**
 * 读取 Excel 文件内容
 */

class Widget_PhpExcel_Api_Read extends Widget_PhpExcel_Abstract
{
    /**
     * params
     */
    private static $_res = array('status' => 0, 'error' => null, 'data' => null);
    
    /**
     * 读取 Excel 文件内容
     */
    public static function index($file)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //创建一个2007的读取对象
        $objPHPExcel = $objReader->load ($file);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        $arr_result=array();
        $strs=array();
        
        $i = 0;
        for($j=2;$j<=$highestRow;$j++) {
            for($k='A';$k<= $highestColumn;$k++) {
                //读取单元格
                $arr_result[$i][] = $objPHPExcel->getActiveSheet()->getCell($k.$j)->getValue().',';
            }
            $i++;
        }
        self::$_res['data'] = $arr_result;
        return self::$_res;

    }
    
    /**
     * 读取 上传 文件内容
     */
    public function readExcelForForm($filename)
    {
    
        $succ_result=0;
        $error_result=0;
        $max_size="2000000000"; //最大文件限制（单位：byte）
    
        $objPHPExcel = PHPExcel_IOFactory::load($filename["tmp_name"]);
        //内容转换为数组
        $indata = $objPHPExcel->getSheet(0)->toArray();
    
        self::$_res['data'] = $indata;
        return self::$_res;
    
    }
}
