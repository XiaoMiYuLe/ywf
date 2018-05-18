<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-7-6
 * @version    SVN: $Id$
 */

class Com_Model_Mapping_User extends Zeed_Db_Model
{
    /*
     * @var string The table name.
     */
    protected $_name = 'mapping_user';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'userid';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'os_';
    
    /**
     * 添加 Mapping 表信息
     *
     * @param array|Com_Entity_Mapping_User $set
     * @return integer|false 添加成功返回 userid 值，失败返回 false
     * @see Com_Model_User::add()
     */
    public function add($set)
    {
        $mappingUser = new Com_Entity_Mapping_User();
        $mappingUser->fromObject($set);
        
        if ($mappingUser->isEmpty()) {
            return false;
        }
        
        $data = $mappingUser->toArray();
        
        try {
            $userid = $this->insert($data);
            
            if (isset($data['userid']) && is_numeric($data['userid'])) {
                $userid = $data['userid'];
            }
        } catch (Exception $e) {
            $userid = false;
        }
        
        return $userid;
    }
    
    /**
     * 用户模糊搜索 
     * @param string $like
     * @return array|null
     */
    public function getUserByusernameLike($like)
    {
        $like = trim($like);
        if (empty($like))
            return null;
        $like = str_replace(array(
                '%',
                '*'), array(
                '_',
                '%'), $like);
        $sql = sprintf('SELECT * FROM ' . $this->getTable() . ' WHERE `username` LIKE %s LIMIT 100', $this->getAdapter()->quote($like));
        try {
            $data = $this->getAdapter()->query($sql)->fetchAll();
        } catch (Exception $e) {
            echo $e;
        }
        return is_array($data) && ! empty($data) ? $data : null;
    }
    
    /**
     * 获取一个新的用户标志
     * 
     * @return integer|false
     * @throws Zeed_Exception
     */
    public function getNewUserid()
    {
        /**
         * 临时从 Cache 的配置中获取 memcached 的实例对象
         */
        $options = Zeed_Config::loadGroup('cache.memcached.backendOption');
        if (! is_array($options['servers']) || empty($options['servers'])) {
            
            throw new Zeed_Exception('getNewUserid() is use memcached increment to get userid. must set memcached servers.');
        }
        
        $memd = new Memcached();
        
        foreach ($options['servers'] as $server) {
            if (! array_key_exists('port', $server)) {
                $server['port'] = 11211;
            }
            
            if (! array_key_exists('weight', $server)) {
                $server['weight'] = 1;
            }
            
            $memd->addServer($server['host'], $server['port'], $server['weight']);
        }
        
        $memd->setOption(Memcached::OPT_COMPRESSION, false);
        $memd->setOption(Memcached::OPT_CONNECT_TIMEOUT, 1000);
        $memd->setOption(Memcached::OPT_RETRY_TIMEOUT, 0);
        $memd->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, false);
        
        $id = 'UNIQUEID_' . $this->getTable();
        $offset = mt_rand(1, 5);
        
        if (! ($tmp = @$memd->increment($id, $offset)) || empty($tmp)) {
            $init_no = $this->getMaxId() + 10 + $offset;
            
            @$memd->set($id, $init_no);
            $tmp = $init_no;
        }
        
        if (is_int($tmp) || (is_numeric($tmp) && ($tmp = (int) $tmp))) {
            return $tmp;
        }
        
        return false;
    }
    
    /**
     * @return Com_Model_Mapping_User
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}


// End ^ Native EOL ^ encoding
