<?php
/**
 * Firestone_Protocol_C2G_Teleport
 * @package Firestone_Protocol_C2G
 * @since 2010-8-30
 * @author yaojie
 * 传送玩家
 */

class Firestone_Protocol_C2G_Teleport extends Firestone_Protocol_QMEProtocolSendAbstract
{
    
    const DATA_FORMAT = "Va21a21VVa*";
    
    protected $_data = array(
            'TeleportType' => 0,
            'name' => '',
            'map' => '',
            'map_x' => 0,
            'map_y' => 0,
            'npc_or_player' => '');
    
    /**
     * @return Firestone_Protocol_C2G_Teleport
     */
    public static function instance()
    {
        return parent::_instance();
    }
    
    /**
     * 设置data
     * @param array|string $key 
     * @param integer|string|null $val
     * @return Firestone_Protocol_C2G_Teleport
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
     * @return Firestone_Protocol_C2G_Teleport
     */
    public function makeDataPack()
    {
        $this->_dataPackString = pack(self::DATA_FORMAT, $this->_data['TeleportType'], $this->_data['name'], $this->_data['map'], $this->_data['map_x'], $this->_data['map_y'], $this->_data['npc_or_player']);
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

