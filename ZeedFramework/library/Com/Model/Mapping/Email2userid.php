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

class Com_Model_Mapping_Email2userid extends Zeed_Db_Model_Detach {
    /*
     * @var string The table name.
     */
    protected $_name = 'mapping_email2userid';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'email';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'os_';

    /**
     * 定义分表依据字段
     *
     * @var $_detachField string
     */
    protected $_detachField = 'email';

    /**
     * 添加 Mapping 表信息
     *
     * @param array|Com_Entity_Mapping_Email2userid $set
     * @return integer|false 添加成功返回 userid 值，失败返回 false
     * @see Com_Model_User::add()
     */
    public function add($set)
    {
        $mappingEmail2userid = new Com_Entity_Mapping_Email2userid();
        $mappingEmail2userid->fromObject($set);

        if ($mappingEmail2userid->isEmpty()) {
            return false;
        }

        if (! is_numeric($mappingEmail2userid->userid)) {
            return false;
        }

        $data = $mappingEmail2userid->toArray();

        $this->detachToken($data);
        $name = $this->_name;
        $this->_name = $this->getTable();

        try {
            $email = $this->insert($data);
        }
        catch (Exception $e) {
            $email = false;
        }

        $this->_name = $name;
        return $email;
    }

    /**
     *
     * @param string $username
     * @return BigInteger|null 如果失败返回 null
     */
    public function getUseridByEmail($email)
    {
        $email = (string) $email;

        $this->detachToken($email);

        $userid = null;

        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('email') . " = ?", $email);
        $sql = 'SELECT userid FROM ' . $this->getTable() . ' WHERE ' . $where;
        $row = $this->getAdapter()->query($sql)->fetchColumn(0);
        if ($row && is_numeric($row)) {
            $userid = $row;
        }

        return $userid;
    }

    /**
     * @return Com_Model_Mapping_Email2userid
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}


// End ^ Native EOL ^ encoding
