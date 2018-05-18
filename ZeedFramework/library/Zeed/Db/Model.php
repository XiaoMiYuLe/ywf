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
 * @version    SVN: $Id: Model.php 13233 2012-07-16 10:22:27Z xsharp $
 */

/**
 * Abstract model class
 */
class Zeed_Db_Model extends Zend_Db_Table_Abstract
{
    /**
     * 完整的表名
     *
     * @var string
     */
    protected $_name = null;
    
    /**
     * 主键
     *
     * @var String
     */
    protected $_primary = null;
    
    /**
     * 数据库表前缀(系统内部使用)
     *
     * @var string
     */
    protected $_prefix = null;
    
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
            $config = array(parent::ADAPTER => $config);
        }
        
        if ($config) {
            $this->setOptions($config);
        }
        
        if (! is_object($this->_db)) {
            $this->_db = Zeed_Db::instance('default', $config);
            if (Zeed_Db::$metaCacheEnable) {
                Zend_Db_Table_Abstract::setDefaultMetadataCache(Zeed_Cache::instance());
            }
        }
        
        if ($this->_prefix != '') {
            $this->_name = $this->_prefix . $this->_name;
        }
        
        // Global table prefix, should defined in application/APPNAME/config/database.php or config/bootstrap.php
        if (defined('ZEED_DB_TABLEPREFIX'))
            $this->_name = ZEED_DB_TABLEPREFIX . $this->_name;
    }
    
    /**
     * 切换数据库连接
     * 
     * @param string $name
     * @param array $config
     * @return Zeed_Db_Model
     */
    public function changeAdapter($name, $config = null)
    {
        $this->_db = Zeed_Db::instance($name, $config);
        
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see Zend_Db_Table_Abstract::insert()
     */
    public function insert(array $data)
    {
        $this->_db->insert($this->getTable(), $data);
        return $this->_db->lastInsertId($this->getTable(), $this->_primary);
    }
    
    /**
     * (non-PHPdoc)
     * @see Zend_Db_Table_Abstract::update()
     */
    public function update(array $data, $where)
    {
        return $this->_db->update($this->getTable(), $data, $where);
    }
    
    /**
     * @param string $name
     * @return Zeed_Db_Model
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
     * 内部表前缀
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }
    
    /**
     * Leave autocommit mode and begin a transaction.
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function beginTransaction()
    {
        $this->_db->beginTransaction();
        return true;
    }
    
    /**
     * Commit a transaction and return to autocommit mode.
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function commit()
    {
        $this->_db->commit();
        return true;
    }
    
    /**
     * Roll back a transaction and return to autocommit mode.
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function rollBack()
    {
        $this->_db->rollBack();
        return true;
    }
    
    /**
     * Prepares and executes an SQL statement with bound data.
     *
     * @param  mixed  $sql  The SQL statement with placeholders.
     * May be a string or Zend_Db_Select.
     * @param  mixed  $bind An array of data to bind to the placeholders.
     * @return Zend_Db_Statement_Interface
     */
    public function query($sql, $bind)
    {
        return $this->_db->query($sql, $bind);
    }
    
    /**
     * 检查当前数据库中是否存在指定表
     * 
     * @param string $tablename
     * @return boolean
     */
    public function isExistDB($tablename = null)
    {
        if (null === $tablename) {
            $tablename = $this->_name;
        }
        
        $sql = $this->_db->quoteInto('SHOW TABLES LIKE ?', $tablename);
        $row = $this->_db->query($sql)->fetchColumn(0);
        
        if ($row && $row == $tablename) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 移动记录到指定表内
     * 
     * 注意：最好在使用时，使用事务
     * 
     * @param string $where
     * @param string $sourceTable
     * @param string $targetTable
     * @return integer|false 成功移动返回影响行数，数据未找到返回 false
     * @throws Zeed_Exception
     */
    public function moveRecord($where, $source, $dest = null)
    {
        $source = (string) $source;
        if (null === $dest) {
            $dest = $source . '_history';
        }
        
        $selectSql = "SELECT * FROM {$source} WHERE {$where} FOR UPDATE";
        $data = $this->_db->fetchAll($selectSql);
        
        if (is_array($data) && count($data)) {
            try {
                $copySql = "INSERT INTO {$dest} SELECT * FROM {$source} WHERE {$where}";
                $insertAffected = $this->_db->query($copySql)->rowCount();
                if (! $insertAffected) {
                    throw new Zeed_Exception("copy record from {$source} to {$dest} failed, no affected.");
                }
                
                $deleteSql = "DELETE FROM {$source} WHERE {$where}";
                $delectAffected = $this->_db->query($deleteSql)->rowCount();
                if (! $delectAffected) {
                    throw new Zeed_Exception("delete source $source record failed, no affected.");
                }
                
                return $insertAffected;
            } catch (Zend_Db_Exception $e) {
                $eMsg = $e->getMessage();
                
                if (strpos($eMsg, '42S02') !== false || strpos($eMsg, 'Base table or view not found') !== false) {
                    $this->copyTable($source, $dest);
                    return $this->moveRecord($where, $source, $dest);
                } else {
                    throw new Zeed_Exception($eMsg, 8);
                }
            }
        }
        
        return false;
    }
    
    /**
     * 同数据库拷贝表
     *
     * @param string $source
     * @param string $dest
     * @return boolean
     */
    public function copyTable($source, $dest)
    {
        $sourceTable = $this->_db->quoteIdentifier($source);
        $destTable = $this->_db->quoteIdentifier($dest);
        
        $sql = "CREATE TABLE {$destTable} LIKE {$sourceTable}";
        $row = $this->_db->query($sql);
        
        if ($row) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 根据MODEL中的定义PRIMARYKEY获取一行(一维)数据, 支持联合主键, 基本同parent::find()功能
     *
     * @todo 联合主键时, 对代入的参数做进一步匹配检测
     * @param integer|string|array $pkVal 当为联合主键时代入按主键字段顺序的数组
     * @param array|string         $cols  指定获取的字段
     * @return array 二维数组
     */
    public function fetchByPK($pkVal, $cols = null)
    {
        if (is_null($cols)) {
            $cols = '*';
        }
        
        if (empty($this->_primary)) {
            throw new Zeed_Exception('No primary key defiend.');
        }
        if (empty($this->_name)) {
            throw new Zeed_Exception('Table name not defiend.');
        }
        if (is_string($this->_primary)) {
            if (is_array($pkVal)) {
                $where = $this->_primary . ' IN (\'' . implode('\',\'', $pkVal) . '\')';
            } else {
                $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $pkVal);
            }
            $rows = $this->getAdapter()->select()->from($this->getTable(), $cols)->where($where)->query()->fetchAll();
        } elseif (is_array($this->_primary)) {
            $select = $this->getAdapter()->select()->from($this->getTable(), $cols);
            
            if (is_string($pkVal) || is_int($pkVal)) {
                $pkVal = array(0 => $pkVal); // @see Zend_Db_Table_Abstract::_setupPrimaryKey()
            }
            
            foreach ($pkVal as $k => $v) {
                if (is_array($v) && count($v) == count($this->_primary)) {
                    throw new Zeed_Exception('Multiple rows fetching by multi-column primary key NOT supported .');
                }
                if (! is_null($v)) {
                    $where = $this->getAdapter()->quoteInto($this->_primary[$k] . ' = ?', $v);
                    $select->where($where);
                }
            }
            
            $rows = $select->query()->fetchAll();
        } else {
            return null;
        }
        
        return is_array($rows) && ! empty($rows) ? $rows : null;
    }
    
    /**
     * 根据(最好有索引)字段名&值获取一行或多行数据.
     *
     * @param string               $field 字段名
     * @param string|integer|array $value 值
     * @param array|string         $cols  指定获取的字段
     * @return array|null
     */
    public function fetchByFV($field, $value, $cols = null)
    {
        if (is_null($cols)) {
        	$cols = '*';
        }
        
        $select = $this->getAdapter()->select()->from($this->getTable(), $cols);
        if (is_array($value)) {
            $rows = $select->where($field . ' IN (\'' . implode('\',\'', $value) . '\')')->query()->fetchAll();
        } else {
            $rows = $select->where($field . ' = ?', $value)->query()->fetchAll();
        }
        
        return (is_array($rows) && count($rows) > 0) ? $rows : null;
    }
    
    /**
     * 通用的自定义WHERE田间搜索
     * 
     * @param string|array  $where
     * @param array         $order  排序
     * @param integer       $count
     * @param integer       $offset
     * @return array|null
     */
    public function fetchByWhere($where = null, $order = null, $count = null, $offset = null, $cols = null)
    {
        if (is_null($cols)) {
        	$cols = '*';
        }
        
        $select = $this->getAdapter()->select()->from($this->getTable(), $cols);
        
        if (is_string($where)) {
            $select->where($where);
        } elseif (is_array($where) && count($where)) {
            /**
             * 数组, 支持两种形式.
             */
            foreach ($where as $key => $val) {
                if (preg_match("/^[0-9]/", $key)) {
                    $select->where($val);
                } else {
                    $select->where($key . '= ?', $val);
                }
            }
        }
        
        if ($order !== null) {
            $select->order($order);
        }
        if ($count !== null || $offset !== null) {
            $select->limit($count, $offset);
        }
        $rows = $select->query()->fetchAll();
        return (is_array($rows) && count($rows)) ? $rows : null;
    }
    
    /**
     * 通用的自定义WHERE关联表搜索
     *
     * @param string|array $where
     * @param array $order
     * @param integer $count
     * @param integer $offset
     * @return array null
     */
    public function getJoinListByWhere ($table, $joinTables, $where = null, $order = null, $count = null, $offset = null, $cols = null)
    {
        if (is_null($cols)) {
            $cols = '*';
        }
        
        if (is_array($joinTables)) {
            $select = $this->getAdapter()
                    ->select()
                    ->from($table, $cols);
            
            /* 循环关联子表 */
            foreach ($joinTables as $k => $v) {
                $select = $select->joinLeft($v['table'], $v['on']);
            }
        }
        
        if (is_string($where)) {
            $select->where($where);
        } elseif (is_array($where) && count($where)) {
            /**
             * 数组, 支持两种形式.
             */
            foreach ($where as $key => $val) {
                if (preg_match("/^[0-9]/", $key)) {
                    $select->where($val);
                } else {
                    $select->where($key . '= ?', $val);
                }
            }
        }
    
        if ($order !== null) {
            $select->order($order);
        }
        if ($count !== null || $offset !== null) {
            $select->limit($count, $offset);
        }
    
        $rows = $select->query()->fetchAll();
        return (is_array($rows) && count($rows)) ? $rows : array();
    }
    
    /**
     * 根据字段名&值检测是否存在数据行
     *
     * @param string $field 字段名
     * @param string|mixed $value 值
     * @return boolean
     */
    public function rowExistsByFV($field, $value)
    {
        $row = $this->getAdapter()->select()->from($this->getTable(), array($field))->where($field . ' = ?', $value)->query()->fetch();
        
        return (is_array($row) && count($row) > 0) ? true : false;
    }
    
    /**
     * 由字段的KEY-VAL数组构建WHERE条件(AND连接).
     * 数组格式如下:
     * <code>
     * $rows = array('field1' = > '123', 'field2' => 'abc');
     * </code>
     * 该方法主要是用于兼容原Kohana中Database Query Builder的Where()
     *
     * @param array|string $rows
     * @return string
     */
    public function batchWhere($rows)
    {
        if (is_array($rows) && count($rows) > 0) {
            $where = array();
            foreach ($rows as $k => $v) {
                $where[] = $this->getAdapter()->quoteInto($k . ' = ?', $v);
            }
            return implode(' AND ', $where);
        } elseif (is_string($rows)) {
            return $rows;
        }
        
        return '';
    }
    
    /**
     * 批量数据库记录插入, 不检测字段名是否相配, 谨慎使用
     *
     * @param array $binds      二维数组
     * @param string $tablename
     * @return integer          返回插入影响条数
     */
    public function batchInsert($binds, $table = null)
    {
        $cols = array();
        $rows = array();
        foreach ($binds as $bind) {
            // extract and quote col names from the array keys
            $cols = array();
            $vals = array();
            foreach ($bind as $col => $val) {
                $cols[] = $this->_db->quoteIdentifier($col, true);
                $vals[] = ($val instanceof Zend_Db_Expr) ? $val->__toString() : $this->_db->quote($val);
            }
            
            $rows[] = $vals;
        }
        
        // build the statement
        $insertValues = array();
        foreach ($rows as $vals) {
            $insertValues[] = '(' . implode(', ', $vals) . ')';
        }
        
        if (null === $table) {
            $table = $this->getTable();
        }
        $sql = "INSERT INTO " . $this->_db->quoteIdentifier($table, true) . ' (' . implode(', ', $cols) . ') VALUES ' . implode(', ', $insertValues);
        
        return $this->_db->query($sql, array())->rowCount();
    }
    
    /**
     * 快速获取指定MODEL的实例
     *
     * @param string $name Model Name.
     * @throws Zeed_Exception
     * @return Zeed_Db_Model
     */
    public static function getModel($name)
    {
        return self::_instance($name);
    }
    
    /**
     * 获取'Com_Model_'前缀的Model-Class, 即将废弃
     * 
     * @param string $name
     * @return Zeed_Db_Model|null
     */
    public static function getComModel($name)
    {
        if (! is_string($name) || '' == $name) {
            return null;
        }
        
        if (strpos($name, '_') !== false) {
            $part = explode('_', $name);
            foreach ($part as $k => $v) {
                $part[$k] = ucfirst($v);
            }
            $name = implode('_', $part);
        } else {
            $name = ucfirst($name);
        }
        
        if (strpos($name, 'Com_Model_') !== 0) {
            $name = 'Com_Model_' . $name;
        }
        
        return self::getModel($name);
    }
    
    /**
     * 获取数据表记录对象的主键唯一的值
     * 该值是一个根据主键数字,自增的值
     *
     * @return integer
     */
    public function getUniqueId()
    {
        return false;
    }
    
    /**
     * 获取数据表记录对象的主键最大值
     * 
     * @return integer
     */
    public function getMaxId()
    {
        $id = 0;
        
        if (strlen($this->_primary) > 0) {
            $stmt = $this->_db->query(sprintf('SELECT MAX(%s) FROM `%s` WHERE 1', $this->_primary, $this->getTable()));
            $result = $stmt->fetchColumn();
            
            if (is_numeric($result)) {
                $id = (int) $result;
            }
        }
        
        return $id;
    }
    
    /**
     * 统计表记录条目数
     * 
     * @param string|array  $where
     * @return integer
     */
    public function getCount($where = null)
    {
        $select = $this->getAdapter()->select()->from($this->getTable(), array("count_num" => "count(*)"));
        if (is_string($where)) {
            $select->where($where);
        } elseif (is_array($where) && count($where)) {
            /**
             * 数组, 支持两种形式.
             */
            foreach ($where as $key => $val) {
                if (preg_match("/^[0-9]/", $key)) {
                    $select->where($val);
                } else {
                    $select->where($key . '= ?', $val);
                }
            }
        }
        $row = $select->query()->fetch();
        return $row ? $row["count_num"] : 0;
    }
    
    /**
     * 添加记录（根据 entity 字段映射关系过滤字段）
     * 
     * @param array $set
     * @return integer
     */
    public function addForEntity($set)
    {
        $entity = $this->_getEntity();
        if ($entity) {
            if ($set instanceof $entity) {
                $data = $set->toArray();
            } else {
                $entity = new $entity();
                $data = $entity->fromArray($set)->toArray();
            }
        } else {
            $data = $set;
        }
        return $this->insert($data);
    }
    
    /**
     * 更新记录
     *
     * @param array $set
     * @param string $id
     * @return integer
     */
    public function updateForEntity($set, $id)
    {
        $entity = $this->_getEntity();
        if ($entity) {
            if ($set instanceof $entity) {
                $data = $set->toArray();
            } else {
                $entity = new $entity();
                $data = $entity->fromArray($set)->toArray();
            }
        } else {
            $data = $set;
        }
        return $this->update($data, "{$this->_primary}='{$id}'");
    }
    
    /**
     * 根据主键 ID 删除一条记录
     * 
     * @param integer $id
     * @return integer
     */
    public function deleteByPK($id)
    {
        return $this->delete(array("{$this->_primary} = ?" => $id));
    }
    
    /**
     * 根据(最好有索引)字段名&值删除一行或多行数据.
     *
     * @param string               $field 字段名
     * @param string|integer|array $value 值
     * @return array|null
     */
    public function deleteByFV($field, $value)
    {
        if (is_array($value)) {
            return $this->delete($field . ' IN (\'' . implode('\',\'', $value) . '\')');
        } else {
            return $this->delete($field . ' = ' . $value);
        }
    }
    
    /**
     * 获取 entity
     */
    private function _getEntity()
    {
        $table = $this->getTable();
        $table_arr = explode('_', $table);
        
        $entity_arr = array();
        foreach ($table_arr as $k => $v) {
            $entity_arr[] = ucfirst($v);
            if ($k == 0) {
                $entity_arr[] = 'Entity';
            }
        }
        $entity = implode('_', $entity_arr);
        return $entity ? $entity : null;
    }
    
    /**
     * MODEL单列模式
     * 在子类中(注意注释中填写正确的返回值类型, 一般为类名):
     * <code>
     * public static function instance()
     * {
     * return parent::_instance(__CLASS__);
     * }
     * </code>
     *
     * @param string $model
     * @throws Zeed_Exception
     * @return Zeed_Db_Model
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