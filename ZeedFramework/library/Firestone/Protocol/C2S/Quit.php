<?php
/**
 * Firestone_Protocol_C2S_Quit
 * 
 * @package Firestone_Protocol_C2S
 * @since   2010-8-29
 * @author  yaojie
 */

class Firestone_Protocol_C2S_Quit extends Firestone_Protocol_QMEProtocolSendAbstract
{
    /**
     * @return Firestone_Protocol_C2S_Quit
     */
    public static function instance()
    {
        return parent::_instance();
    }
    /**
     * 设置data
     * @param array|string $key 
     * @param integer|string|null $val
     * @return Firestone_Protocol_C2S_Quit
     */
    public function setData($key, $val = null)
    {
    }
    
    /**
     * 生产data二进制串
     * @return Firestone_Protocol_C2S_Quit
     */
    public function makeDataPack()
    {
    }
    
    /**
     * 生成最终的二进制串
     */
    public function makeReturnPackString($header, $data)
    {
    }
}

// End ^ Native EOL ^ encoding
