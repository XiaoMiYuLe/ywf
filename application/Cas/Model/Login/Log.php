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
 * @author     Ahdong ( GTalk: ahdong.com@gmail.com )
 * @since      May 20, 2010
 * @version    SVN: $Id: Log.php 10330 2011-05-16 10:05:06Z xsharp $
 */

class Cas_Model_Login_Log extends Zeed_Db_Model
{
    /**
     * The table name.
     * 
     * @var string
     */
    protected $_name = 'login_log';
    
    /**
     * 主键
     * 
     * @var String
     */
    protected $_primary = 'logid';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'cas_';
    
    
    /**
     * @return Cas_Model_Login_Log
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ Native EOL ^ encoding
