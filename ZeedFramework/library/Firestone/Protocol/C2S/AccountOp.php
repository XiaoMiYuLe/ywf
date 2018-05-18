<?php
/**
 * 
 * 
 * Firestone_Protocol_C2S_AccountOp
 * @package    Firestone_Protocol_C2S
 * @since      2010-9-2
 * @author     yaojie
 */
class Firestone_Protocol_C2S_AccountOp extends Firestone_Protocol_QMEProtocolSendAbstract
{
    const DATA_FORMAT = 'Va51a50V';

    protected $_data = array(
            'action' => 0,
            'account' => '',
            'reason' => '',
            'duration' => 0);
    /**
     * @return Firestone_Protocol_C2S_AccountOp
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
     * @return Firestone_Protocol_C2S_AccountOp
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
     * @return Firestone_Protocol_C2S_AccountOp
     */
    public function makeDataPack()
    {
        $this->_dataPackString = pack(
                            self::DATA_FORMAT, 
                            $this->_data['action'],
                            $this->_data['account'],
                            $this->_data['reason'],
                            $this->_data['duration']
        );
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