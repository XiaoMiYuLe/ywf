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

class Article_Model_Content extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'content';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'content_id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'article_';
    

    /**
     * @return Article_Model_Content
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
    
    /**
     * 根据 parent_id 读取分类列表
     *
     * @param integer $parent_id
     * @return array
     */
    public function fetchByCategoryId($category = 0)
    {
    	$db = $this->getAdapter();
    	$where = $db->quoteInto('parent_id = ?', $parent_id);
    	$sql = 'SELECT *,(SELECT count(' . $this->_primary . ') FROM ' . $this->getTable() . ' AS c1 WHERE c1.parent_id=c2.category_id) AS hasSub FROM '
    			. $this->getTable() . ' AS c2 WHERE ' . $where . ' ORDER BY sort_order';
    	$rows = $db->query($sql)->fetchAll();
    	return is_array($rows) && count($rows) ? $rows : null;
    }
}

// End ^ LF ^ encoding
