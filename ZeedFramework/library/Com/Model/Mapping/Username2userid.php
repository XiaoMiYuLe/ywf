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

class Com_Model_Mapping_Username2userid extends Zeed_Db_Model_Detach
{
    /*
     * @var string The table name.
     */
    protected $_name = 'mapping_username2userid';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'userid';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'os_';

    /**
     * 定义分表依据字段
     *
     * @var $_detachField string
     */
    protected $_detachField = 'username';

    /**
     * 添加 Mapping 表信息
     *
     * @param array|Com_Entity_Mapping_Username2userid $set
     * @return integer|false 添加成功返回 userid 值，失败返回 false
     * @see Com_Model_User::add()
     */
    public function add($set)
    {
        $mappingUsername2userid = new Com_Entity_Mapping_Username2userid();
        $mappingUsername2userid->fromObject($set);
        if ($mappingUsername2userid->isEmpty()) {
            return false;
        }

        if (! is_numeric($mappingUsername2userid->userid)) {
            return false;
        }

        $data = $mappingUsername2userid->toArray();

        if ($data['userid'] > 0) {
            $userid= $data['userid'];
        }
        else {
            return false;
        }

        $this->detachToken($data);

        try {
            $this->insert($data);
        }
        catch (Exception $e) {
            $userid = false;
        }

        return $userid;
    }

    /**
     *
     * @param string $username
     * @return BigInteger|null 如果失败返回 null
     * @cached
     */
    public function getUseridByUsername($username, $domainid = 0)
    {
        $domainid = (int) $domainid;

        $hash = md5($username.$domainid);
        $cache = Zeed_Cache::instance();

        if ( ($data = $cache->load($hash)) && $data ) {
            return $data;
        }

        $this->detachToken($username);

        $userid = null;

        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('username') . " = ? AND domainid = {$domainid}", $username);
        $sql = 'SELECT userid FROM ' . $this->getTable() . ' WHERE ' . $where;
        $row = $this->getAdapter()->query($sql)->fetchColumn(0);
        if ($row && is_numeric($row)) {
            $cache->save($row, $hash);
            $userid = $row;
        }

        return $userid;
    }

    /**
     * 根据小写分表 username
     * 获取分表依据字段整型值，用于分表运算
     *
     * @param string $field
     * @return integer|null
     * @overwrite
     */
    protected function getDetcahFieldForMod($value)
    {
        $forMod = null;

        if (is_int($value)) {
            $forMod = $value;
        } elseif (is_string($value) && ! $this->_skipCrc32) {
            $value = mb_strtolower($value, 'utf-8');
            $checksum = crc32($value);
            $forMod = sprintf("%u", $checksum);
        } else {
            $forMod = $value;
        }

        return $forMod;
    }

    /**
     * @return Com_Model_Mapping_Username2userid
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}


// End ^ Native EOL ^ encoding
