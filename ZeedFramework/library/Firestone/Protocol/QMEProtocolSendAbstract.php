<?php
/**
 * Firestone_QMEProtocolBase
 * 
 * @package Firestone_Protocols
 * @since   2010-8-24
 * @author  wida<wida@foxmail.com>
 *
 */
abstract class Firestone_Protocol_QMEProtocolSendAbstract
{
    /**
     * header pack格式
     */
    const HEADER_FORMAT = "vvVVV";
    /**
     * 初始化协议头
     */
    private $_header = array(
            'length' => 0,
            'cmd' => 0,
            'param1' => 0,
            'param2' => 0,
            'param3' => 0);
    /**
     * data 数据
     */
    protected $_data = array();
    /**
     * header二进制串
     */
    protected $_headerPackString = '';
    /**
     * data二进制串
     */
    protected $_dataPackString = '';
    /**
     * 要返回的二进制串
     */
    protected $_returnPackString = '';
    
    /**
     * @return Firestone_QMEProtocolBase  返回单件
     */
    public static function _instance()
    {
        static $object = null;
        
        if ($object == null) {
            $object = self;
        }
        
        return $object;
    }
    
    /**
     * @param array|string $key  协议头的key
     * @param int $val			  协议头值
     * @return Firestone_QMEProtocolBase
     */
    public function setHeader($key, $val = null)
    {
        if (is_array($key) && ! is_numeric($key)) {
            foreach ($key as $k => $v) {
                $this->setHeader($k, $v);
            }
        } else {
            if (is_null($val) || ! isset($this->_header[$key])) {
                throw new Zeed_Exception("not match key");
                return;
            }
            
            $this->_header[$key] = $val;
        }
        return $this;
    }
    
    /**
     * pack 协议头
     * @return Firestone_QMEProtocolBase
     */
    public function makeHeaderPack()
    {
        //格式化
        settype($this->_header['length'], 'integer');
        settype($this->_header['cmd'], 'integer');
        settype($this->_header['param1'], 'integer');
        settype($this->_header['param2'], 'integer');
        settype($this->_header['param3'], 'integer');
        
        $this->_headerPackString = pack(self::HEADER_FORMAT, $this->_header['length'], $this->_header['cmd'], $this->_header['param1'], $this->_header['param2'], $this->_header['param3']);
        return $this;
    }
    
    /**
     * @return  array 返回herder
     */
    public function getHeader()
    {
        return $this->_header;
    }
    
    /**
     * @return  array 返回data
     */
    public function getData()
    {
        return $this->_data();
    }
    
    /**
     * @return Header 二进制串
     */
    public function getHeaderPackString()
    {
        return $this->_headerPackString;
    }
    
    /**
     * @return Data 二进制串
     */
    public function getDataPaceString()
    {
        return $this->_dataPackString;
    }
    
    /**
     * @return Header+Data 二进制串
     */
    public function getResultString()
    {
        return $this->_returnPackString;
    }
    
    /**
     * @return string Header解包格式
     */
    public function unPackHeader()
    {
        return 'v1length/v1cmd/V3param';
    }
    
    /**
     * 
     * @param array|string $key
     * @param integer|string $val
     */
    abstract public function setData($key, $val = null);
    
    /**
     * 生产data二进制串
     */
    abstract public function makeDataPack();
    
    /**
     * @param array $header
     * @param array $data
     */
    
    abstract public function makeReturnPackString($header, $data);

}

