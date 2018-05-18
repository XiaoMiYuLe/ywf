<?php
/**
 * Firestone_Protocol_S2C_AccountInquiryRet
 * 
 * @package    Firestone_Protocol_S2C
 * @since      2010-8-31
 * @author     wida<wida@foxmail.com>
 */
class Firestone_Protocol_S2C_AccountInquiryRet extends Firestone_Protocol_QMEProtocolReceiveAbstract
{
     /**
     * 
     * @var data的解包字符串
     */
    protected   $unpackFormatString = 'a51name/Vclinet_id/freeze_time';
    
    /**
     * 
     * @var 接收的data格式  非重复结构 0  重复结构为1
     */
    protected $dataType  = 1;      
    
    /**
     * data是块状多列结果时子类必须重载该属性且必须填写块长度
     * @var integer
     */
     protected $blockLength = 59;

}

// End ^ Native EOL ^ encoding
