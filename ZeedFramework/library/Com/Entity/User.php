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
 * @version    SVN: $Id: User.php 9385 2011-01-18 10:05:05Z woody $
 */

class Com_Entity_User extends Zeed_Object_Ext
{
    public $uuid;
    public $userid;
    public $username;
    public $password;
    public $ipassword;
    public $freeze;
    public $salt;
    public $encrypt;
    public $domainid;
    public $email;
    public $nickname;
    public $gender;
    public $ctime;
    public $ban_etime;
    public $status;

    /**
     * 定义 EXT 扩展属性字段
     *
     * @todo 暂时不使用
     */
    private $_properties = array(
    );

    public function __construct()
    {
        parent::__construct();
        $this->setAllowedProperties($this->_properties);

        $this->setExtTable('userext');
        $this->setExtProcessHandle('Com_Model_User', 'saveExt');
    }
}

// End ^ LF ^ encoding
