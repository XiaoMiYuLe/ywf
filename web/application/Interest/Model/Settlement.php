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
class Interest_Model_Settlement extends Zeed_Db_Model
{

    /**
     * @var string The table name.
     */
    protected $_name = 'settlement';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'interest_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'interest_';

    /**
     * @return Interest_Model_Settlement
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

}

// End ^ LF ^ encoding
