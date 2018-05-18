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
class Cas_Model_BankConfig extends Zeed_Db_Model
{

    /**
     * @var string The table name.
     */
    protected $_name = 'bank_channel_config';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = '';

    /**
     * @return Cas_Model_Bank
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

}

// End ^ LF ^ encoding
