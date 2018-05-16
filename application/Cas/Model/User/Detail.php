<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Cas
 * @package    Cas_Model
 * @subpackage Cas_Model_User
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @version    SVN: $Id$
 */

class Cas_Model_User_Detail extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'user_detail';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'userid';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'cas_';
    
    /**
     * 保存用户详细信息
     *
     * @param array|Cas_Model_User_Detail $set
     * @return boolean 当修改了数据返回 true, 无任何修改返回 false
     */
    public function save($set)
    {
        if (! is_array($set) && ! $set instanceof Cas_Entity_User_Detail) {
            throw new Zeed_Exception('$set must be  array or instance of `Cas_Entity_User_Detail`.');
        }
    
        $userDetail = new Cas_Entity_User_Detail();
        $userDetail->fromObject($set);
    
        if ($userDetail->isEmpty() || ! is_numeric($userDetail->userid)) {
            return false;
        }
    
        $data = $userDetail->toArray();
    
        if (is_null($this->getUserDetailByUserid($userDetail->userid))) {
            $this->insert($data);
            return true;
        } else {
            if (isset($data['userid'])) {
                unset($data['userid']);
            }
    
            if (empty($data)) {
                return false;
            }
    
            $affects = $this->update($data, array('userid = ?' => $userDetail->userid));
            if ($affects > 0) {
                return true;
            }
        }
    
        return false;
    }
    
    /**
     * 获取用户详细信息
     *
     * @param BigInteger $userid
     * @param array|string $fields 参考 Zend_db
     * @return array|null
     * @todo cache it
     */
    public function getUserDetailByUserid($userID, $fields = '*')
    {
        if (! is_numeric($userID)) {
            return null;
        }
    
        $userIntID = $userID;
    
        $select = $this->getAdapter()->select()->from($this->getTable(), $fields)->where('userid = ?', $userIntID)->limit(1);
        $row = $select->query()->fetch();
        $result = null;
    
        if (is_array($row) && count($row)) {
            $result = $row;
            /**
             * @var $verifiedEmail 邮箱验证标记
             * 针对 userdetail.verifiedEmail 进行判断，不去检查 user.email
             */
            if (isset($result['verifiedEmail'])) {
                $result['verifiedEmail'] = intval($result['verifiedEmail']) > 0 ? true : false;
            }
    
            if (isset($result['qa']) && strlen($result['qa'])) {
                $result['qa'] = @unserialize($result['qa']);
            }
        }
    
        return $result;
    }
    
    /**
     * @return Cas_Model_User_Detail
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
