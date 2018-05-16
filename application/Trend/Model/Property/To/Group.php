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

class Trend_Model_Property_To_Group extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'property_to_group';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'property_id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'trend_';
    
    /**
     * 通过属性关联关系查询属性信息
     * 
     * @param string|array $property_ids
     * @return boolean|array
     */
    public function getPropertyInfo($property_group_id, $property_ids = '')
    {
        if (is_array($property_ids) && count($property_ids)) {
            $property_ids = implode(',', $property_ids);
        }
        
        $sql = "SELECT * FROM `trend_property_to_group` AS tptg LEFT JOIN trend_property AS tp" . 
                " ON tptg.property_id = tp.property_id" . 
                " WHERE tptg.property_group_id = {$property_group_id}";
        
        if ($property_ids) {
            $sql .= " AND tptg.property_id IN ({$property_ids}) ";
        }
        
        $sql .= " ORDER BY tptg.sort_order ASC";
        
        $rows = $this->getAdapter()->fetchAll($sql);
        
        return is_array($rows) && count($rows) ? $rows : null;
    }
    
    /**
     * @return Trend_Model_Property_To_Group
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
