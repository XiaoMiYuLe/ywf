<?php
/**
 * Firestone_Protocol_S2C_AccountOpRet
 * 
 * @package  Firestone_Protocol_S2C_AccountOpRet
 * @since    2010-9-2
 * @author   wida<wida@foxmail.com>
 */
class Firestone_Protocol_S2C_AccountOpRet extends Firestone_Protocol_QMEProtocolReceiveAbstract
{
    /**
     * 
     * @var data的解包字符串   返回data 为空  结果在header中
     */
    protected   $unpackFormatString = '';
    
    /**
     * 
     * @var 接收的data格式  非重复结构 0  重复结构为1
     */
    protected $dataType  = 0;      
     
     
}



// End ^ Native EOL ^ encoding