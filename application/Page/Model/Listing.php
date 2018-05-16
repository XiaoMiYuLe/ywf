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

class Page_Model_Listing extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'listing';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'page_';
    
    /**
     * 根据分组ID和单页路径获取单条单页信息
     * 
     * @param integer $group_id
     * @param integer $page_folder
     * @return array|boolean
     */
    public function getPage($group_id, $page_folder)
    {
        $select = $this->select()->from($this->getTable())
                ->where("group_id = ?", $group_id)->where("page_folder = ?", $page_folder);
        $row = $select->query()->fetch();
        return $row ? $row : null;
    }
    
    /**
     * @return Page_Model_Listing
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8