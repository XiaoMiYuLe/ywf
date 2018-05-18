<?php
/**
 * Playcool Project
 * 
 * LICENSE
 * 
 * http://www.playcool.com/license/ice
 * 
 * @category   ICE
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Jun 3, 2009
 * @version    SVN: $Id: Model.php 4849 2010-01-07 10:06:07Z woody $
 */

//Zeed::packageClass(array(
//        'Zend_Db_Table_Abstract',
//        'Zend_Db_Adapter_Abstract',
//        'Zend_Db_Adapter_Mysqli',
//        'Zend_Db',
//        'Zend_Config',
//        'Zend_Db_Select',
//        'Zend_Db_Profiler',
//        'Zend_Db_Expr',
//        'Zend_Db_Statement',
//        'Zend_Db_Statement_Interface',
//        'Zend_Db_Statement_Mysqli'), null, ZEED_IN_PRODUCTION);

/**
 * Abstract model class
 */

// 缓存表META数据
// Zend_Db_Table_Abstract::setDefaultMetadataCache(Zeed_Cache::instance());

class Fit_Db_Model extends Zend_Db_Table_Abstract
{
    /*
     * The table name.
     * 
     * @var string
     */
    protected $_name;
    
    /**
     * 数据库表前缀
     * 
     * @var string
     */
    protected $_prefix = '';
    
    /**
     * 数据库连接实例缓存
     * 
     * @var array
     */
    protected static $instanceInternalCache = array();
    
    /**
     * @param string $name Database table name
     */
    public function __construct($config = array())
    {
        /**
         * Allow a scalar argument to be the Adapter object or Registry key.
         * @see parent::__construct()
         */
        if (! is_array($config)) {
            $config = array(
                    self::ADAPTER => $config);
        }
        
        if ($config) {
            $this->setOptions($config);
        }
        
        if (! is_object($this->_db)) {
            $this->_db = Fit_Db::instance('default');
        }
    }
    
    protected function changeAdapter($name, $config = null)
    {
        $this->_db = Fit_Db::instance($name, $config);
        
        return $this;
    }
    
    /**
     * @param string $name
     * @return Fit_Db_Model
     */
    public function setTable($name)
    {
        $this->_name = (string) $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTable()
    {
        return $this->_name;
    }
    
    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }
    
    public function beginTransaction()
    {
        $this->_db->beginTransaction();
        return true;
    }
    
    public function commit()
    {
        $this->_db->commit();
        return true;
    }
    
    public function rollBack()
    {
        $this->_db->rollBack();
        return true;
    }
    
    /**
     * 由字段的KEY-VAL数组构建WHERE条件(AND连接).
     * 数组格式如下:
     * <code>
     * $rows = array('field1' = > '123', 'field2' => 'abc');
     * </code>
     * 该方法主要是用于兼容原Kohana中Database Query Builder的Where()
     *
     * @param Array|String $rows
     * @return String
     */
    public function batchWhere($rows)
    {
        if (is_array($rows) && count($rows) > 0) {
            $where = array();
            foreach ($rows as $k => $v) {
                $where[] = $this->getAdapter()->quoteIdentifier($k) . ' = \'' . (string) $v . '\'';
            }
            return implode(' AND ', $where);
        } elseif (is_string($rows)) {
            return $rows;
        }
        
        return '';
    }
    
    /**
     * 快速获取指定MODEL的实例
     * 
     * @param string $name Model Name.
     * @throws Zeed_Exception
     * @return Fit_Db_Model
     */
    public static function getModel($name)
    {
        return self::_instance($name);
    }
    
    /**
     * MODEL单列模式
     * 在子类中(注意注释中填写正确的返回值类型, 一般为类名):
     * <code>
     * public static function instance()
     * {
     *     return parent::_instance(__CLASS__);
     * }
     * </code>
     * 
     * @param string $model
     * @throws Zeed_Exception
     * @return Fit_Db_Model
     */
    protected static function _instance($model)
    {
        if (isset(self::$instanceInternalCache[$model]) && is_subclass_of(self::$instanceInternalCache[$model], __CLASS__)) {
            return self::$instanceInternalCache[$model];
        } elseif (class_exists($model)) {
            self::$instanceInternalCache[$model] = new $model();
            return self::$instanceInternalCache[$model];
        }
        
        throw new Zeed_Exception('Model( ' . $model . ' ) not exists.');
    }
}

// End ^ LF ^ UTF-8
