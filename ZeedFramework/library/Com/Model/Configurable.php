<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Apr 12, 2010
 * @version    SVN: $Id: Configurable.php 5063 2010-04-28 09:02:48Z xsharp $
 */

/**
 * 常用的"配置型"数据表
 */
abstract class Com_Model_Configurable extends Zeed_Db_Model
{
    
    public function fetchRowByUniqueField($field, $value)
    {
    }
    
    public function fetchRowsAll()
    {
    }
    
    /**
     * 常用检测方法:
     * 1.检查数据最后修改时间(兼容性好)
     * 2.检查表属性(与数据库有关)
     * <code lang="mysql">
     * SHOW TABLE STATUS WHERE NAME='os_user'
     * </code>
     * 
     * @return boolean
     */
    abstract protected function cacheIsValid()
    {
    }
    
    protected function saveCache()
    {
    }
    
    protected function loadCache()
    {
    }
}

// End ^ LF ^ encoding
