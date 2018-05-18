<?php
/**
 * Firestone_Protocol_QMEProtocolReceiveAbstract
 * 
 * @package Firestone_Protocols
 * @since   2010-9-2
 * @author  wida<wida@foxmail.com>
 *
 */

abstract class Firestone_Protocol_QMEProtocolReceiveAbstract
{
    /**
     * 
     * @var data的解包字符串
     */
    protected $unpackFormatString;
    
    /**
     * 
     * @var 接收的data格式  非重复结构 0  重复结构为1
     */
    
    protected $dataType = 0;
    
    /**
     * data是块状多列结果时子类必须重载该属性且必须填写块长度
     * @var integer
     */
    protected $blockLength = 0;
    
    /**
     * 
     * @param string 要解包的二进制串
     * @return array 解包后的数组
     */
    
    public function unpackData($str,$toUTF8 = 1)
    {
        $array = array();
        if ($this->dataType == 1) {
            if ($this->blockLength == 0 || $this->blockLength > strlen($str)) {
                throw new Zeed_Exception(__CLASS__ . 'blockLength is zero or blockLength longer than string');
            }
            
            $row = strlen($str) / ($this->blockLength);
            for ($i = 0; $i < $row; $i ++) {
                $array[] = unpack($this->unpackFormatString, $str);
                /**
                 * 偏移“指针”
                 */
                $str = substr($str, $this->blockLength);
            }
            return $array;
        } else {
            $array = unpack($this->unpackFormatString, $str);
        }
       if ($toUTF8)
       {    
       	    array_walk_recursive($array, array(
                $this,
                'gbk2Utf8'));
       }
        return $array;
    }
    
    /**
     * 
     * @return data的unpack字符串
     */
    
    public function unPackDataFormatString()
    {
        return $this->unpackFormatString;
    }
    
    /**
     * 编码转换 gbk2utf-8
     * @param $string reference 
     * @param $key reference
     * @return void
     */
    
    public function gbk2Utf8(&$string, &$key)
    {
    	$string = mb_convert_encoding($string, "UTF-8", "GBK");
       //$string = iconv('gbk', 'UTF-8//TRANSLIT', $string);
    }
}