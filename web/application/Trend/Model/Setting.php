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
class Trend_Model_Setting extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'setting';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'setting_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'trend_';
    
    /**
     * 获取所有系统设置信息
     */
    public function getAllSettings()
    {
        $rows = $this->getAdapter()->select()->from($this->getTable())
                ->where('store_id = ?', 0)->order("sort_order ASC")->query()->fetchAll();
        return $rows ? $rows : null;
    }

    /**
     * @return Trend_Model_Setting
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding

