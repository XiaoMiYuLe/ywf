<?php

/**
 * 将内容写入 Excel 文件
 */
define("ZEED_PHPEXCEL_PATH", ZEED_PATH_LIB . 'Widget/PhpExcel/');
require ZEED_PHPEXCEL_PATH . 'Abstract.php';
require ZEED_PHPEXCEL_PATH . '1.8.0/Classes/PHPExcel.php';

class Widget_PhpExcel_Api_Write extends Widget_PhpExcel_Abstract {

    
    
    /**
     * params
     */
    private static $_res = array('status' => 0, 'error' => null, 'data' => null);
    /**
     * 写入到excel2007
     * @param type $data
     */
    public static function downToExcel($data) {
        self::write($data, "Excel2007");
    }

    /**
     * 生成PDF格式的
     * @param type $data
     */
    public static function downToPdf($data) {
         self::write($data, "PDF");
    }

    /**
     * 生成CSV格式的
     * @param type $data
     */
    public static function downToCsv($data) {
         self::write($data, "CSV");
    }

    /**
     * 生成2003版本的exlce
     * @param type $data
     */
    public static function downToExcel2003($data) {
        self::write($data, "Excel5");
    }
    
    /**
     * 获取名称
     */
    public static function getFileName($pre="Order"){
        //pre为前缀
        $filename = $pre."_".date("Y-m-d-H-i");
        return $filename;
    }

    public static function write($input, $type) {
        if (empty($input['list'])) {
            return false;
        }
        if(!isset($input['filename'])){
            $filename = self::getFileName();
        }else{
            $filename = $input['filename'];
        }
        if(!isset($input['title'])){
            $title = "Order";
        }else{
            $title = $input['title'];
        }
        $data = $input['list'];
        $cols_name = $input['cols_name'];
        //两个数组合并
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("system_export")
                ->setLastModifiedBy("system_export")
                ->setTitle("Office 2007 XLSX Document")
                ->setSubject("Office 2007 XLSX Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Order");
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Add some data
        $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        array_unshift($data, $cols_name);
        foreach ($data as $k => $v) {
            $line_nums = count($v);
            if ($line_nums > (26 * 26)) {
                self::$_res['status'] = 1;
                self::$_res['error'] = "内容列数超出范围，暂不支持";
                return self::$_res;
            }

            $i = 0;
            foreach ($v as $kk => $vv) {
                $letter = $letters[$i];
                if ($i > 25) {
                    $s = (int) ($line_nums / 26);
                    $m = $i % $line_nums;
                    if ($s > 2) {
                        $letter = $letters[$s];
                    } else {
                        $letter = $letters[0];
                    }
                    $letter .= $letters[$m];
                }
                $objPHPExcel->getActiveSheet()->setCellValue($letter . ($k + 1), $vv);
                $i++;
            }
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($title);

        switch ($type) {
            case 'Excel2007':
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=".$filename.".xlsx");
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                break;
            case 'PDF':
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
                $objWriter->save($file);
                break;
            case 'Excel5':
                // 生成2003excel格式的xls文件
                header('Content-Type: application/vnd.ms-excel');
                header("Content-Disposition: attachment;filename='".$filename.".xls'");
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                break;
            case 'CSV':
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')->setDelimiter(',');  //设置分隔符
                $objWriter->setEnclosure('"');    //设置包围符
                $objWriter->setLineEnding("\r\n"); //设置行分隔符
                $objWriter->setSheetIndex(0);      //设置活动表
                $objWriter->save(str_replace('.php', '.csv', __FILE__));
                break;
            case 'HTML':
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');       //将$objPHPEcel对象转换成html格式的
                $objWriter->setSheetIndex(0);  //设置活动表
                $objWriter->setImagesRoot('http://www.example.com');
                $objWriter->save(str_replace('.php', '.htm', __FILE__));     //保存文件
                break;
        }
    }

}
