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
class Cas_Model_User extends Zeed_Db_Model
{

    /**
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
    protected $_prefix = 'cas_';

    /**
     * 注册会员
     * 
     * @param array|Cas_Entity_User $set
     * @return void
     */
    public function add($set)
    {
        $this->beginTransaction();

        try {
            $userid = $this->addForEntity($set);
            $set['userid'] = $userid;
            Cas_Model_User_Detail::instance()->addForEntity($set);
            $this->commit();
            return $userid;
        } catch (Exception $e) {
            $this->rollBack();
            throw new Zeed_Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 修改会员消息
     * 
     * @param array|Cas_Entity_User $set
     * @return void
     */
    public function updateinfo($set, $id)
    {
        $this->beginTransaction();

        try {
            $this->updateForEntity($set, $id);
            Cas_Model_User_Detail::instance()->updateForEntity($set, $id);
            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw new Zeed_Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 更新会员资料
     * 
     * @param array|Cas_Entity_User $set
     * @return boolean 当修改了数据返回 true, 无任何修改返回 false
     */
    public function save($set)
    {
        if (!is_array($set) && !$set instanceof Cas_Entity_User) {
            throw new Zeed_Exception('$set must be array or instance of `Cas_Entity_User`.');
        }

        if ($set instanceof Cas_Entity_User) {
            $user = $set;
            $set = $user->toArray();
        } else {
            $user = new Cas_Entity_User();
            $user->fromObject($set);
        }

        if ($user->isEmpty()) {
            return false;
        }

        if (!is_numeric($user->userid)) {
            return false;
        }

        $data = $user->toArray();
        $this->beginTransaction();

        try {
            if (isset($data['userid'])) {
                unset($data['userid']);
            }

            if (!empty($data)) {
                $this->update($data, array('userid = ?' => (int) $user->userid));
            }

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw new Zeed_Exception($e->getMessage(), $e->getCode());
        }

        return false;
    }

    /**
     * 根据用户ID获取用户信息
     *
     * @param BigInteger $userid
     * @return array|null
     */
    public function getUserByUserid($userid)
    {
        if (!is_numeric($userid)) {
            return null;
        }

        $select = $this->getAdapter()->select()->from($this->getTable())->where('userid = ?', $userid);
        $result = $select->query()->fetch();
        return (is_array($result) && count($result) > 0) ? $result : null;
    }

    /**
     * 根据用户名获取用户信息
     *
     * @param string $username
     * @return array|null
     */
    public function getUserByUsername($username)
    {
        $select = $this->getAdapter()->select()->from($this->getTable())->where('username = ?', $username);
        $row = $select->query()->fetch();
        return (is_array($row) && count($row) > 0) ? $row : null;
    }

    /**
     * 根据第三方ID获取用户信息
     * 
     * @param string $thirdid
     */
    public function getUserByThirdid($thirdid)
    {
        $userid = Cas_Model_User_Flat::instance()->getUseridByThirdid($thirdid);
        if (null !== $userid) {
            return $this->getUserByUserid($userid);
        }

        return null;
    }

    /**
     * 检查用户名是否存在
     *
     * @param string $username
     * @return boolean
     */
    public function isUserExistent($username, $domainid = 0)
    {
        $user = $this->getUserByUsername($username, $domainid);
        return (is_array($user) && count($user)) ? true : false;
    }

    /**
     * 检查用户昵称是否已存在
     *
     * @param string $nickname
     * @return boolean
     *
     * @see Cas_Model_User_Nickname::isNicknameExistent()
     */
    public function isNicknameExistent($nickname)
    {
        $userid = Cas_Model_User_Flat::instance()->getUseridByNickname($nickname);
        return (is_numeric($userid)) ? true : false;
    }

    /**
     * 检查用户手机是否已存在
     *
     * @param string $phone
     * @return boolean
     */
    public function isPhoneExistent($phone)
    {
        $rows = $this->fetchByFV('phone', $phone);
        return (is_array($rows) && count($rows)) ? true : false;
    }

    /**
     * 判断用户是否设置安全问题
     *
     * @param BigInteger $userID
     *
     * @return boolean
     */
    public function issetQA($userID)
    {
        $qa = Cas_Model_User_Detail::instance()->getQA($userID);
        return (isset($qa['qa']) && is_array($qa['qa'])) ? true : false;
    }

    /**
     *
     * @param BigInteger $userID
     */
    public function getAllBindPWCard($userID, $skip_imagebin = false)
    {
        return null;
    }

    /**
     * 根据用户邮箱获取用户信息
     *
     * @param string $email
     * @return array|null
     *
     * @see Cas_Model_User_Detail::getUserByUserid();
     */
    public function getUserByEmail($email)
    {
        /**
         * 查询 mongodb
         */
        return null;
    }

    /**
     * 判断用户是否验证过邮箱
     *
     * @param Cas_Entity_User|BigInteger $userID
     * @return boolean
     * @see Cas_Model_User_Detail::hasValidEmail();
     */
    public function hasValidEmail($userID)
    {
        return Cas_Model_User_Detail::instance()->hasValidEmail($userID);
    }

    /**
     * 设置当前邮件验证状态
     *
     * @param Cas_Entity_User|BigInteger $userID
     * @param boolean $flag
     * @return boolean
     * @see Cas_Model_User_Detail::setVerificationEmail();
     */
    public function setVerificationEmail($userID, $flag)
    {
        return Cas_Model_User_Detail::instance()->setVerificationEmail($userID, $flag);
    }

    /**
     * 修改用户昵称
     *
     * @param BigInteger $userID
     * @param string $newNickname
     * @return boolean 如果修改成功了返回 true
     */
    public function modifyNickname($userID, $newNickname)
    {
        if (!is_numeric($userID)) {
            return false;
        }

        $userIntID = $userID;

        return $this->save(array(
                    'userid' => $userIntID,
                    'nickname' => $newNickname,
                    'uk_nickname' => mb_strtolower($newNickname)));
    }

    /**
     * 修改用户通行证
     * 
     * @param BigInteger $userID
     * @param string $newUsername
     * @return boolean 如果修改成功了返回 true
     */
    public function modifyUsername($userID, $newUsername)
    {
        if (!is_numeric($userID)) {
            return false;
        }

        $userIntID = $userID;

        return $this->save(array(
                    'userid' => $userIntID,
                    'username' => $newUsername,
                    'uk_username' => mb_strtolower($newUsername)));
    }

    /**
     * 修改用户密码
     *
     * @param BigInteger $userID
     * @param string $newPassword
     *
     * @return boolean 如果修改成功了返回 true
     */
    public function modifyPassword($userID, $newPassword = null)
    {
        if (!is_numeric($userID)) {
            return false;
        }

        $userIntID = $userID;
        $userInfo = $this->getUserByUserid($userIntID);

        if ($userInfo) {
            $encodeMode = $userInfo['encrypt'];
            $passwordSaltWord = $userInfo['salt'];
            $encodePassword = Zeed_Encrypt::encode($encodeMode, $newPassword, $passwordSaltWord);

            $userpasswordConfig = Zeed_Config::loadGroup('security.userpassword');
            $iPasswordAlgorithm = $userpasswordConfig['algorithm'];
            $iPasswordSalt = $userpasswordConfig['salt'];
            Zeed_Encrypt::encode($iPasswordAlgorithm, $newPassword, $iPasswordSalt);

            return $this->save(array(
                        'userid' => $userIntID,
                        'password' => $encodePassword));
        }

        return false;
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
        $userInfo = $this->getUserByUserid($userID);

        if (is_array($userInfo)) {
            $encodeMode = $userInfo['encrypt'];
            $passwordSaltWord = $userInfo['salt'];
            $currentPassword = $userInfo['password'];

            $encodePassword = Zeed_Encrypt::encode($encodeMode, $password, $passwordSaltWord, $encrypted);
            if (0 === strcmp($encodePassword, $currentPassword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 修改用户昵称
     *
     * @param BigInteger $userID
     * @param string $newRealname
     * @param string $newIdcard
     * @return boolean 如果修改成功了返回 true
     */
    public function modifyCertify($userID, $newRealname, $newIdcard)
    {
        if (!is_numeric($userID)) {
            return false;
        }

        $userIntID = $userID;

        return $this->save(array(
                    'userid' => $userIntID,
                    'realname' => $newRealname,
                    'idcard' => $newIdcard));
    }

    /**
     * 通过用户 ID 获取最后登录时间
     * 
     * @param integer $userid
     * @return DateTime
     */
    public function getLastLoginByUserid($userid)
    {
        $userid = (int) $userid;
        $row = $this->fetchByPK($userid);
        $lastLogin = $row ? $row[0]['last_login_time'] : null;
        return $lastLogin;
    }

    /**
     * 更新最后登录的相关信息
     *
     * @param integer $userid
     * @param string $time
     * @param string $ip
     * @param string $extra
     * @throws Zeed_Exception
     */
    public function updateLastLogin($userid, $time, $ip, $extra = null)
    {
        $userid = (int) $userid;
        $set = array('userid' => $userid, 'last_login_time' => $time, 'last_login_ip' => $ip, 'extra' => $extra);
        return $this->save($set);
    }

    /**
     * 通过用户邮箱、手机或帐号获取账户信息
     * 
     * @param string $username
     * @param string|boolean $type
     * @return array|boolean
     */
    public function fetchByUsernameType($username, $type = null)
    {
        if ($type == 'email') {
            $where = "email = '{$username}'";
        } elseif ($type == 'phone') {
            $where = "phone = '{$username}'";
        } elseif ($type == 'idcard') {
            $where = "idcard = '{$username}'";
        } else {
            $where = "username = '{$username}'";
        }
        $rows = $this->fetchByWhere($where);
        return $rows ? $rows[0] : null;
    }

    /**
     * 验证手机或邮箱是否被注册
     */
    public function verifyUserExistent($value, $type)
    {
        if ($type == 'email') {
            $where = "email = '{$value}'";
        }
        if ($type == 'phone') {
            $where = "phone = '{$value}'";
        }
        $select = $this->getAdapter()->select()->from($this->getTable())->where($where);
        $row = $select->query()->fetch();
        return (is_array($row) && count($row) > 0) ? $row : null;
    }
    /*查找3级上限*/
    public function fetchParents($userid){
        $sql = "SELECT  t2.userid as one, t3.userid as two,t4.userid as three
                FROM cas_user AS t1
                LEFT JOIN cas_user AS t2 ON t2.userid = t1.parent_id
                LEFT JOIN cas_user AS t3 ON t3.userid = t2.parent_id
                LEFT JOIN cas_user AS t4 ON t4.userid = t3.parent_id
                WHERE t1.userid = ".$userid;
        $row = $this->getAdapter()->query($sql)->fetchAll();
        return $row ? $row :null;
    }
    /**
     * @return Cas_Model_User
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

}

// End ^ LF ^ encoding
