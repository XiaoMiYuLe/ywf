<?php
/**
 * Firestone_Protocol_S2C_CharInquiryRet
 * 
 * @package    Firestone_Protocol_S2C
 * @since      2010-9-1
 * @author     wida<wida@foxmail.com>
 */
class Firestone_Protocol_S2C_CharInquiryRet extends Firestone_Protocol_QMEProtocolReceiveAbstract
{
     /**
     * 
     * @var data的解包字符串
     */
    protected   $unpackFormatString =  'V1id/a21name';
    
    
    /**
     * 
     * @var 接收的data格式  非重复结构 0  重复结构为1
     */
    protected $dataType  = 1;      
    
     /**
      * data是块状多列结果时子类必须重载该属性且必须填写块长度
      * @var integer
      */
     protected $blockLength = 25;
    

}

// End ^ Native EOL ^ encoding