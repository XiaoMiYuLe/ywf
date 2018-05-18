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
 * @version    SVN: $Id: Zeed.php 8949 2010-12-16 14:00:06Z xsharp $
 */

/**
 * 定义框架常量
 * 测试
 */

define('ZEED_VERSION', '1.0.0-SNAPSHOT');
define('ZEED_CODENAME', 'farm');
define('ZEED_IS_WIN', DIRECTORY_SEPARATOR === '\\');

defined('ZEED_PATH') || define('ZEED_PATH', str_replace('\\', '/', realpath(dirname(__FILE__))) . '/');

// 下面常量如需定义建议在加载本文件之前设置
defined('ZEED_PATH_3rd') || define('ZEED_PATH_3rd', ZEED_PATH . '3rd/');
defined('ZEED_PATH_APPS') || define('ZEED_PATH_APPS', str_replace('\\', '/', realpath(ZEED_PATH . '../application') . '/'));
defined('ZEED_PATH_CONF') || define('ZEED_PATH_CONF', str_replace('\\', '/', realpath(ZEED_PATH . '../config') . '/'));
defined('ZEED_PATH_VIEW') || define('ZEED_PATH_VIEW', str_replace('\\', '/', realpath(ZEED_PATH . '../view') . '/'));
defined('ZEED_PATH_DATA') || define('ZEED_PATH_DATA', str_replace('\\', '/', realpath(ZEED_PATH . '../data')) . '/');
defined('ZEED_PATH_UPLOAD') || define('ZEED_PATH_UPLOAD', str_replace('\\', '/', realpath(ZEED_PATH . '../upload') . '/'));
defined('EXT') || define('EXT', '.php');

// Zeed need PHP 5.2 or newer
if (version_compare(PHP_VERSION, '5.3', '<')) {
    exit('Zeed need PHP 5.3 or newer.');
}

set_include_path(ZEED_PATH . PATH_SEPARATOR . get_include_path());

class Zeed
{
    /**
     * 对象注册表
     *
     * @var array
     */
    private static $_objects = array();
    
    /**
     * 以特定名字在对象注册表中登记一个对象
     *
     * 开发者可以将一个对象登记到对象注册表中，以便在应用程序其他位置使用 Q::registry() 来查询该对象。
     * 登记时，如果没有为对象指定一个名字，则以对象的类名称作为登记名。
     *
     * <code>
     * // 注册一个对象
     * Zeed::register(new MyObject(), 'keyName');
     * .....
     * // 稍后取出对象
     * $obj = Zeed::regitry('MyObject');
     * </code>
     *
     * @param object $obj 要登记的对象
     * @param string $name 用什么名字登记
     * @return object
     */
    public static function register($obj, $name = null)
    {
        if (empty($name) && is_object($obj)) {
            $name = get_class($obj);
        } elseif (empty($name)) {
            Zeed_Loader::loadClass('Zeed_Exception');
            throw new Zeed_Exception(Zeed::_t('Type mismatch. $obj expected is object, actual is "%s".', gettype($obj)));
        }
        self::$_objects[$name] = $obj;
        return $obj;
    }
    
    /**
     * 查找指定名字的对象实例，如果指定名字的对象不存在则抛出异常
     *
     * <code>
     * // 注册一个对象
     * Zeed::register(new MyObject(), 'obj1');
     * .....
     * // 稍后取出对象
     * $obj = Zeed::regitry('obj1');
     * </code>
     *
     * @param string $name 要查找对象的名字
     *
     * @return object 查找到的对象
     */
    public static function registry($name)
    {
        if (isset(self::$_objects[$name])) {
            return self::$_objects[$name];
        }
        
        Zeed_Loader::loadClass('Zeed_Exception');
        throw new Zeed_Exception(Zeed::_t('No object is registered of name "%s".', $name));
    }
    
    public static function _t()
    {
        $args = func_get_args();
        return call_user_func_array('sprintf', $args);
    }
    
    public static function packageClass($classArr, $cacheKey = null, $autoPackage = false)
    {
        if (! $autoPackage) {
            return false;
        }
        if (! is_array($classArr)) {
            $classArr = array((string) $classArr);
        }
        if (is_null($cacheKey)) {
            $cacheKey = md5(implode(',', $classArr));
        }
        $cacheFile = ZEED_PATH_DATA . 'shadow/' . $cacheKey . EXT;
        if (file_exists($cacheFile)) {
            include $cacheFile;
            return;
        }
        
        $cacheContent = '';
        foreach ($classArr as $class) {
            $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . EXT;
            $file = dirname(__FILE__) . '/' . $file;
            $cacheContent .= str_replace('<?php', '', php_strip_whitespace($file));
        }
        file_put_contents($cacheFile, "<?php" . $cacheContent);
        
        include $cacheFile;
    }
    
    private $_files;
    
    /**
     * @todo 处理已经加载的文件
     * 
     * @param array $fileArr
     * @param string $cacheKey
     */
    public static function packageFile($fileArr, $cacheKey = null, $autoPackage = false)
    {
        if (! $autoPackage) {
            foreach ($fileArr as $file) {
                include_once $file;
            }
            return;
        }
        if (is_null($cacheKey)) {
            $cacheKey = md5(implode(',', $fileArr));
        }
        $cacheFile = ZEED_PATH_DATA . 'shadow/' . $cacheKey . EXT;
        if (file_exists($cacheFile)) {
            include $cacheFile;
            return;
        }
        
        $cacheContent = '';
        foreach ($fileArr as $file) {
            if (file_exists($file)) {
                $cacheContent .= str_replace('<?php', '', php_strip_whitespace($file));
            }
        }
        file_put_contents($cacheFile, "<?php" . $cacheContent);
        
        include $cacheFile;
    }
}

/**
 * 字符串翻译, 尚未实现
 * 
 * @param string $str
 * @return string
 */
function __($str)
{
    return $str;
}


// End ^ LF ^ UTF-8
