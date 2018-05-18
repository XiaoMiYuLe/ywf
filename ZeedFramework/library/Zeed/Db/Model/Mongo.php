<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-5-18
 * @version    SVN: $Id$
 */

class Zeed_Db_Model_Mongo
{
    /**
     * @var Mongo
     */
    protected $conn;
    
    /**
     * @var MongoDb
     */
    protected $db;
    
    /**
     * @var MongoCollection
     */
    protected $collection = null;
    
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
    
    public function __construct($config = array())
    {
        $config = Zeed_Config::loadGroup('database.mongodb');
        
        if ($this->_prefix != '') {
            $this->_name = $this->_prefix . $this->_name;
        }
        
        $serverOptions = isset($config['server_options']) ? $config['server_options'] : array();
        $this->conn = $this->connect($config['server'], $serverOptions);
        $this->db = $this->conn->selectDB($config['db']);
        $this->collection = $this->db->selectCollection($this->_name);
    }
    
    /**
     * 数据库连接实例缓存
     *
     * @var array
     */
    protected static $connections = null;
    
    /**
     * @return Mongo
     */
    protected function connect($dsn, $options)
    {
        if (! isset(self::$connections[$dsn])) {
            if (! empty($options) && is_array($options)) {
                self::$connections[$dsn] = new Mongo($dsn, $options);
            } else {
                self::$connections[$dsn] = new Mongo($dsn);
            }
        }
        
        return self::$connections[$dsn];
    }
    
    /**
     * @return array
     */
    public function listDBs()
    {
        return $this->conn->listDBs();
    }
    
    /**
     * @return array
     */
    public function listCollections()
    {
        return $this->db->listCollections();
    }
    
    /**
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
     * @return Zeed_Db_Model_Mongo
     */
    protected static function _instance($model)
    {
        if (isset(self::$instanceInternalCache[$model]) && is_subclass_of(self::$instanceInternalCache[$model], __CLASS__)) {
            return self::$instanceInternalCache[$model];
        } elseif (class_exists($model)) {
            self::$instanceInternalCache[$model] = new $model();
            return self::$instanceInternalCache[$model];
        }
        
        throw new Zeed_Exception('Mongo Model( ' . $model . ' ) not exists.');
    }
}

// End ^ Native EOL ^ UTF-8