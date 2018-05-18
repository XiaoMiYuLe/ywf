<?php
/**
 * Firestone_Protocol_S2C_AccountOpListRet
 * 
 * @package    Firestone_Protocol_S2C
 * @since      2010-9-1
 * @author     wida<wida@foxmail.com>
 */
class Firestone_Protocol_S2C_AccountOpListRet extends Firestone_Protocol_QMEProtocolReceiveAbstract
{
    /**
     * 
     * @var data的解包字符串
     */
    protected   $unpackFormatString = 'Vid/a31gm/Vaction/a51account/a50reason/Vtime/Vduration';
    
    /**
     * 
     * @var 接收的data格式  非重复结构 0  重复结构为1
     */
    protected $dataType  = 0;      
    
}

// End ^ Native EOL ^ encoding