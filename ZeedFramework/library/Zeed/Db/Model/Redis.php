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
 * @since      2011-5-18
 * @version    SVN: $Id$
 */

class Zeed_Db_Model_Redis
{
    /**
     * @var config
     */
    protected $_config = null;
    
    /**
     * @var RedisCollection
     */
    protected $collection = null;

    /**
     * 数据库连接实例缓存
     *
     * @var array
     */
    protected static $instanceInternalCache = array();

    public function __construct ($config = array())
    {
        $this->_config = Zeed_Config::loadGroup('database.redis');
        
        $this->collection = $this->connect($this->_config['host'], $this->_config['port']);
        $this->collection->select($this->_config['db']);
    }

    /**
     * 数据库连接实例缓存
     *
     * @var array
     */
    protected static $connections = null;

    /**
     * @return Redis
     */
    protected function connect ($host, $port)
    {
        self::$connections[$host] = new redis();
        self::$connections[$host]->connect($host, $port);
        return self::$connections[$host];
    }
    
    /**
     * 判断key是否存在
     *
     * @param string $key/存储键名
     * @return boolean
     */
    public function exists ($key)
    {
        return $this->collection->exists($key);
    }
    
    /**
     * 设置值
     *
     * @param string $key/存储键名
     * @param array $value/存储数据
     * @return string
     */
    public function set ($key, $value)
    {
        $ret = $this->collection->set($key, $value);
        if (is_int($this->_config['expire']) && $this->_config['expire']) {
            $this->collection->expire($key, $this->_config['expire']);
        }
        return $ret;
    }
    
    /**
     * 读取值
     *
     * @param string $key/存储键名
     * @return string
     */
    public function get ($key, $expire = TRUE)
    {
        $ret = $this->collection->get($key);
        if ($expire && is_int($this->_config['expire']) && $this->_config['expire']) {
            $this->collection->expire($key, $this->_config['expire']);
        }
        return $ret;
    }

    /**
     * list 入队列 返回已进入当前队列的元素数量
     *
     * @param string $key/存储键名            
     * @param string $value/存储值            
     * @return int/执行命令后，列表的长度
     */
    public function rpush ($key, $value)
    {
        return $this->collection->rpush($key, $value);
    }
    
    /**
     * 将 key 中的值自增 1
     * 
     * @param string $key
     * @return string
     */
    public function incr ($key)
    {
        return $this->collection->incr($key);
    }
    
    /**
     * 将 key 中的值，自增第二个参数所填的值
     * 
     * @param string $key
     * @param integer $val
     * @return string
     */
    public function incrby ($key, $val)
    {
        return $this->collection->incrby($key, $val);
    }

    /**
     * list 出队列
     *
     * @param string $key/存储键名            
     * @return string
     */
    public function lpop ($key)
    {
        return $this->collection->lpop($key);
    }

    /**
     * list 删除元素
     *
     * @param string $key/存储键名            
     * @param string $value/存储值            
     * @return int/被移除元素的数量
     */
    public function lrem ($key, $value)
    {
        return $this->collection->lrem($key, $value, 0);
    }

    /**
     * list 查询队列
     *
     * @param string $key/存储键名            
     * @param int $index/队列索引
     *            0表示第一个
     *            -1表示最后一个
     * @return string
     */
    public function lindex ($key, $index)
    {
        return $this->collection->lindex($key, $index);
    }

    /**
     * list 遍历
     *
     * @param string $key/存储键名            
     * @return array
     */
    public function lrange ($key)
    {
        return $this->collection->lrange($key, 0, - 1);
    }

    /**
     * list 返回元素的数量
     *
     * @param string $key/存储键名            
     * @return int
     */
    public function llen ($key)
    {
        return $this->collection->llen($key);
    }
    
    /**
     * 返回名称为key的list有多少个元素
     *
     * @param string $key/存储键名            
     * @return int
     */
    public function lsize ($key)
    {
        return $this->collection->lsize($key);
    }

    /**
     * hash 设置值
     *
     * @param string $key/存储键名            
     * @param array $value/存储数据            
     * @return string
     */
    public function hmset ($key, $value)
    {
        return $this->collection->hmset($key, $value);
    }
    
    /**
     * hash 读取值
     *
     * @param string $key/存储键名            
     * @param array $value/存储数据            
     * @return string
     */
    public function hmget ($key, $arr)
    {
        return $this->collection->hmget($key, $arr);
    }
    
    /**
     * hash 设置值
     * 
     * @param string $h
     * @param string $key
     * @param string $val
     * @return string
     */
    public function hset ($h, $key, $val)
    {
        return $this->collection->hset($h, $key, $val);
    }
    
    /**
     * hash 读取值
     * 
     * @param string $h
     * @param string $key
     * @return string
     */
    public function hget ($h, $key)
    {
        return $this->collection->hget($h, $key);
    }

    /**
     * hash 读取值
     *
     * @param string $key/存储键名            
     * @return string
     */
    public function hgetall ($key, $expire = TRUE)
    {
        $ret = $this->collection->hgetall($key);
        if ($expire && is_int($this->_config['expire']) && $this->_config['expire']) {
            $this->collection->expire($key, $this->_config['expire']);
        }
        return $ret;
    }

    /**
     * hash 删除 key中的一个或多个指定域，不存在的域将被忽略
     *
     * @param string $key/存储键名            
     * @param string $field/域名            
     * @return int/被成功移除的域的数量，不包括被忽略的域
     */
    public function hdel ($key, $domain)
    {
        return $this->collection->hdel($key, $domain);
    }

    /**
     * set 设置值
     *
     * @param string $key/存储键名            
     * @param array $value/存储数据            
     * @return int 0,1
     */
    public function sadd ($key, $value)
    {
        return $this->collection->sadd($key, $value);
    }

    /**
     * set 移除元素
     *
     * @param string $key/存储键名            
     * @param array $value/元素名称            
     * @return int/被成功移除的元素的数量，不包括被忽略的元素
     */
    public function srem ($key, $value)
    {
        return $this->collection->srem($key, $value);
    }

    /**
     * set 返回集合所有信息
     *
     * @param string $key/存储键名            
     * @return array
     */
    public function smembers ($key)
    {
        return $this->collection->smembers($key);
    }

    /**
     * set 返回交集信息
     *
     * @param string $arrKey/需查询的交集key            
     * @return array
     */
    public function sinter ($arrKey)
    {
        return $this->collection->sinter($arrKey);
    }

    /**
     * set 返回差集信息
     *
     * @param string $arrKey/需查询的差集key            
     * @return array
     */
    public function sdiff ($arrKey)
    {
        return $this->collection->sdiff($arrKey);
    }

    /**
     * set 返回并集信息
     *
     * @param string $arrKey/需查询的并集key            
     * @return array
     */
    public function sunion ($arrKey)
    {
        return $this->collection->sunion($arrKey);
    }

    /**
     * zset 设置值
     *
     * @param string $key/存储键名            
     * @param string $score/存储的排序值（一般我用时间戳）            
     * @param string $value/存储数据            
     * @return int 被成功添加的新成员的数量，不包括那些被更新的、已经存在的成员
     */
    public function zadd ($key, $score, $value)
    {
        return $this->collection->zadd($key, $score, $value);
    }

    /**
     * zset 返回列表 从大到小排序
     *
     * @param string $key/存储键名            
     * @param int $start/第几个开始显示            
     * @param int $end/结束位置-1为全长度            
     * @param boolean $withscores/是否显示score值            
     * @return array/指定区间内，带有 score 值(可选)的有序集成员的列表
     */
    public function zrevrange ($key, $start, $end, $withscores = FALSE)
    {
        return $this->collection->zrevrange($key, $start, $end, $withscores);
    }

    /**
     * zset 返回列表 从小到大排序
     *
     * @param string $key/存储键名            
     * @param int $start/第几个开始显示            
     * @param int $end/结束位置-1为全长度            
     * @param boolean $withscores/是否显示score值            
     * @return array/指定区间内，带有 score 值(可选)的有序集成员的列表
     */
    public function zrange ($key, $start, $end, $withscores = FALSE)
    {
        return $this->collection->zrange($key, $start, $end, $withscores);
    }

    /**
     * zset 返回并集数据
     *
     * @param string $arrKey/需查询的并集key            
     * @param int $start/第几个开始显示            
     * @param int $end/结束位置-1为全长度            
     * @return array
     */
    public function zunion ($arrKey, $start, $end)
    {
        $redisData = $this->collection->multi()
            ->zunionstore('tempKey', $arrKey)
            ->zrevrange('tempKey', $start, $end)
            ->del('tempKey')
            ->exec(); // 事务模式
        return $redisData[1]; // 获取第二个命令执行的数据
    }

    /**
     * 删除键
     *
     * @param string $key/存储键名            
     * @param
     * @return boolean
     */
    public function del ($key)
    {
        return $this->collection->del($key);
    }
    
    /**
     * 选择一个数据库
     */
    public function select($database)
    {
    	$ret = $this->collection->select($database);
    	return $ret;
    }
    
    /**
     * 清空当前数据库
     *
     * @return boolean
     */
    public function flushDB ()
    {
        return $this->collection->flushDB();
    }
    
    /**
     * 清空所有数据库
     *
     * @return boolean
     */
    public function flushAll ()
    {
        return $this->collection->flushAll();
    }
    
    /**
     * 发表内容到某一个通道
     */
    public function publish ($channel, $key)
    {
    	$ret = $this->collection->publish($channel, $key);
    	return $ret;
    }
    
    /**
     * 设定一个key的活动时间，单位：秒(s)
     */
    public function expire($key, $ttl)
    {
    	$ret = $this->collection->expire($key, $ttl);
    	return $ret;
    }
    
    /**
     * 返回名称为h的hash中元素个数
     *
     * @param string $key/存储键名
     * @return int
     */
    public function hlen ($key)
    {
    	return $this->collection->hlen($key);
    }
    
    /**
     * 给key重命名
     *
     * @param string $key/存储键名
     * @return int
     */
    public function rename ($oldkey, $key)
    {
    	return $this->collection->rename($oldkey, $key);
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
     * @return Zeed_Db_Model_Redis
     */
    protected static function _instance ($model)
    {
        if (isset(self::$instanceInternalCache[$model]) &&
                 is_subclass_of(self::$instanceInternalCache[$model], __CLASS__)) {
            return self::$instanceInternalCache[$model];
        } elseif (class_exists($model)) {
            self::$instanceInternalCache[$model] = new $model();
            return self::$instanceInternalCache[$model];
        }
        
        throw new Zeed_Exception('Redis Model( ' . $model . ' ) not exists.');
    }
}

// End ^ Native EOL ^ UTF-8