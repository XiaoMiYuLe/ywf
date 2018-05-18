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
class Brokerage_Model_Setting extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'setting';

    /**
     * @var integer Primary key.
     */
    protected $_primary = '';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'brokerage_';


    /**
     * @return Cas_Model_Code
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
