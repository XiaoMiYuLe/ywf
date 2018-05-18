<?php
/**
 * Firestone_Protocol_S2C_AccountInfoRet
 * 
 * @package    Firestone_Protocol_S2C
 * @since      2010-8-31
 * @author     wida<wida@foxmail.com>
 */
class Firestone_Protocol_S2C_AccountInfoRet extends Firestone_Protocol_QMEProtocolReceiveAbstract
{
	
     /**
     * 
     * @var data的解包字符串
     */
    protected   $unpackFormatString = 'a51name/Vclient_id/Vfreeze_time';
    
    /**
     * 
     * @var 接收的data格式  非重复结构 0  重复结构为1
     */
    protected $dataType  = 0;      
    
}

// End ^ Native EOL ^ encoding