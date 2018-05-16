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

class AppModel extends Com_Admin_Model_Group
{
    /**
     * @var string The table name.
     */
    protected $_name = 'app';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'appkey';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    /**
     * 获取所有应用
     * 
     * @return multitype:
     */
    public function getAllApps()
    {
        return $this->fetchAll(null, $this->_primary)->toArray();
    }
    
    /**
     * 添加应用
     * 
     * @param array $set
     * @return integer
     */
    public function addApp($set)
    {
        if ($set instanceof AppEntity) {
            $data = $set->toArray();
        } else {
            $entity = new AppEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->insert($data);
    }
    
    /**
     * 更新应用
     * 
     * @param array $set
     * @param string $appkey
     * @return integer
     */
    public function updateApp($set, $appkey)
    {
        if ($set instanceof AppEntity) {
            $data = $set->toArray();
        } else {
            $entity = new AppEntity();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->update($data, "appkey='{$appkey}'");
    }
    
    /**
     * 获取允许排版的模块
     * 
     * @param integer $if_compose
     * @return array|null
     */
    public function fetchByIfcompose($if_compose = 1)
    {
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('if_compose') . ' = ?', $if_compose);
        return $this->fetchAll($where, 'sort_order ASC')->toArray();
    }
    
    /**
     * @return AppModel
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8