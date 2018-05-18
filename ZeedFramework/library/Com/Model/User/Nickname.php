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
 * @since      2010-8-19
 * @version    SVN: $Id$
 */

class Com_Model_User_Nickname extends Zeed_Db_Model_Detach
{
    /*
     * @var string The table name.
     */
    protected $_name = 'user_nickname';

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
     * @overwrite
     */
    protected $_detachField = 'nickname';

    /**
     * @param array $set
     * @return integer|false 添加成功返回 id 值，失败返回 false
     */
    public function add($set)
    {
        if (!is_array($set)) {
            $set = array( 'nickname' => $set );
        }

        try {
            $db = $this->getAdapter();
            $this->detachToken($set);
            $affected = $db->insert($this->getTable(), $set);

            if ($affected) {
                return $db->lastInsertId($this->getTable());
            }
        }
        catch (Exception $e) {
        }

        return false;
    }

    /**
     * 检查用户昵称是否已存在
     *
     * @param string $nickname
     * @return boolean
     */
    public function isNicknameExistent($nickname)
    {
        $nickname = (string) $nickname;
        $existent = false;

        $this->detachToken($nickname);

        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('nickname') . " = ?", $nickname);
        $sql = 'SELECT userid FROM ' . $this->getTable() . ' WHERE ' . $where;
        $row = $this->getAdapter()->query($sql)->fetchColumn(0);
        if ($row && is_numeric($row)) {
            $existent = true;
        }

        return $existent;
    }

    /**
     * 根据昵称得到userid
     * 主要给后台客服人员使用 ，采有联合表查询，效力极低，前台请勿使用
     * 
     * @param string $nickname
     * @throw  Zeed_Exception
     * @return array | null
     */
    public function getUserByNickname($nickname)
    {
        $nickname = (string) $nickname;
        $nicknameInfo = array();

        $this->detachToken($nickname);

        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('nickname') . " = ?", $nickname);
        $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE ' . $where;
        $row = $this->getAdapter()->query($sql)->fetch();
        if (is_array($row) && count($row) > 0) {
            $nicknameInfo[] = $row;
        }

        return $nicknameInfo;
    }
    
    
    /**
     * 根据小写分表 nickname
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
     * @return Com_Model_User_Nickname
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ Native EOL ^ encoding
