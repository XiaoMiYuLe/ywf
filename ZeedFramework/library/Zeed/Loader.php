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
 * @package    Zeed_Loader
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Loader.php 14037 2013-03-13 10:12:50Z xsharp $
 */

class Zeed_Loader
{
    public static function autoload($class)
    {
        try {
            Zeed_Loader::loadClass($class);
            return $class;
        } catch (Exception $e) {
            return false;
        }
        
        return false;
    }
    
    /**
     * Loads a class from a PHP file.  The filename must be formatted
     * as "$class.php".
     *
     * If $dirs is a string or an array, it will search the directories
     * in the order supplied, and attempt to load the first matching file.
     *
     * If $dirs is null, it will split the class name at underscores to
     * generate a path hierarchy (e.g., "Zeed_Example_Class" will map
     * to "Zend/Example/Class.php").
     *
     * If the file was not found in the $dirs, or if no $dirs were specified,
     * it will attempt to load it from PHP's include_path.
     *
     * @param string $class      - The full class name of a Zend component.
     * @param string|array $dirs - OPTIONAL Either a path or an array of paths to search.
     * @return void
     * @throws Zeed_Exception
     */
    public static function loadClass($class, $dirs = null)
    {
        if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }
        
        if (! is_null($file = self::loadClassFormClassIndex($class))) {
            include_once $file;
            if (class_exists($class, false) || interface_exists($class, false)) {
                return;
            }
        }
        
        if ((null !== $dirs) && ! is_string($dirs) && ! is_array($dirs)) {
            Zeed_Loader::loadClass('Zeed_Exception');
            throw new Zeed_Exception('Directory argument must be a string or an array');
        }
        
        // Find file in appointed folders.
        if (! empty($dirs)) {
            $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . EXT;
            // use the autodiscovered path
            $dirPath = dirname($file);
            if (is_string($dirs)) {
                $dirs = explode(PATH_SEPARATOR, $dirs);
            }
            foreach ($dirs as $key => $dir) {
                if ($dir == '.') {
                    $dirs[$key] = $dirPath;
                } else {
                    $dir = rtrim($dir, '\\/');
                    $dirs[$key] = $dir . DIRECTORY_SEPARATOR . $dirPath;
                }
            }
            $file = basename($file);
            Zeed_Loader::loadFile($file, $dirs, true);
        } else {
            // Zeed / Zend, high priority
            if (($suffix = substr($class, 0, 5)) === 'Zeed_' || $suffix === 'Zend_') {
                $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
                if (file_exists(ZEED_PATH . $file)) {
                    include_once ZEED_PATH . $file;
                    return;
                } elseif ($suffix === 'Zend_') {
                    include_once $file;
                    return;
                }
            }
            
            // Load the file named same as class name.
            if (null != $file = Zeed_Loader::findFile($class . EXT)) {
                include_once $file;
                if (class_exists($class, false) || interface_exists($class, false)) {
                    self::saveClassIndex($class, $file);
                    return;
                }
            }
            
            // Controller, Model, Entity, View
            $m = array();
            if (is_null($dirs) && preg_match("/([A-Z0-9]{1,})(Controller|Model|Entity|Object|Hook|View)$/i", $class, $m)) {
                $file = $m[1] . EXT;
                if (null != $file = Zeed_Loader::findFile($file, $m[2])) {
                    include_once $file;
                    if (class_exists($class, false) || interface_exists($class, false)) {
                        self::saveClassIndex($class, $file);
                        return;
                    }
                }
            }
            
            // Parse class name to folder.
            if (strstr($class, '_')) {
                $file = str_replace('_', '/', $class) . EXT;
                if (null != $file = Zeed_Loader::findFile($file)) {
                    include_once $file;
                    if (class_exists($class, false) || interface_exists($class, false)) {
                        self::saveClassIndex($class, $file);
                        return;
                    }
                }
            }
        }
        
        if (! class_exists($class, false) && ! interface_exists($class, false)) {
            Zeed_Loader::loadClass('Zeed_Exception');
            throw new Zeed_Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
        }
    }
    
    private static $__CLASSINDEXING = null;
    
    private static function loadClassFormClassIndex($classname)
    {
        if (is_null(self::$__CLASSINDEXING)) {
            if (file_exists(ZEED_PATH_DATA . 'cache/__CLASSINDEXING.php')) {
                self::$__CLASSINDEXING = include ZEED_PATH_DATA . 'cache/__CLASSINDEXING.php';
                
                /**
                 * 确保变量为数组
                 * 
                 * @see /manual/en/function.include.php
                 */
                if (!is_array(self::$__CLASSINDEXING)) {
                    self::$__CLASSINDEXING = array();
                }
            } else {
                return null;
            }
        }
        if (is_array(self::$__CLASSINDEXING) && isset(self::$__CLASSINDEXING[$classname])) {
            /**
             * 如果优先查找模块
             */
            if (defined('ZEED_PATH_MODULE')) {
                $moduleFolderName = basename(ZEED_PATH_MODULE);
                if (is_array(self::$__CLASSINDEXING[$classname])) {
                    if (isset(self::$__CLASSINDEXING[$classname][$moduleFolderName])) {
                        return self::$__CLASSINDEXING[$classname][$moduleFolderName];
                    } else {
                        reset(self::$__CLASSINDEXING[$classname]);
                        return current(self::$__CLASSINDEXING[$classname]);
                    }
                }
            }
            
            return self::$__CLASSINDEXING[$classname];
        }
        
        return null;
    }
    private static function saveClassIndex($classname, $filepath)
    {
        if (defined('ZEED_PATH_MODULE') && strstr($filepath, ZEED_PATH_MODULE)) {
            // 子模块的CLASS
            $moduleFolderName = basename(ZEED_PATH_MODULE);
            self::$__CLASSINDEXING[$classname][$moduleFolderName] = (string) $filepath;
        } else {
            // 全局的CLASS
            self::$__CLASSINDEXING[$classname] = (string) $filepath;
        }
        
        $string = "<?php\n";
        $string .= 'return ' . str_replace('\\\\', '/', var_export(self::$__CLASSINDEXING, true)) . ';';
        
        file_put_contents(ZEED_PATH_DATA . 'cache/__CLASSINDEXING.php', $string, LOCK_EX);
    }
    
    public static function registerAutoload($class = 'Zeed_Loader', $enabled = true)
    {
        if (! function_exists('spl_autoload_register')) {
            Zeed_Loader::loadClass('Zeed_Exception');
            throw new Zeed_Exception('spl_autoload does not exist in this PHP installation');
        }
        
        Zeed_Loader::loadClass($class);
        $methods = get_class_methods($class);
        if (! in_array('autoload', (array) $methods)) {
            Zeed_Loader::loadClass('Zeed_Exception');
            throw new Zeed_Exception("The class \"$class\" does not have an autoload() method");
        }
        
        if ($enabled === true) {
            spl_autoload_register(array($class, 'autoload'));
        } else {
            spl_autoload_unregister(array($class, 'autoload'));
        }
    }
    
    /**
     * Loads a PHP file.  This is a wrapper for PHP's include() function.
     *
     * $filename must be the complete filename, including any
     * extension such as ".php".  Note that a security check is performed that
     * does not permit extended characters in the filename.  This method is
     * intended for loading Zend Framework files.
     *
     * If $dirs is a string or an array, it will search the directories
     * in the order supplied, and attempt to load the first matching file.
     *
     * If the file was not found in the $dirs, or if no $dirs were specified,
     * it will attempt to load it from PHP's include_path.
     *
     * If $once is TRUE, it will use include_once() instead of include().
     *
     * @param  string        $filename
     * @param  string|array  $dirs - OPTIONAL either a path or array of paths to search.
     * @param  boolean       $once
     * @return boolean
     * @throws Zeed_Exception
     */
    public static function loadFile($filename, $dirs = null, $once = false)
    {
        self::_securityCheck($filename);
        
        /**
         * Search in provided directories, as well as include_path
         */
        $incPath = false;
        if (! empty($dirs) && (is_array($dirs) || is_string($dirs))) {
            if (is_array($dirs)) {
                $dirs = implode(PATH_SEPARATOR, $dirs);
            }
            $incPath = get_include_path();
            set_include_path($dirs . PATH_SEPARATOR . $incPath);
        }
        
        /**
         * Try finding for the plain filename in the include_path.
         */
        if ($once) {
            include_once $filename;
        } else {
            include $filename;
        }
        
        /**
         * If searching in directories, reset include_path
         */
        if ($incPath) {
            set_include_path($incPath);
        }
        
        return true;
    }
    
    /**
     * Ensure that filename does not contain exploits
     *
     * @param  string $filename
     * @return void
     * @throws Zeed_Exception
     */
    protected static function _securityCheck($filename)
    {
        /**
         * Security check
         */
        if (preg_match('/[^a-z0-9\\/\\\\_.-]/i', $filename)) {
            Zeed_Loader::loadClass('Zeed_Exception');
            throw new Zeed_Exception('Security check: Illegal character in filename');
        }
    }
    
    /**
     * Returns TRUE if the $filename is readable, or FALSE otherwise.
     * This function uses the PHP include_path, where PHP's is_readable()
     * does not.
     *
     * @param string   $filename
     * @return boolean
     */
    public static function isReadable($filename)
    {
        if (! $fh = @fopen($filename, 'r', true)) {
            return false;
        }
        @fclose($fh);
        return true;
    }
    
    /**
     * Find file
     *
     * @param string $filename
     * @param array|string $findPathsOrType
     * @return string
     */
    public static function findFile($filename, $findPathsOrType = null)
    {
        if (is_null($findPathsOrType)) {
            $findPathsOrType = explode(PATH_SEPARATOR, get_include_path());
        } elseif (is_string($findPathsOrType)) {
            $incPaths = Zeed::registry('ZEED_INCLUDE_PATH');
            if (is_array($incPaths)) {
                foreach ($incPaths as $module => $_incPaths) {
                    switch ($findPathsOrType) {
                        case 'Object' :
                            $filepath = rtrim($_incPaths['entity'], '\\/') . '/' . $filename;
                            break;
                        case 'Enity' :
                            $filepath = rtrim($_incPaths['entity'], '\\/') . '/' . $filename;
                            break;
                        case 'Model' :
                            $filepath = rtrim($_incPaths['model'], '\\/') . '/' . $filename;
                            break;
                        case 'Hook' :
                            $filepath = rtrim($_incPaths['hook'], '\\/') . '/' . $filename;
                            break;
                        case 'Controller' :
                            $filepath = rtrim($_incPaths['controller'], '\\/') . '/' . $filename;
                            break;
                    }
                    if (file_exists($filepath)) {
                        return $filepath;
                    }
                }
            }
        }
        
        if (is_array($findPathsOrType) && count($findPathsOrType) > 0) {
            foreach ($findPathsOrType as $dir) {
                /* 临时解决方案 - 设计了模块控制器中集成后台和前台目录的功能 */
                if (strstr($filename, 'Abstract.php')) {
                    $dir_arr_temp = explode('/', $dir);
                    if ($dir_arr_temp[count($dir_arr_temp) - 3] == 'controllers') {
                        unset($dir_arr_temp[count($dir_arr_temp) - 2], $dir_arr_temp[count($dir_arr_temp) - 1], $dir_arr_temp[count($dir_arr_temp)]);
                        $dir = implode('/', $dir_arr_temp);
                    }
                }
                /* 临时解决方案 - 设计了模块控制器中集成后台和前台目录的功能 @end 2013-07-14 */
                
                if (file_exists($filepath = rtrim($dir, '\\/') . '/' . $filename)) {
                    return $filepath;
                }
            }
        }
        
        return null;
    }
    
    /**
     * @param string $modulename
     */
    public static function addModulePath($modulename)
    {
        $_incPaths = Zeed::registry('ZEED_INCLUDE_PATH');
        if (! isset($_incPaths[$modulename])) {
            $moduleFolder = ZEED_PATH_APPS . $modulename . '/';
            $_incPaths[$modulename] = array(
                    'library' => realpath($moduleFolder . 'libraries/'), 
                    'model' => realpath($moduleFolder . 'models/'), 
                    'entity' => realpath($moduleFolder . 'entities/'), 
                    'controller' => $moduleFolder . 'controllers/');
            
            set_include_path(implode(PATH_SEPARATOR, $_incPaths[$modulename]) . PATH_SEPARATOR . get_include_path());
            Zeed::register($_incPaths, 'ZEED_INCLUDE_PATH');
        }
    }
}

// End ^ LF ^ UTF-8
