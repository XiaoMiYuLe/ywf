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

class Goods_Model_Content_Trash extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'content_trash';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'content_id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'goods_';
    
    
    /**
     * @return Goods_Model_Content_Trash
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
