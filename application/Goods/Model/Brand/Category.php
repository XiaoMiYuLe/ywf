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
class Goods_Model_Brand_Category extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'brand_category';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'brand_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'goods_';
    
    /**
     * 根据分类 ID 获取关联品牌信息
     * 
     * @param integer $category_id
     * @return array
     */
    public function getBrandByCategoryid($category_id)
    {
        $sql = "SELECT gbc.*, gb.brand_name FROM goods_brand_category AS gbc LEFT JOIN goods_brand AS gb ON gbc.brand_id = gb.brand_id 
                WHERE gbc.category_id = {$category_id}
                ORDER BY gbc.sort_order ASC";
        $rows = $this->getAdapter()->query($sql)->fetchAll();
        return $rows ? $rows : null;
    } 
    
    /**
     * @return Goods_Model_Brand_Category
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
