<?php

/**
 * iNewS Project
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
class Bts_Model_Order_Items extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'order_items';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'item_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'bts_';

    /**
     * @return Bts_Model_Order_Items
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding

