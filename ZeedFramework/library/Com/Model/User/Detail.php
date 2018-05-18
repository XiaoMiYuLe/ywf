<?php
/**
 * iNewS Project
 *
 * LICENSE
 *
 * http://www.inews.com.cn/license/inews
 *
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Apr 8, 2010
 * @version    SVN: $Id: User.php 5338 2010-06-18 06:13:35Z nroe $
 */

class Com_Model_User_Detail extends Zeed_Db_Model_Detach
{
    const VERIFICATION_EMAIL = 1;

    /*
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
    protected $_prefix = 'os_';

    /**
     * 定义分表依据字段
     *
     * @var $_detachField string
     * @overwrite
     */
    protected $_detachField = 'userid';
    protected $_skipCrc32 = true;

    /**
     * 添加用户详细信息
     *
     * @param array|Com_Model_User_Detail $set
     * @return integer|false 添加成功返回 userid 值，失败返回 false
     */
    public function add($set)
    {
        $userDetail = new Com_Entity_User_Detail();
        $userDetail->fromObject($set);

        if ($userDetail->isEmpty()) {
            return false;
        }

        if (! is_numeric($userDetail->userid)) {
            return false;
        }

        $data = $userDetail->toArray();

        $this->detachToken($data);
//        $name = $this->_name;
//        $this->_name = $this->getTable();

        try {
            $userid = $this->insert($data);
        }
        catch (Exception $e) {
            $userid = false;
        }

//        $this->_name = $name;
        return $userid;
    }

    /**
     * 保存用户详细信息
     *
     * @param array|Com_Model_User_Detail $set
     * @return boolean 当修改了数据返回 true, 无任何修改返回 false
     */
    public function save($set)
    {
        $userDetail = new Com_Entity_User_Detail();
        $userDetail->fromObject($set);

        if ($userDetail->isEmpty()) {
            return false;
        }

        if (! is_numeric($userDetail->userid)) {
            return false;
        }

        $data = $userDetail->toArray();
        $this->detachToken($data);
        unset($data['userid']);

        if (empty($data)) {
            return false;
        }
        
        if (is_null($this->getUserDetailByUserid($userDetail->userid))) {
            //insert
            $data['userid'] = $userDetail->userid;
            $this->insert($data);
        } else {
            $affects = $this->getAdapter()->update($this->getTable(), $data, array(
                'userid = ?' => $userDetail->userid));
            if ($affects > 0) {
                return true;
            }
        }
        

        return false;
    }

    /**
     * 判断用户是否验证过邮箱
     *
     * @param Com_Entity_User|BigInteger $userID
     * @return boolean
     */
    public function hasValidEmail($userID)
    {
        if ($userID instanceof Com_Entity_User) {
            $userIntID = $userID->userid;
        }
        else {
            $userIntID = $userID;
        }

        $userDetailInfo = $this->getUserDetailByUserid($userIntID);

        if ($userDetailInfo) {
            return $userDetailInfo['verifiedEmail'];
        }

        return false;
    }

    /**
     * 设置当前邮件验证状态
     *
     * @param Com_Entity_User|BigInteger $userID
     * @param boolean $flag
     * @return boolean
     */
    public function setVerificationEmail($userID, $flag)
    {
        if ($userID instanceof Com_Entity_User) {
            $userIntID = $userID->userid;
        }
        else {
            $userIntID = $userID;
        }

        if ( ! is_numeric($userIntID)) {
            return false;
        }
        
        $verifiedEmail = $flag ? '1' : '0';
        
        return $this->save(array('userid'=>$userIntID, 'verifiedEmail'=>$verifiedEmail));

        /*没有判断是否有DETAIL数据
        $this->detachToken($userIntID);

        $verifiedEmail = $flag ? '1' : '0';
        $effects = $this->getAdapter()->update($this->getTable(), array(
                'verifiedEmail' => $verifiedEmail), array(
                'userid = ?' => $userIntID));

        $cache = Zeed_Cache::instance();
        $cache->remove('userinfo_'.$userIntID);

        return $effects ? true : false;
        */
    }

    /**
     * 获取用户详细信息
     *
     * @param BigInteger $userid
     * @return array|null
     * @todo cache it
     */
    public function getUserDetailByUserid($userID)
    {
        if (! is_numeric($userID)) {
            return null;
        }

        $userIntID = $userID;
        $this->detachToken($userIntID);

        $db = $this->getAdapter();
        $select = $db->select()->from($this->getTable())->where('userid = ?', $userIntID)->limit(1);
        $row = $db->fetchRow($select);
        $result = null;

        if ($row) {
            $result = $row;
            /**
             * @var $verifiedEmail 邮箱验证标记
             * 针对 userdetail.verifiedEmail 进行判断，不去检查 user.email
             */
            $result['verifiedEmail'] = intval($result['verifiedEmail']) > 0 ? true : false;
        }

        return $result;
    }

    /**
     * 获取完整的用户信息，包括了 user、userdetail
     * 如果只需要获取基础 user 信息，使用 UserModel::instance()->getUserByUserid($userID);
     *
     * @param BigInteger $userID
     * @return array|null
     * @cached
     */
    public function getUserByUserid($userID)
    {
        if (is_null($userID) || (! is_numeric($userID) && ! is_int($userID))) {
            return null;
        }

        $userIntID = $userID;
        $hash = 'userinfo_'.$userIntID;
        $cache = Zeed_Cache::instance();

        if ( ($data = $cache->load($hash)) && $data ) {
            return $data;
        }

        $userInfo = UserModel::instance()->getUserByUserid($userIntID);

        if (null === $userInfo) {
            return null;
        }

        $userDetailInfo = $this->getUserDetailByUserid($userIntID);

        if (is_array($userDetailInfo)) {
            $userInfo = array_merge($userInfo, $userDetailInfo);
            $cache->save($userInfo, $hash);
        }

        return $userInfo;
    }

    /**
     * @return Com_Model_User_Detail
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
