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
 * @package    Zeed_Object
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Object.php 13329 2012-08-27 01:21:15Z xsharp $
 */

/**
 * 一个特殊的空对象, 用于表示对象内为空的属性
 * 或使用: eval('final class Zeed_Object_Null{}');
 */
if (! class_exists('Zeed_Object_Null', false)) {
    final class Zeed_Object_Null
    {
        public function __toString()
        {
            return '';
        }
    }
}

/**
 * Base Entity
 *
 * @author xsharp
 */
class Zeed_Object
{
    /**
     * 导入(fromArray)/导出(toArray)时Property Key大小写(转换)
     */
    const KEY_AUTO = 0; // 自动
    const KEY_LOWER = 1; // 转成小写
    const KEY_UPPER = 2; // 转成大写
    const KEY_NATURAL = 3; // 不转换,严格于OBJECT的Property
    

    /**
     * 对象反射缓存
     */
    private $_ReflectedPropertiesCache = null;
    
    /**
     * 对象是否为空标记
     *
     * @var boolean
     */
    private $_objectEmptyFlag = true;
    
    /**
     * 将所有默认为的空属性"置空".
     * 
     * <code>
     *   $o = new Zeed_Object(array('foo' => 'bar'));
     * </code>
     *
     * @return void
     */
    public function __construct()
    {
        static $Zeed_Object_Null = null;
        if (is_null($Zeed_Object_Null)) {
            $Zeed_Object_Null = new Zeed_Object_Null();
        }
        
        if (count($p = $this->getReflectedProperties())) {
            foreach ($p as $propertyName) {
                if (is_null($this->$propertyName)) {
                    $this->$propertyName = $Zeed_Object_Null;
                }
            }
        }
        
        $vars = func_get_args();
        if (isset($vars[0]) && is_array($vars[0])) {
            $this->fromArray($vars[0]);
        }
    }
    
    /**
     * 获取当前对象的属性(PUBLIC和PROTECTED)
     *
     * @return Array
     */
    public function getReflectedProperties()
    {
        if (is_null($this->_ReflectedPropertiesCache)) {
            $this->_ReflectedPropertiesCache = array();
            
            $refectionClass = new ReflectionClass($this);
            $propertiesArray = $refectionClass->getProperties();
            if (is_array($propertiesArray) and count($propertiesArray) > 0) {
                while (@list(, $property) = each($propertiesArray)) {
                    $refectionProperty = new ReflectionProperty($property->class, $property->name);
                    if ($refectionProperty->isPublic() || $refectionProperty->isProtected()) {
                        $this->_ReflectedPropertiesCache[] = $property->name;
                    }
                }
            }
        }
        
        return $this->_ReflectedPropertiesCache;
    }
    
    /**
     * 输出为数组
     *
     * @param Integer $keyCase 输出数组索引大小写转换
     * @return Array
     */
    public function toArray($keyCase = Zeed_Object::KEY_NATURAL)
    {
        if (count($p = $this->getReflectedProperties())) {
            $properties = array();
            foreach ($p as $propertyName) {
                if ($this->$propertyName instanceof Zeed_Object_Null) {
                    continue;
                }
                $properties[$propertyName] = $this->$propertyName;
            }
        } else {
            return array();
        }
        
        $filters = array();
        
        switch ($keyCase) {
            case self::KEY_LOWER :
                $properties = array_change_key_case($properties, CASE_LOWER);
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_LOWER);
                    return array_diff_key($properties, $filters);
                } else {
                    return $properties;
                }
                break;
            
            case self::KEY_UPPER :
                $properties = array_change_key_case($properties, CASE_UPPER);
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_UPPER);
                    return array_diff_key($properties, $filters);
                } else {
                    return $properties;
                }
                break;
            
            case self::KEY_NATURAL :
                if (count($filters) > 0) {
                    return array_diff_key($properties, $filters);
                } else {
                    return $properties;
                }
                break;
            
            default : // KEY_AUTO
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_LOWER);
                    while (@list($key, ) = each($properties)) {
                        if (array_key_exists(strtolower($key), $filters)) {
                            unset($properties[$key]);
                        }
                    }
                }
                return $properties;
                break;
        }
    }
    
    /**
     * 返回字符串格式
     * 
     * @return string
     */
    public function toString()
    {
        return serialize($this->toArray());
    }
    
    /**
     * 产生用于SQL更新的数组, 需要设置原先对象的属性值(设置了哪些比较哪些)
     *
     * @return array
     */
    public function generateUpdateSet()
    {
    
    }
    
    /**
     * 与另一个Zeed_Object的值比较(不比较类型), 返回差集数组
     * 该数组包括了所有在 当前对象中但是不在参数对象中的值, 键名为字段名. 
     * 
     * @param Zeed_Object $obj
     * @return boolean
     */
    public function diff(Zeed_Object $obj)
    {
        $row1 = $this->toArray();
        $row2 = $obj->toArray();
        
        $return = array();
        foreach ($row1 as $k => $v) {
            if ($v != $row2[$k]) {
                $return[$k] = $v;
            }
        }
        
        return $return;
    }
    
    /**
     * 通过对象对 Zeed_Object对象属性赋值
     *
     * @param Zeed_Object|array $propertySet
     * @param integer $keyCase
     * @return Zeed_Object
     */
    public function fromObject($propertySet, $keyCase = self::KEY_NATURAL)
    {
        if (is_object($propertySet) && $propertySet instanceof Zeed_Object) {
            $data = $propertySet->toArray();
            $this->fromArray($data, $keyCase);
        } elseif (is_array($propertySet)) {
            $this->fromArray($propertySet, $keyCase);
        }
        
        return $this;
    }
    
    /**
     * 通过数组批量给Zeed_Object对象属性赋值.
     *
     * @return Zeed_Object
     */
    public function fromArray($propertySet, $keyCase = self::KEY_NATURAL)
    {
        $filters = array();
        
        switch ($keyCase) {
            
            case self::KEY_LOWER :
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_LOWER);
                    $propertySet = array_diff_key($propertySet, $filters); // 过滤后的数组
                }
                $this->setProperty($propertySet, self::KEY_LOWER);
                break;
            
            case self::KEY_UPPER :
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_UPPER);
                    $propertySet = array_diff_key($propertySet, $filters); // 过滤后的数组
                }
                $this->setProperty($propertySet, self::KEY_UPPER);
                break;
            
            case self::KEY_NATURAL :
                if (count($filters) > 0) {
                    $propertySet = array_diff_key($propertySet, $filters); // 过滤后的数组
                }
                $this->setProperty($propertySet, self::KEY_NATURAL);
                break;
            
            default : // KEY_AUTO, 全部转为小写
                $propertySet = array_change_key_case($propertySet, CASE_LOWER);
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_LOWER);
                    $propertySet = array_diff_key($propertySet, $filters); // 过滤后的数组
                }
                $this->setProperty($propertySet, self::KEY_LOWER);
                break;
        }
        
        return $this;
    }
    
    /**
     * @return void
     */
    protected function setProperty($propertySet, $case = self::KEY_NATURAL)
    {
        if (count($properties = $this->getReflectedProperties()) == 0) {
            return;
        }
        
        while (@list(, $propertyName) = each($properties)) {
            if ($case == self::KEY_LOWER) {
                $keyNew = strtolower($propertyName);
            } elseif ($case == self::KEY_UPPER) {
                $keyNew = strtoupper($propertyName);
            } else {
                $keyNew = $propertyName;
            }
            if (array_key_exists($keyNew, $propertySet)) {
                $this->$propertyName = $propertySet[$keyNew];
                $this->_objectEmptyFlag = false;
            }
        }
    }
    
    /**
     * 判断对象的属性是否被赋值过，任何一个属性被赋值(即非Zeed_Objec_Null对象)则返回false
     * 当对象成员变量被设置为 false， '' 空字符串时并不返回 true
     *
     * @param $skipProperties
     * @return boolean
     */
    public function isEmpty($skipProperties = array())
    {
        if (! $this->_objectEmptyFlag) {
            return false;
        }
        
        /**
         * 检查当前变量是否真为空，对象可能直接通过访问成员变量设置
         */
        if (count($p = $this->getReflectedProperties())) {
            $properties = array();
            foreach ($p as $propertyName) {
                if (! $this->$propertyName instanceof Zeed_Object_Null) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * 输出成PHP代码(数组)
     *
     * @return string
     */
    public function export($keyCace = self::KEY_NATURAL)
    {
        return var_export($this->toArray($keyCace));
    }
    
    /**
     * @return Zeed_Object
     */
    public function __call($method, $args)
    {
        $refection = new ReflectionClass($method);
        if ($refection->isSubclassOf('Zeed_Object')) {
            return new $method($args);
        }
        
        return false;
    }
    
    private static $_instance;
    
    /**
     * @return Zeed_Object
     */
    public final static function instance()
    {
        if (! self::$_instance instanceof Zeed_Object) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
}

// End ^ LF ^ UTF-8
