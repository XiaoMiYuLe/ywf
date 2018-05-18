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

class Com_Model_User_Security_Pwdcard extends Zeed_Db_Model_Detach
{
    /*
     * @var string The table name.
     */
    protected $_name = 'user_security_pwdcard';

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
     * 添加用户绑定密保卡
     *
     * @param array|Com_Model_User_Detail $set
     * @return integer|false 添加成功返回 userid 值，失败返回 false
     */
    public function add($set)
    {
        $userSecurityPwdcard = new Com_Entity_User_Security_Pwdcard();
        $userSecurityPwdcard->fromObject($set);

        if ($userSecurityPwdcard->isEmpty()) {
            return false;
        }

        if (! is_numeric($userSecurityPwdcard->userid)) {
            return false;
        }

        $data = $userSecurityPwdcard->toArray();
        if (!isset($data['appid'])) $data['appid'] = null;
        $userSecurityPwdcard = $this->getUserSecurityPwdCard($data['userid'], $data['appid']);

        /**
         * 该应用程序的密保卡已绑定
         */
        if ( $userSecurityPwdcard ) {
            return false;
        }

        $this->detachToken($data);

        try {
            if ( ! is_string($data['pwdcard'])) {
                $data['pwdcard'] = serialize($data['pwdcard']);
            }

            $this->insert($data);
            $userid = $data['userid'];
        } catch (Exception $e) {
            $userid = false;
        }

        return $userid;
    }

    /**
     * 判断用户是否有密保卡
     *
     * @param BigInter $userID 通行证帐号
     * @return boolean 如果用户绑定了一个或多个密保卡，返回 true
     */
    public function hasSecurityPwdCard($userID)
    {
        $securityPwdCard = $this->getAllUserSecurityPwdCard($userID);
        return null !== $securityPwdCard;
    }

    public function getAllUserSecurityPwdCard($userID, $skip_imagebin = false)
    {
        if (! is_numeric($userID)) {
            return null;
        }

        $userIntID = $userID;
        $this->detachToken($userIntID);

        $db = $this->getAdapter();
        $select = $db->select()->from($this->getTable())->where('userid = ?', $userIntID);

        $rows = $db->fetchAll($select);
        $result = null;

        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $id => $row) {
                if (! empty($row['pwdcard'])) {
                    if ($skip_imagebin) {
                        unset($rows[$id]['pwdcardimg']);
                    }
                    $rows[$id]['pwdcard'] = @unserialize($row['pwdcard']);
                }
            }

            $result = $rows;
        }

        return $result;
    }

    /**
     * 获取用户已绑定密保卡信息
     *
     * @param BigInteger $userID
     * @param Integer $appid
     * @param string $pwdcardid
     *
     * @return array|null
     */
    public function getUserSecurityPwdCard($userID, $appid = null, $pwdcardid = null, $skip_imagebin = false)
    {
        $pwdcardid = preg_replace('#\s+#', '', $pwdcardid);

        if (! is_numeric($userID)) {
            return null;
        }

        $userIntID = $userID;
        $this->detachToken($userIntID);

        $db = $this->getAdapter();
        $select = $db->select()->from($this->getTable())->where('userid = ?', $userIntID);

        if (null !== $appid) {
            $appid = (int) $appid;
            $select->where('appid = ?', $appid);
        }

        if (null !== $pwdcardid) {
            $select->where('pwdcardid = ?', $pwdcardid);
        }

        $row = $db->fetchRow($select->limit(1));
        $result = null;

        if (is_array($row)) {
            if (! empty($row['pwdcard'])) {
                $row['pwdcard'] = @unserialize($row['pwdcard']);
            }

            if ($skip_imagebin) {
                unset($row['pwdcardimg']);
            }

            $result = $row;
        }

        return $result;
    }

    /**
     * 清除用户所有已绑定密码卡
     *
     * @param BigInteger $userid
     * @return integer The number of affected rows.
     */
    public function deleteAll($userid)
    {
        $this->detachToken($userid);
        return $this->getAdapter()->delete($this->getTable(), array(
                'userid = ?' => $userid));
    }

    /**
     * 移除、解绑密保卡
     *
     * @param BigInteger $userid
     * @param string $pwdcardid
     * @return integer The number of affected rows.
     */
    public function delete($userid, $pwdcardid)
    {
        $pwdcardid = preg_replace('#\s+#', '', $pwdcardid);
        $this->detachToken($userid);
        return $this->getAdapter()->delete($this->getTable(), array(
                'userid = ?' => $userid,
                'pwdcardid = ?' => $pwdcardid));
    }

    /**
     * @return Com_Model_User_Security_Pwdcard
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
