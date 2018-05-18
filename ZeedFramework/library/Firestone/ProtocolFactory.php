<?php
/**
 * 协议工厂类
 * 
 * ProtocolFactory
 * 
 * @package Friestone
 * @since 2010-8-26
 * @author wida<wida@foxmail.com>
 */
class Firestone_ProtocolFactory
{
    
    /**
     * 禁用本类复制行为
     */
    private function __clone()
    {
    }
    
    /**
     * 不允许直接调用构造函数
     */
    private function __construct(){
    }
    
    /**
     * 协议装载器
     * @param string $ProtocolName   协议名称
     * @return object                协议名对应的协议
     */
    public static function loadProtocol($ProtocolName)
    {
        static $_objects = array();
        
        if (empty($ProtocolName)) {
            throw new Zeed_Exception('protoname is empty');
            return;
        }
        if (false === strpos($ProtocolName, 'Firestone_Protocol_')) {
            $ProtocolName = 'Firestone_Protocol_' . $ProtocolName;
        }
        if (isset($_objects[$ProtocolName])) {
            return $_objects[$ProtocolName];
        }
        
        $_objects[$ProtocolName] = new $ProtocolName();
        
        return $_objects[$ProtocolName];
    }

}

// End ^ Native EOL ^ encoding
