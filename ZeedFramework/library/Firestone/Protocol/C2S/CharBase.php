<?php
/**
 * Firestone_Protocol_C2S_CharBase
 * 
 * @package Firestone_Protocol_C2S
 * @since 2010-8-29
 * 
 */

class Firestone_Protocol_C2S_CharBase extends Firestone_Protocol_QMEProtocolSendAbstract
{
    
    /**
     * @return Firestone_Protocol_C2S_CharBase
     */
    public static function instance()
    {
        return parent::_instance();
    }
    /**
     * 设置data 该类data为空
     * @param array|string $key 
     * @param integer|string|null $val
     * @return Firestone_Protocol_C2S_CharBase
     */
    public function setData($key, $val = null)
    {
    
    }
    
    /**
     * 生产data二进制串
     * @return Firestone_Protocol_C2S_CharBase
     */
    public function makeDataPack()
    {
        return $this;
    }
    
    /**
     * 生成最终的二进制串
     */
    public function makeReturnPackString($header, $data)
    {
        $this->setHeader($header)->setData($data);
        $this->makeHeaderPack()->makeDataPack();
        
        if ('' == $this->_headerPackString) {
            throw new Zeed_Exception('headerPackString is empty');
            return;
        }
        
        $this->_returnPackString = $this->_headerPackString;
        return $this->_returnPackString;
    }

}


// End ^ Native EOL ^ encoding
