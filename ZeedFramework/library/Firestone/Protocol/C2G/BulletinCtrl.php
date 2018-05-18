<?php
/**
 * Firestone_Protocol_C2G_BulletinCtrl
 * 
 * @package Firestone_Protocol_C2G
 * @since 2010-8-30
 * @author yaojie
 * 定时公告设置
 */

class Firestone_Protocol_C2G_BulletinCtrl extends Firestone_Protocol_QMEProtocolSendAbstract
{
    
    const DATA_FORMAT = "Va256VVVV";
    
    protected $_data = array(
            'BulletinCtrlType' => 0,
            'data' => '',
            'addAt' => 0,
            'times' => 0,
            'interval' => 0,
            'delID' => 0);
    
    /**
     * @return Firestone_Protocol_C2G_BulletinCtrl
     */
    public static function instance()
    {
        return parent::_instance();
    }
    /**
     * 设置data
     * @param array|string $key 
     * @param integer|string|null $val
     * @return Firestone_Protocol_C2G_BulletinCtrl
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
     * @return Firestone_Protocol_C2G_BulletinCtrl
     */
    public function makeDataPack()
    {
        $this->_dataPackString = pack(self::DATA_FORMAT, $this->_data['BulletinCtrlType'], $this->_data['data'], $this->_data['addAt'], $this->_data['times'], $this->_data['interval'], $this->_data['delID']);
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
        if ('' == $this->_dataPackString) {
            throw new Zeed_Exception('dataPackString is empty');
            return;
        }
        $this->_returnPackString = $this->_headerPackString . $this->_dataPackString;
        return $this->_returnPackString;
    }

}


// End ^ Native EOL ^ encoding

