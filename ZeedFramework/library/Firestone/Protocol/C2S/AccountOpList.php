<?php
/**
 * 
 * 
 * Firestone_Protocol_C2S_AccountOpList
 * @package    Firestone_Protocol_C2S
 * @since      2010-9-1
 * @author     wida<wida@foxmail.com>
 */
class Firestone_Protocol_C2S_AccountOpList extends Firestone_Protocol_QMEProtocolSendAbstract
{
    const DATA_FORMAT = 'a*x';
    
    protected $_data = array(
            'keyword' => '');
    /**
     * @return Firestone_Protocol_C2S_AccountOpList
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
     * @return Firestone_Protocol_C2S_AccountOpList
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
     * @return Firestone_Protocol_C2S_AccountOpList
     */
    public function makeDataPack()
    {
        $this->_dataPackString = pack(self::DATA_FORMAT, $this->_data['keyword']);
        return $this;
    }
    
    /**
     * 生成最终的二进制串
     *@return 二进制串
     */
    public function makeReturnPackString($header, $data)
    {
        $this->setHeader($header)->setData($data);
        $this->makeHeaderPack()->makeDataPack();
        
        if ('' == $this->_headerPackString) {
            throw new Zeed_Exception('headerPackString is empty');
            return;
        }
        if ('' == $this->_dataPackString) {
            throw new Zeed_Exception('dataPackString is empty');
            return;
        }
        $this->_returnPackString = $this->_headerPackString . $this->_dataPackString;
        return $this->_returnPackString;
    }

}



// End ^ Native EOL ^ encoding