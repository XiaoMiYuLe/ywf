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

class Com_Model_User_Security extends Zeed_Db_Model_Detach
{
    /*
     * @var string The table name.
     */
    protected $_name = 'user_security';

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
    protected $_detachField = 'userid';
    protected $_skipCrc32 = true;

    /**
     * 获取用户安全信息
     *
     * @param BigInteger $userID
     * @param array|string $fields 参考 Zend_db
     *
     * @return array|null
     */
    public function getUserSecurity($userID, $fields = '*')
    {
        if (! is_numeric($userID)) {
            return null;
        }

        $userIntID = $userID;
        $this->detachToken($userIntID);

        $db = $this->getAdapter();
        $select = $db->select()->from($this->getTable(), $fields)->where('userid = ?', $userIntID)->limit(1);

        $row = $db->fetchRow($select);

        $result = null;

        if ((is_array($row) && count($row) > 0)) {
            if (! empty($row['qa'])) {
                $row['qa'] = @unserialize($row['qa']);
            }

            if (! empty($row['pwdcard'])) {
                $row['pwdcard'] = @unserialize($row['pwdcard']);
            }

            $result = $row;
        }

        return $result;
    }

    /**
     * 获取用户真实姓名以及身份证号
     *
     * @param BigInteger $userID
     * @return array|null
     * @see Com_Model_User_Security::getUserSecurity()
     */
    public function getCertify($userID)
    {
        return $this->getUserSecurity($userID, array(
                'userid',
                'realname',
                'idcard'));
    }

    /**
     * 获取用户当前密保卡信息
     *
     * @param BigInteger $userID
     * @return array|null
     * @see Com_Model_User_Security::getUserSecurity()
     */
    public function getPWCard($userID)
    {
        $pwdcard = $this->getUserSecurity($userID, array(
                'userid',
                'pwdcardid',
                'pwdcard',
                'pwdcardimg'));

        if (is_array($pwdcard)) {
            $pwdcard['pwdcardimg'] = base64_decode($pwdcard['pwdcardimg']);
        }

        return $pwdcard;
    }

    /**
     * 获取用户绑定密保卡信息
     *
     * @param BigInteger $userID
     * @param integer $pwdcardid
     * @return array|null
     */
    public function getBindPWCard($userID, $pwdcardid, $skip_imagebin = false)
    {
        return Com_Model_User_Security_Pwdcard::instance()->getUserSecurityPwdCard($userID, null, $pwdcardid, $skip_imagebin);
    }

    /**
     * 判断用户是否已绑定一张或多张密保卡
     *
     * @param BigInteger $userID
     * @return boolean
     */
    public function hasBindPWCard($userID)
    {
        $allBindPWCard = $this->getAllBindPWCard($userID, false);
        return (null === $allBindPWCard) ? false : true;
    }

    /**
     *
     * @param BigInteger $userID
     */
    public function getAllBindPWCard($userID, $skip_imagebin = false)
    {
        return Com_Model_User_Security_Pwdcard::instance()->getAllUserSecurityPwdCard($userID, $skip_imagebin);
    }

    /**
     * 移除、解绑密保卡
     *
     * @param BigInteger $userID
     * @param integer $pwdcardid
     * @return integer The number of affected rows.
     */
    public function unbindPWCard($userID, $pwdcardid)
    {
        return Com_Model_User_Security_Pwdcard::instance()->delete($userID, $pwdcardid);
    }

    /**
     * 更新当前用户密保卡信息
     *
     * @param BigInteger $userID
     * @param array $pwdCard
     * @param BIN $pwdCardImg
     * @return numeric|false 如果成功返回密保卡序列号，如果失败返回 false
     */
    public function updatePwdCard($userID, $pwdCardID, $pwdCard, $pwdCardImg)
    {
        $userSecurity = new Com_Entity_User_Security();
        $userSecurity->fromObject($userID);

        /**
         * $userID 不是一个数据集合，推断是 BigInteger
         */
        if ($userSecurity->isEmpty()) {
            $data = array(
                    'userid' => $userID,
                    'pwdcardid' => $pwdCardID,
                    'pwdcard' => $pwdCard,
                    'pwdcardimg' => $pwdCardImg);

            $userSecurity->fromArray($data);

            /**
             * 还是空？空的数据已被保存 ：）
             */
            if ($userSecurity->isEmpty()) {
                return true;
            }
        }

        $userIntID = $userSecurity->userid;
        $pwdCardID = $userSecurity->pwdcardid;
        $pwdCard = $this->filterPwdCardFiled($userSecurity->pwdcard);
        $pwdCardImg = base64_encode($userSecurity->pwdcardimg);

        if (! is_numeric($userIntID) || ! is_array($pwdCard)) {
            return false;
        }

        $db = $this->getAdapter();
        $userPwdCard = $this->getPWCard($userIntID);

        if ($userPwdCard) {
            $affects = $db->update($this->getTable(), array(
                    'pwdcardid' => $pwdCardID,
                    'pwdcard' => serialize($pwdCard),
                    'pwdcardimg' => $pwdCardImg), array(
                    'userid = ?' => $userIntID));
        } else {
            $affects = $db->insert($this->getTable(), array(
                    'userid' => $userIntID,
                    'pwdcardid' => $pwdCardID,
                    'pwdcard' => serialize($pwdCard),
                    'pwdcardimg' => $pwdCardImg));
        }

        if ($affects > 0) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param BigInteger $userID
     * @param Zeed_CC_Pwdcard $pwdCardID
     * @return numeric|null
     */
    public function getValidPwdcardid($userID, Zeed_CC_Pwdcard $pwdCardID)
    {
        if (! is_numeric($userID)) {
            return null;
        }

        $this->detachToken($userID);

        if (null === $pwdCardID->length) {
            $pwdCardID->length = 16;
        }

        $pwdCardID->prefix = $this->getDetachToken();

        $ccnumber_exist = false;
        $try_regen_ccnumber = 1;
        $max_regen_times = 5;

        $db = $this->getAdapter();

        /**
         * 检查密保卡序列号是否存在
         */
        do {
            if ($try_regen_ccnumber >= $max_regen_times) {
                return null;
            }

            $ccnumber = $pwdCardID->generate();

            $where = $db->quoteInto($db->quoteIdentifier('pwdcardid') . ' = ?', $ccnumber);
            $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE ' . $where . 'LIMIT 1';
            $row = $db->query($sql)->fetch();

            if ($row) {
                $ccnumber_exist = true;
                $ccnumber = null;
                $try_regen_ccnumber ++;
            }

        } while ($ccnumber_exist);

        return $ccnumber;
    }

    /**
     * 检查密保卡是否正确
     *
     * @param BigInteger $userID
     * @param numeric $pwdCardID 密保卡序列号
     * @param string $pwdCardWord 密保卡坐标对应密码
     * @param array $pwdCardXYPos 密保卡坐标
     * @param array $userPwdCard 用户密保卡信息
     * @return boolean
     */
    public function checkPwdCard($userID, $pwdCardID, $pwdCardWord, $pwdCardXYPos, $userPwdCard = null)
    {
        $pwdCardID = preg_replace('#\s+#', '', $pwdCardID);
        $pwdCardWord = strtolower($pwdCardWord);

        if (empty($pwdCardWord) || ! is_numeric($pwdCardID) || ! is_array($pwdCardXYPos)) {
            return false;
        }

        $pwdCardWord = strtolower($pwdCardWord);
        foreach ($pwdCardXYPos as $_id => $_xypos) {
            $pwdCardXYPos[$_id] = strtolower($_xypos);
        }

        /**
         * 读取用户未绑定密保卡信息
         */
        if ( null === $userPwdCard ) {
            $userPwdCard = $this->getPWCard($userID);
        }

        if (null !== $userPwdCard) {
            /**
             * 核对密保卡序列号
             */
            if ( strcmp($pwdCardID, $userPwdCard['pwdcardid']) === 0 ) {
                /**
                 * 对 KEY 进行反序填充
                 */
                foreach ($userPwdCard['pwdcard'] as $XYPos => $value) {
                    $startString = substr($XYPos, 1);
                    $endString = substr($XYPos, 0, 1);

                    $reversionString = $startString . $endString;
                    $userPwdCard['pwdcard'][$reversionString] = $value;
                }

                /**
                 * 核对密保卡内容
                 */
                $userPwdCardWords = array();

                foreach ($pwdCardXYPos as $_xypos) {
                    $userPwdCardWords[] = $userPwdCard['pwdcard'][$_xypos];
                }

                $userPwdCardWord = implode('', array_values($userPwdCardWords));
                if (strcmp($userPwdCardWord, $pwdCardWord) === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 绑定用户密保卡
     *
     * @param BigInteger $userID
     * @param integer $appID
     * @return boolean
     */
    public function bindPwdCard($userID, $appID) {
        if (null !== $appID) {
            $appID = (int) $appID;
        }

        $pwdCard = $this->getPWCard($userID);

        if ( $pwdCard ) {
            $data = $pwdCard;
            $data['appid'] = $appID;
            $data['btime'] = date('Y-m-d H:i:s');

            if (Com_Model_User_Security_Pwdcard::instance()->add($data)) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param BigInteger $userID
     * @return boolean
     */
    public function clearPwdCard($userID)
    {
        if ( ! is_numeric($userID)) {
            return false;
        }

        $userPwdCard = $this->getPWCard($userID);
        if ( $userPwdCard ) {
            $this->detachToken($userID);

            $affects = $this->getAdapter()->update($this->getTable(), array(
                    'pwdcardid' => null,
                    'pwdcard'   => null,
                    'pwdcardimg'=> null), array(
                    'userid = ?' => $userID));

            if ( 0 === $affects ) {
                return false;
            }
        }

        return true;
    }
    
    /**
     * 根据身份获取userid
     * 主要给后台客服人员使用 ，采有联合表查询，效力极低，前台请勿使用
     * 
     * @param string $idcard
     * @throw  Zeed_Exception
     * @return array | null
     */
    public function getUserByIdcard($idcard){
        $idcard = (string) trim($idcard);
        if (empty($idcard)) return null;
        //分表数
        $branchNum = 50;
        
        $unionSql = '';
        for($i = 0;$i < $branchNum;$i++)
        {
        	$branchCode = str_pad($i,2,'0',STR_PAD_LEFT);
            if ($i == 0){
            $unionSql .= sprintf('(SELECT `userid` FROM '. $this->getTable().
                                    "_%s WHERE idcard = %s)" ,$branchCode,$this->getAdapter()->quote($idcard));
            }
            else{
               $unionSql .= sprintf(' UNION (SELECT `userid` FROM '. $this->getTable().
                                    "_%s WHERE idcard = %s)" ,$branchCode,$this->getAdapter()->quote($idcard));
            }
        } 
        try {
             //echo $unionSql;
             $data = $this->getAdapter()->query($unionSql)->fetchAll();
        } catch (Exception $e) {
            throw new Zeed_Exception($e->getMessage());
        }
        return $data;
    }
    
    /**
     *
     *
     * @param array|Com_Entity_User_Security|BigInteger $userID
     * @param string $realname
     * @param string $idcard
     * @param boolean $freshAdd 新注册用户，无须检测是否已经存在
     * @return boolean 设置成功返回 true, 设置失败或无修改返回 false
     */
    public function setCertify($userID, $realname = null, $idcard = null, $freshAdd = false)
    {
        $userSecurity = new Com_Entity_User_Security();
        $userSecurity->fromObject($userID);

        /**
         * $userID 不是一个数据集合，推断是 BigInteger
         */
        if ($userSecurity->isEmpty()) {
            $data = array(
                    'userid' => $userID,
                    'realname' => $realname,
                    'idcard' => $idcard);

            $userSecurity->fromArray($data);

            /**
             * 还是空？空的数据已被保存 ：）
             */
            if ($userSecurity->isEmpty()) {
                return true;
            }
        }

        $userIntID = $userSecurity->userid;

        if (! is_numeric($userIntID)) {
            return false;
        }
        
        if ($freshAdd) {
            $currentCertify = null;
        } else {
            $currentCertify = $this->getCertify($userIntID);
        }
        $this->detachToken($userIntID);

        if ( null === $currentCertify) {
            $affects = $this->getAdapter()->insert($this->getTable(), array(
                'userid' => $userIntID,
                'realname' => $userSecurity->realname,
                'idcard' => $userSecurity->idcard));
            Com_Model_Mapping_Idcard2userid::instance()->add($userSecurity->idcard, $userIntID);
        } else {
            $affects = $this->getAdapter()->update($this->getTable(), array(
                    'realname' => $userSecurity->realname,
                    'idcard' => $userSecurity->idcard), array(
                    'userid = ?' => $userIntID));
            Com_Model_Mapping_Idcard2userid::instance()->updateIdcard($userSecurity->idcard, $userIntID, $currentCertify['idcard']);
        }

        if ($affects > 0) {
            return true;
        }

        return false;
    }

    /**
     * 获取用户安全问题
     *
     * @param BigInteger $userID
     * @return array|null
     * @see Com_Model_User_Security::getUserSecurity()
     */
    public function getQA($userID)
    {
        return $this->getUserSecurity($userID, array(
                'userid',
                'qa'));
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
        $currentQA = $this->getQA($userID);

        if (is_array($currentQA) && $currentQA['qa'] && count($currentQA['qa']) > 0) {
            return true;
        }

        return false;
    }

    /**
     * 检查安全问题是否正确
     *
     * @param BigInteger $userID
     * @param array $qa
     *
     * @return boolean
     */
    public function checkQA($userID, $qa = null)
    {
        $currentQA = $this->getQA($userID);

        if (is_array($currentQA) && count($currentQA['qa']) > 0) {
            unset($currentQA['qa']['question1'], $currentQA['qa']['question2']);

            if ($currentQA && $this->compareQA($qa, $currentQA['qa'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * 设置安全问题
     *
     * @param array|Com_Entity_User_Security|BigInteger $userID
     * @param array $qa
     * @return boolean
     */
    public function setQA($userID, $qa = null)
    {
        $userSecurity = new Com_Entity_User_Security();
        $userSecurity->fromObject($userID);

        /**
         * $userID 不是一个数据集合，推断是 BigInteger
         */
        if ($userSecurity->isEmpty()) {
            $data = array(
                    'userid' => $userID,
                    'qa' => $qa);

            $userSecurity->fromArray($data);

            /**
             * 还是空？空的数据已被保存 ：）
             */
            if ($userSecurity->isEmpty()) {
                return true;
            }
        }

        $userIntID = $userSecurity->userid;
        $qa = $userSecurity->qa;

        if (! is_numeric($userIntID) || ! is_array($qa)) {
            return false;
        }

        $update = $this->filterQAFiled($qa);

        /**
         * 缺少数据
         */
        if (4 != count($update)) {
            return false;
        }

        /**
         * 兼容数据库扩展性，使用程序检查记录是否已存在的
         */
        $currentQA = $this->getQA($userIntID);
        $this->detachToken($userIntID);

        if (null === $currentQA) {
            if ($this->getAdapter()->insert($this->getTable(), array(
                    'userid' => $userIntID,
                    'qa' => serialize($update)))) {
                return true;
            }
        } else {
            $this->getAdapter()->update($this->getTable(), array(
                    'qa' => serialize($update)), array(
                    'userid = ?' => $userIntID));
            return true;
        }

        return false;
    }

    private function compareQA($qa1, $qa2)
    {
        return count(array_diff_assoc($qa1, $qa2)) ? false : true;
    }

    /**
     * 过滤密保卡字段
     *
     * @param array $pwdCard
     * @return array
     */
    private function filterPwdCardFiled($pwdCard)
    {
        /**
         * 密保卡是一个矩阵卡，只有 X Y 轴，所以 KEY 应该是2位的
         * 密保卡大小写不敏感，所以全部使用小写
         */
        $card = array();

        foreach ($pwdCard as $field => $value) {
            if (strlen($field) == 2) {
                $field = strtolower($field);
                $field = $field[1].$field[0]; //1a 转换成a1
                $card[$field] = strtolower($value);
            }
        }

        return $card;
    }

    private function filterQAFiled($qa)
    {
        foreach ($qa as $field => $value) {
            switch ($field) {
                case 'question1' :
                case 'question2' :
                case 'answer1' :
                case 'answer2' :
                    break;

                default :
                    unset($qa[$field]);
            }
        }

        return $qa;
    }

    /**
     * @return Com_Model_User_Security
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
