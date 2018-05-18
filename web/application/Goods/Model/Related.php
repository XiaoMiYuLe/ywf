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
class Goods_Model_Related extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'related';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'content_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'goods_';

    /**
     * 删除关联商品, 可指定删除某个(些), 不指定时删除所有关联商品
     *
     * @param integer $content_id
     * @param array|integer $related_content_id 指定删除的关联商品
     * @return integer
     */
    public function deleteByContentid($content_id, $related_content_id = null)
    {
        $where = $this->_db->quoteInto('content_id = ?', $content_id);
        if (is_null($related_content_id)) {
            return $this->delete($where);
        }
    
        $where .= ' AND related_content_id IN (' . implode(',', $related_content_id) . ')';
        return $this->delete($where);
    }
    
    /**
     * 根据商品id获取相关商品
     *
     * @param int $content_id
     * @return null
     */
    public function getRelatedByContentId($content_id = 0)
    {
        $where = '1=1';
        if($content_id){
            $where .= ' AND gr.content_id='.$content_id;
        }
        $select = $this->getAdapter()->select()->from($this->getTable().' AS gr');
        $select->joinLeft('goods_content AS gt','gr.related_content_id=gt.content_id');
        $select->where($where);
        $row = $select->query()->fetchAll();
        return $row ? $row :null;
    }
    
    /**
     * @return Goods_Model_Related
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
