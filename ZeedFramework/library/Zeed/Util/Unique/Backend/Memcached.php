<?php
/**
 * Playcool Project
 * 
 * LICENSE
 * 
 * http://www.playcool.com/license/ice
 * 
 * @category   ICE
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      2009-8-12
 * @version    SVN: $Id: Date.php 4880 2010-01-21 03:19:40Z xsharp $
 */

class Zeed_Util_Unique_Backend_Memcached extends Zeed_Cache_Backend_Memcached
{
    /**
     * 重新计算自增的唯一数值 ( 自增值初始化 )
     *
     * @param string|PP_Application_Model_Record $token
     * @return integer
     */
    public function recountUniqueId($token = '')
    {
        return false;
    }
    
    /**
     * 获取一个自增的唯一的数值
     * Do not use Memcache::increment() with item, which was stored compressed,
     * because consequent call to Memcache::get() will fail. 
     * 
     * @param string|PP_Application_Model_Record $token
     * @param boolean $recount 重新计算 Memcached 自增值
     * @return integer|boolean
     */
    public function getUniqueId($key = '', $offset = 1, $recount = false)
    {
        $id = 'UNIQUEID_' . $key;
        
        if ($recount || (! ($tmp = @$this->_memcached->increment($id, $offset)) || empty($tmp))) {
            $init_no = 1;
            
            $this->save($init_no, $id);
            $tmp = $init_no;
        }
        
        if (is_int($tmp) || (is_numeric($tmp) && ($tmp = (int) $tmp))) {
            return $tmp;
        }
        
        return false;
    }
}

// End ^ LF ^ encoding
