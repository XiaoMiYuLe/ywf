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

class Bts_Model_Order_Attachment extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'order_attachment';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'bts_';
    

    /**
     * @return Media_Model_Attachment
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

    /**
     * 取得FILEPATH
     * @param unknown $to_id
     */
    public function getPath($to_id){//trend_attachment
    	$sql = "SELECT TA.filepath FROM {$this->getTable()} AS CA INNER JOIN trend_attachment AS TA ON CA.attachmentid = TA.attachmentid";
    	$sql .= " WHERE CA.to_id = :to_id";
    	$bind = array(':to_id' => $to_id);
    	return $this->query($sql, $bind)->fetchAll();
    }
    
}

// End ^ LF ^ encoding
