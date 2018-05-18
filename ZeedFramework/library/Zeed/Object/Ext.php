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
 * @since      Apr 8, 2010
 * @version    SVN: $Id: Ext.php 13102 2012-06-27 10:24:15Z xsharp $
 */

/**
 * 支持扩展属性
 */
abstract class Zeed_Object_Ext extends Zeed_Object
{
    /**
     * @var array 允许的扩展属性列表
     */
    private $_allowedProperties = null;
    
    /**
     * @var array 扩展属性列表
     */
    private $_extProperties = array();
    
    /**
     * @override Zeed_Object::fromArray($propertySet, $keyCase = self::KEY_NATURAL)
     * @return Zeed_Object_Ext
     */
    public function fromArray($propertySet, $keyCase = self::KEY_NATURAL)
    {
        if ($keyCase == self::KEY_AUTO) {
            throw new Zeed_Exception('KEY_AUTO not supported.');
        }
        $p = $this->getReflectedProperties();
        $ep = (is_array($this->_allowedProperties)) ? array_keys($this->_allowedProperties) : null;
        if (! is_null($ep)) {
            foreach ($propertySet as $pk => $pv) {
                if (! in_array($pk, $p) && in_array($pk, $ep)) {
                    $this->addExtProperty($pk, $pv);
                }
            }
        } else {
            foreach ($propertySet as $pk => $pv) {
                if (! in_array($pk, $p)) {
                    $this->addExtProperty($pk, $pv);
                }
            }
        }
        
        return parent::fromArray($propertySet, $keyCase);
    }
    
    /**
     * @override Zeed_Object::toArray($keyCase = self::KEY_NATURAL)
     * @param Integer $keyCase 输出数组索引大小写转换
     * @return Array
     */
    public function toArray($keyCase = Zeed_Object::KEY_NATURAL)
    {
        $row = parent::toArray($keyCase);
        $orginalProperties = $this->getReflectedProperties();
        
        $refectionObject = new ReflectionObject($this);
        $propertiesArray = $refectionObject->getProperties();
        foreach ($propertiesArray as $property) {
            if (! in_array($property->name, $orginalProperties)) {
                $this->addExtProperty($property->name);
            }
        }
        
        return $row;
    }
    
    /**
     * 与另一个Zeed_Object的值(含扩展字段)比较(不比较类型), 返回差集数组
     * 该数组包括了所有在 当前对象中但是不在参数对象中的值, 键名为字段名. 
     * 
     * @param Zeed_Object $obj
     * @return boolean
     */
    public function diff(Zeed_Object $obj)
    {
        $row1 = $this->toArray();
        $row1 = array_merge($row1, $this->getExtProperties());
        $row2 = $obj->toArray();
        $row2 = array_merge($row2, $obj->getExtProperties());
        
        $return = array();
        foreach ($row1 as $k => $v) {
            if (isset($row2[$k]) && $v != $row2[$k]) {
                $return[$k] = $v;
            }
        }
        
        return $return;
    }
    
    /**
     * @param String $field
     * @param Mixed $value
     * @return void
     */
    protected function addExtProperty($field, $value = null)
    {
        if (is_null($value)) {
            $value = isset($this->$field) ? $this->$field : null;
        }
        
        $this->_extProperties[$field] = $value;
    }
    
    /**
     * @return Array
     */
    public function getExtProperties()
    {
        return $this->_extProperties;
    }
    
    /**
     * @return Mixed
     */
    public function getExtProperty($propertyname)
    {
        return isset($this->_extProperties[$propertyname]) ? $this->_extProperties[$propertyname] : null;
    }
    
    /**
     * @return String 序列化字串
     */
    public function buildPropertiesCache()
    {
        return serialize($this->_extProperties);
    }
    
    /**
     * 扩展属性表,常见是基本表名+ext
     * @var string
     */
    private $_tableExt;
    public function setExtTable($table)
    {
        $this->_tableExt = $table;
        return $this;
    }
    
    public function getExtTable($prefix = null)
    {
        return ! empty($prefix) ? $prefix . $this->_tableExt : $this->_tableExt;
    }
    
    private $_extProcessClass;
    private $_extProcessMethod;
    /**
     *
     * @param string $class
     * @param string $method
     */
    public function setExtProcessHandle($class, $method)
    {
        $this->_extProcessClass = $class;
        $this->_extProcessMethod = $method;
        
        return $this;
    }
    
    public function saveExt()
    {
        $handle = Zeed_Db_Model::getModel($this->_extProcessClass);
        $method = $this->_extProcessMethod;
        $handle->$method($this);
    }
    
    /**
     * 设置允许的扩展属性
     * <code>
     * $properties = array(propertyname=>description, 'title'=>'标题', 'username'=>用户名)
     * </code>
     *
     * @param Array $propertyList
     * @return Zeed_Object_Ext
     */
    public function setAllowedProperties($propertyList)
    {
        if (empty($propertyList)) {
            return $this;
        }
        
        if (is_array($this->_allowedProperties)) {
            $this->_allowedProperties = array_merge($this->_allowedProperties, $propertyList);
        } else {
            $this->_allowedProperties = $propertyList;
        }
        
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getAllowedProperties()
    {
        return $this->_allowedProperties;
    }

}

// End ^ LF ^ encoding
