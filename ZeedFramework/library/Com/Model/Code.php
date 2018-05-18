<?php
/**
 * iNewS Project
 *
 * LICENSE
 *
 * http://www.inews.com.cn/license/inews
 *
 * @category   Com
 * @package    Com_Model
 * @subpackage Com_Model_Code
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Nroe ( GTalk: gnroed@gmail.com )
 * @since      Apr 8, 2010
 * @version    SVN: $Id: User.php 5368 2010-06-22 02:33:51Z nroe $
 */

class Com_Model_Code extends Zeed_Db_Model
{
    const CODE_EXPIRED = 1;
    const CODE_AVAILABLE = 0;

    /*
     * @var string The table name.
     */
    protected $_name = 'code';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'code';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'os_';

    /**
     * 添加一个验证码
     *
     * @param Com_Entity_User|BigInteger $userID
     * @param Com_Entity_Code|false $code
     *
     * @throws Zeed_Exception
     */
    public function add($userID, Com_Entity_Code $code)
    {
        if ($userID instanceof Com_Entity_User) {
            $user = $userID;
        } else {
            $data = array(
                    'userid' => $userID);
            $user = Zeed_Object::instance()->Com_Entity_User()->fromArray($data);
        }

        $userIntID = $user->userid;

        $code->userid = $userIntID;
        $code->code = Zeed_Util_UUID::generate();
        $code->ctime = date('Y-m-d H:i:s', time());


        try {
            $this->insert($code->toArray());
            return $code;
        }
        catch (Exception $e) {
            throw new Zeed_Exception($e->getMessage());
        }

        return false;
    }

    /**
     * 判断验证码是否有效
     *
     * @param Com_Entity_Code $code
     * @param boolean $returnCode 如果设置为 true， 返回一个匹配的 Com_Entity_Code 对象
     *
     * @return boolean|Com_Entity_Code
     */
    public function isValid(Com_Entity_Code $code, $returnCode = true)
    {
        if ($code->isEmpty()) {
            return false;
        }

        if (empty($code->code) || $code->code instanceof Zeed_Object_Null) {
            return false;
        }

        $db = $this->getAdapter();
        $select = $db->select()->from($this->getTable())->where('code = ?', $code->code)->where('type = ?', $code->type)->limit(1);

        $mailConfig = Zeed_Config::loadGroup('mail');

        if ($mailConfig && $mailConfig['mail_code_lifetime'] > 0) {
            $select->where('ctime >= ?', date('Y-m-d H:i:s', time() - intval($mailConfig['mail_code_lifetime'])));
        }

        if (! $code->userid instanceof Zeed_Object_Null) {
            $select->where('userid = ?', $code->userid);
        }

        if (! $code->email instanceof Zeed_Object_Null) {
            $select->where('email = ?', $code->email);
        }

        $result = $db->fetchRow($select);

        if ($result) {
            if ($returnCode) {
                $code->fromArray($result);
                return $code;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * 删除一个验证码
     *
     * @param Com_Entity_Code $code
     * @param BigInteger $userID 如果设置则检测用户 userid
     * @param string $email 如果设置则检测邮箱地址
     */
    public function deleteCode(Com_Entity_Code $code)
    {
        if ($code->isEmpty()) {
            return true;
        }

        $deleteWhere = array(
                'type = ?' => $code->type);

        if (! empty($code->code) && ! $code->code instanceof Zeed_Object_Null) {
            $deleteWhere['code = ?'] = $code->code;
        }

        if (! $code->userid instanceof Zeed_Object_Null) {
            $deleteWhere['userid = ?'] = $code->userid;
        }

        if (! $code->email instanceof Zeed_Object_Null) {
            $deleteWhere['email = ?'] = $code->email;
        }

        return $this->getAdapter()->delete($this->getTable(), $deleteWhere);
    }

    /**
     * @return Com_Model_Code
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
