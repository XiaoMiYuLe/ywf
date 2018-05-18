<?php
/**
 * Firestone_Protocol_C2S_ListServer
 * 
 * @package Firestone_Protocol_C2S
 * @since   2010-8-29
 * @author  wida<wida@foxmail.com>
 */

class Firestone_Protocol_C2S_ListServer extends Firestone_Protocol_QMEProtocolSendAbstract
{
    
    const DATA_FORMAT = "a*";
    
    /**
     * @return Firestone_Protocol_C2S_ListServer
     */
    public static function instance()
    {
        return parent::_instance();
    }
    /**
     * 设置data
     * @param array|string $key 
     * @param integer|string|null $val
     * @return Firestone_Protocol_C2S_ListServer
     */
    public function setData($key, $val = null)
    {
        if (is_array($key) && ! is_numeric($key)) {
            foreach ($key as $k => $v) {
                $this->setData($k, $v);
            }
        } else {
            if (! array_key_exists($key, $this->_data)) {
                throw new Zeed_Exception("not match key");
                return;
            }
            $this->_data[$key] = $val;
        }
        return $this;
    }
    
    /**
     * 生产data二进制串
     * @return Firestone_Protocol_C2S_ListServer
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
        
        $this->_returnPackString = $this->_headerPackString . $this->_dataPackString;
        return $this->_returnPackString;
    }

}


// End ^ Native EOL ^ encoding
