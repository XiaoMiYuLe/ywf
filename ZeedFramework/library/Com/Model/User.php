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
 * @version    SVN: $Id: User.php 8864 2010-12-08 08:45:59Z woody $
 */

class Com_Model_User extends Zeed_Db_Model_Detach
{
    /*
     * @var string The table name.
     */
    protected $_name = 'user';

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
     * @param array|Com_Entity_User $set
     * @return integer|false 添加成功返回 userid 值，失败返回 false
     * @throws Zeed_Exception
     */
    public function add($set)
    {
        $user = new Com_Entity_User();
        $user->fromObject($set);

        if ($set instanceof Com_Entity_User) {
            $userDetailData = array();
        } else {
            $userDetailData = $set;
        }

        if ($user->isEmpty()) {
            return false;
        }

        $data = $user->toArray();
        unset($data['nickname']);

        /**
         * 开启事务...
         */
        $this->beginTransaction();

        try {
            $data['userid'] = (int) Com_Model_Mapping_User::instance()->getNewUserid();
            if (0 >= $data['userid']) {
                throw new Zeed_Exception('can not get user unique id.');
            }
            
            $userid = Com_Model_Mapping_User::instance()->add($data);

            if (! $userid) {
                throw new Zeed_Exception('mapping user insert error.');
            }

            $data['userid'] = $userid;

            $username2userid = Com_Model_Mapping_Username2userid::instance()->add($data);

            if (! $username2userid) {
                throw new Zeed_Exception('mapping user insert error.');
            }

            $this->detachToken($data);
//            $name = $this->_name;
//            $this->_name = $this->getTable();

            $this->insert($data);

    //        if (isset($data['propertycache'])) {
    //            $user->userid = $userid;
    //            $user->saveExt();
    //        }

            $userDetailData['userid'] = $userid;
            //$userDetail = Com_Model_User_Detail::instance()->add($userDetailData);

            if (isset($userDetailData['realname']) || isset($userDetailData['idcard'])) {
                $realname = isset($userDetailData['realname']) ? $userDetailData['realname'] : '';
                $idcard = isset($userDetailData['idcard']) ? $userDetailData['idcard'] : '';
                Com_Model_User_Security::instance()->setCertify($userid, $realname, $idcard, true);
            }

            /**
             * 用户详细信息没有设置，可能是被允许的
             */
//            if (! $userDetail) {
//                throw new Zeed_Exception('user detail insert error.');
//            }

            $this->commit();
        }
        catch (Exception $e) {
            Zeed_Log::instance()->log('register user failed, message: '. $e->getMessage(), Zeed_Log::WARN);
            $this->rollBack();
            $userid = false;
        }

        return $userid;
    }

    /**
     * @param array|Com_Entity_User $set
     * @return boolean 当修改了数据返回 true, 无任何修改返回 false
     */
    public function save($set)
    {
        $user = new Com_Entity_User();
        $user->fromObject($set);

        if ($set instanceof Com_Entity_User) {
            $userDetailData = array();
        } else {
            $userDetailData = $set;
        }

        if ($user->isEmpty()) {
            return false;
        }

        if (empty($user->userid) || $user->userid instanceof Zeed_Object_Null) {
            return false;
        }

        $data = $user->toArray();
        $this->detachToken($data);
        unset($data['userid'], $data['properties']);
        $affects = $this->getAdapter()->update($this->getTable(), $data, array(
                'userid = ?' => $user->userid));

        /**
         * @todo 更新 Ext 扩展表
         */

        $userDetailStatus = Com_Model_User_Detail::instance()->save($userDetailData);

        if ($affects > 0 || $userDetailStatus) {
            $cache = Zeed_Cache::instance();
            $cache->remove('userinfo_'.$user->userid);
            return true;
        }

        return false;
    }

    /**
     * 保存用户扩展属性
     *
     * @param array $extSet
     * @return void
     */
    public function saveExt(Zeed_Object_Ext $user)
    {
        if (count($_exts = $user->getExtProperties())) {
            foreach ($_exts as $f => $v) {
                $set = array();
                $set['userid'] = $user->userid;
                $set['propertyname'] = $f;
                $set['propertyvalue'] = $v;

                $this->getAdapter()->insert($user->getExtTable($this->_prefix), $set);
            }
        }
    }

    /**
     * 根据用户ID获取用户信息
     *
     * @param BigInteger $userid
     * @return array|null
     */
    public function getUserByUserid($userid)
    {
        if (is_null($userid) || (! is_numeric($userid) && ! is_int($userid))) {
            return null;
        }

        $this->detachToken($userid);

        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('userid') . ' = ?', $userid);
        $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE ' . $where;
        $row = $this->getAdapter()->query($sql)->fetch();

        return (is_array($row) && count($row) > 0) ? $row : null;
    }

    /**
     * 根据用户UUID获取用户信息
     *
     * @param BigInteger $userid
     * @return array|null
     */
    public function getUserByUuid($uuid)
    {
        return null;
    }

    /**
     * 根据用户名获取用户信息
     *
     * @param string $username
     * @param integer $domainid
     * @return array|null
     *
     * @see Com_Model_User_Detail::getUserByUserid();
     */
    public function getUserByUsername($username, $domainid = 0)
    {
        $userid = Com_Model_Mapping_Username2userid::instance()->getUseridByUsername($username, $domainid);
        return Com_Model_User_Detail::instance()->getUserByUserid($userid);
    }


    /**
     * 检查用户名是否存在
     *
     * @param string $username
     * @return boolean
     */
    public function isUserExistent($username, $domainid = 0)
    {
        $userid = Com_Model_Mapping_Username2userid::instance()->getUseridByUsername($username, $domainid);
        if (!empty($userid)) {
            return true;
        }
        return false;
    }

    /**
     * 检查用户昵称是否已存在
     *
     * @param string $nickname
     * @return boolean
     *
     * @see Com_Model_User_Nickname::isNicknameExistent()
     */
    public function isNicknameExistent($nickname)
    {
        return Com_Model_User_Nickname::instance()->isNicknameExistent($nickname);
    }

    /**
     * 根据用户邮箱获取用户信息
     *
     * @param string $email
     * @return array|null
     *
     * @see Com_Model_User_Detail::getUserByUserid();
     */
    public function getUserByEmail($email)
    {
        $userid = Com_Model_Mapping_Email2userid::instance()->getUseridByEmail($email);
        return Com_Model_User_Detail::instance()->getUserByUserid($userid);
    }

    /**
     * 判断用户是否验证过邮箱
     *
     * @param Com_Entity_User|BigInteger $userID
     * @return boolean
     * @see UserDetailModel::hasValidEmail();
     */
    public function hasValidEmail($userID)
    {
        return UserDetailModel::instance()->hasValidEmail($userID);
    }

    /**
     * 设置当前邮件验证状态
     *
     * @param Com_Entity_User|BigInteger $userID
     * @param boolean $flag
     * @return boolean
     * @see UserDetailModel::setVerificationEmail();
     */
    public function setVerificationEmail($userID, $flag)
    {
        return UserDetailModel::instance()->setVerificationEmail($userID, $flag);
    }

    /**
     * 修改用户昵称
     *
     * @param array|Com_Entity_User|BigInteger $userID
     * @param string $newNickname
     *
     * @return boolean 如果修改成功了返回 true, 如果密码修改失败返回 false，如果昵称没有被修改（和上次昵称相同）返回 false
     */
    public function modifyNickname($userID, $newNickname = null)
    {
        $user = new Com_Entity_User();
        $user->fromObject($userID);

        /**
         * $userID 不是一个数据集合，推断是 BigInteger
         */
        if ($user->isEmpty()) {
            $data = array(
                    'userid' => $userID,
                    'nickname' => $newNickname);

            $user->fromArray($data);

            /**
             * 还是空？空的数据已被保存 ：）
             */
            if ($user->isEmpty()) {
                return true;
            }
        }

        $userIntID = $user->userid;
        $newNickname = $user->nickname;

        if (! is_numeric($userIntID)) {
            return false;
        }

        $this->detachToken($userIntID);

        try {
            $db = $this->getAdapter();
            $affected = $db->update($this->getTable(), array(
                    'nickname' => $newNickname), array(
                    'userid = ?' => $userIntID));

            if ($affected) {
                Com_Model_User_Nickname::instance()->add(array('userid' => $userIntID, 'nickname' => $newNickname));

                $cache = Zeed_Cache::instance();
                $cache->remove('userinfo_'.$userIntID);
                return true;
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }

        return false;
    }

    /**
     * 修改用户密码
     *
     * @param array|Com_Entity_User|BigInteger $userID
     * @param string $newPassword
     *
     * @return boolean 如果修改成功了返回 true, 如果密码修改失败返回 false，如果密码没有被修改（和上次密码相同）返回 false
     */
    public function modifyPassword($userID, $newPassword = null)
    {
        $user = new Com_Entity_User();
        $user->fromObject($userID);

        /**
         * $userID 不是一个数据集合，推断是 BigInteger
         */
        if ($user->isEmpty()) {
            $data = array(
                    'userid' => $userID,
                    'password' => $newPassword);

            $user->fromArray($data);

            /**
             * 还是空？空的数据已被保存 ：）
             */
            if ($user->isEmpty()) {
                return true;
            }
        }

        $userIntID = $user->userid;
        $newPassword = $user->password;

        if (! is_numeric($userIntID)) {
            return false;
        }

        $this->detachToken($userIntID);
        $userInfo = $this->getUserByUserid($userIntID);

        if ($userInfo) {
            $encodeMode = $userInfo['encrypt'];
            $passwordSaltWord = $userInfo['salt'];

            $encodePassword = Zeed_Encrypt::encode($encodeMode, $newPassword, $passwordSaltWord);

            $userpasswordConfig = Zeed_Config::loadGroup('security.userpassword');
            $iPassword = Zeed_Encrypt::encode($userpasswordConfig['algorithm'], $newPassword, $userpasswordConfig['salt']);

            $db = $this->getAdapter();
            $affected = $db->update($this->getTable(), array(
                    'password' => $encodePassword,
                    'ipassword' => $iPassword), array(
                    'userid = ?' => $userIntID));

            if ($affected) {
                $cache = Zeed_Cache::instance();
                $cache->remove('userinfo_'.$userIntID);
                return true;
            }
        }

        return false;
    }

    /**
     * 修改用户密码
     * 注意：该方法已被放弃，请使用 modifyPassword() 代替
     *
     * @param array|Com_Entity_User|BigInteger $userID
     * @param string $newPassword
     *
     * @return boolean
     */
    public function setPassword($userID, $newPassword = null)
    {
        return $this->modifyPassword($userID, $newPassword);
    }

    /**
     * 检查用户密码是否正确
     *
     * @param BigInteger $userID
     * @param string $password
     *
     * @return boolean
     */
    public function checkPassword($userID, $password = null, $encrypted = false)
    {
        if (! is_numeric($userID)) {
            return false;
        }

        $userInfo = Com_Model_User_Detail::instance()->getUserByUserid($userID);
        if (null !== $userInfo) {
            $encodeMode = $userInfo['encrypt'];
            $passwordSaltWord = $userInfo['salt'];
            $currentPassword = $userInfo['password'];

            $encodePassword = Zeed_Encrypt::encode($encodeMode, $password, $passwordSaltWord, $encrypted);

            if (0 == strcmp($encodePassword, $currentPassword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Com_Model_User
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
