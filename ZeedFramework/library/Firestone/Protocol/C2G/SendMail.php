<?php
/**
 * Firestone_Protocol_C2G_SendMail
 * 
 * @package Firestone_Protocol_C2G
 * @since 2010-8-30
 * @author yaojie
 * 通过帐号查询角色名及帐号信息(计费信息)
 */

class Firestone_Protocol_C2G_SendMail extends Firestone_Protocol_QMEProtocolSendAbstract
{
    
    const DATA_FORMAT = "Va21a513V5a*";
    
    protected $_data = array(
            'toID' => 0,
            'subject' => '',
            'content' => '',
            'attachType' => 0,
            'attachValue' => 0,
            'attachMoney'  => 0,
            'payMoney' => 0,
            'attachSize' => 0,
            'char'=>''
            );
    
    /**
     * @return Firestone_Protocol_C2G_SendMail
     */
    public static function instance()
    {
        return parent::_instance();
    }
    
    /**
     * 设置data
     * @param array|string $key 
     * @param integer|string|null $val
     * @return Firestone_Protocol_C2G_SendMail
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
     * @return Firestone_Protocol_C2G_SendMail
     */
    public function makeDataPack()
    {
        $this->_dataPackString = pack(self::DATA_FORMAT,
                                      $this->_data['toID'], 
                                      $this->_data['subject'],
                                      $this->_data['content'],
                                      $this->_data['attachType'],
                                      $this->_data['attachValue'],
                                      $this->_data['attachMoney'],  
                                      $this->_data['payMoney'],                             
                                      $this->_data['attachSize'],
                                      $this->_data['char']);
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

