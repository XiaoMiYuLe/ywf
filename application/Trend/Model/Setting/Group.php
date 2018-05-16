<?php

/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright  Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 9, 2010
 * @version    SVN: $Id$
 */
class Trend_Model_Setting_Group extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'setting_group';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'group_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'trend_';
    

    /**
     * @return Trend_Model_Setting_Group
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding

