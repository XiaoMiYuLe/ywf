<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-3-21
 * @version    SVN: $Id$
 */

class Promotion_Model_Category extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'category';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'category_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'promotion_';
    
    /**
     * 获取所有分类 - 下拉选项型
     */
    public function getAllCategoriesForSelect()
    {
        $order = "hid ASC";
        $categories = $this->fetchByWhere(null, $order, 100);
        if (! empty($categories)) {
            $categories = $this->doCategories($categories);
        }
        return $categories;
    }
    
    /**
     * 获取所有分类 - 供列表模式使用
     */
    public function getAllCategoriesForListing($where = null, $order = null, $count = null, $offset = null, $cols = null)
    {
        $categories = $this->fetchByWhere($where, $order, $count, $offset, $cols);
        if (! empty($categories)) {
          $categories = $this->doCategories($categories);
        }
        return $categories;
    }
    
    
    private function doCategories($categories)
    {
        $nv = array();
        foreach ($categories as &$v) {
            $str_padding = '';
            if ($v['parent_id'] == 0) {
                $v['parent_name'] = '顶级分类';
            } else {
                if (! isset($nv[$v['parent_id']])) {
                    if ($title = $this->fetchByPK($v['parent_id'], 'title')) {
                        $nv[$v['parent_id']] = $v['parent_name'] = $title[0]['title'];
                    }
                } else {
                    $v['parent_name'] = $nv[$v['parent_id']];
                }
            }
        
            $level = count(explode(':', $v['hid'])) - 2;
            if ($level) {
                for ($i = 0; $i < $level; $i++) {
                    $str_padding .= "&nbsp;&nbsp;&nbsp;&nbsp;";
                }
            }
            $v['str_padding'] = $str_padding;
        }
        
        return $categories;
    }
    /**
     * @return Promotion_Model_Category
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8