<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Firestone_Protocol_S2C_AccountTakeRet
 * @package    Firestone_Protocol_S2C_AccountTakeRet
 * @subpackage Firestone_Protocol_S2C
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-10-28
 * @version    SVN: $Id$
 */
class Firestone_Protocol_L2C_AccountTakeRet extends Firestone_Protocol_QMEProtocolReceiveAbstract
{
    
     /**
     * 
     * @var data的解包字符串
     */
    protected   $unpackFormatString = 'a51username/a33password/Vexpired';
    
    
    protected   $blockLength = 88;
    
    /**
     * 
     * @var 接收的data格式  非重复结构 0  重复结构为1
     */
    protected $dataType  = 1;      
    
}


// End ^ Native EOL ^ encoding
