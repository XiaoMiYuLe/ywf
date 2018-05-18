<?php
/**
 * Firestone_Protocol_C2S_OnlineQuery
 * 
 * @package    Firestone_Protocol_C2S
 * @since      2010-9-3
 * @author     yaojie
 */
class Firestone_Protocol_C2S_OnlineQuery extends Firestone_Protocol_QMEProtocolSendAbstract
{
    /**
     * @return Firestone_Protocol_C2S_OnlineQuery
     */
    public static function instance()
    {
        return parent::_instance();
    }
    
    /**
     * 设置data
     * 
     * @param array|string
     * @param integer|string|null 
     * @return Firestone_Protocol_C2S_OnlineQuery
     */
    public function setData($key, $val = null)
    {
        return $this;
    }
    
    /**
     * 生产data二进制串
     * @return Firestone_Protocol_C2S_OnlineQuery
     */
    public function makeDataPack()
    {
        return $this;
    }
    
    /**
     * 生成最终的二进制串
     *@return 二进制串
     */
    public function makeReturnPackString($header, $data)
    {
    
    }
}

// End ^ Native EOL ^ encoding