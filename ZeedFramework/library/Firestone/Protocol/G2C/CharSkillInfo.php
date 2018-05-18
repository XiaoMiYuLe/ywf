<?php
/**
 * Firestone_Protocol_G2C_CharSkillInfo
 * 
 * @package    Firestone_Protocol_G2C
 * @since      2010-9-1
 * @author     wida<wida@foxmail.com>
 */
class Firestone_Protocol_G2C_CharSkillInfo extends Firestone_Protocol_QMEProtocolReceiveAbstract
{
    /**
     * 
     * @var data的解包字符串
     */
    protected $unpackFormatString = 'V1id/V1type/a20name/V1level';
    
    /**
     * 
     * @var 接收的data格式  非重复结构 0  重复结构为1
     */
    protected $dataType  = 0;      
    
}

// End ^ Native EOL ^ encoding