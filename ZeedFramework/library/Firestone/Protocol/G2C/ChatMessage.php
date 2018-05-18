<?php
/**
 * Firestone_Protocol_G2C_ChatMessage
 * 
 * @package Firestone_Protocol_C2G
 * @since   2010-8-30
 * @author  yaojie
 */

class Firestone_Protocol_G2C_ChatMessage extends Firestone_Protocol_QMEProtocolReceiveAbstract
{
    
     /**
     * 
     * @var data的解包字符串
     */
    protected   $unpackFormatString = 'a*msg';
    
    /**
     * 
     * @var 接收的data格式  非重复结构 0  重复结构为1
     */
    protected $dataType  = 0;      
    

}


// End ^ Native EOL ^ encoding
