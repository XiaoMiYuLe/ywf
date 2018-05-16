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
class Goods_Model_Attachment extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'attachment';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'goods_attachment_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'goods_';
    
    /**
     * 删除商品附件, 可指定删除某个(些), 不指定时删除该商品的所有附件
     *
     * @param integer $content_id
     * @param array|integer $attachmentid 指定删除的附件
     * @return integer
     */
    public function deleteByContentid($content_id, $attachmentid = null)
    {
        $where = $this->_db->quoteInto('content_id = ?', $content_id);
        if (is_null($attachmentid)) {
            return $this->delete($where);
        }
    
        $where .= ' AND attachmentid IN (' . implode(',', $attachmentid) . ')';
        return $this->delete($where);
    }

    /**
     * @return Goods_Model_Attachment
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
