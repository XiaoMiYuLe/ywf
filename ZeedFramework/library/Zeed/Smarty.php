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
 * @category Zeed
 * @package Zeed_Smarty
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-6-30
 * @version SVN: $Id: Smarty.php 14011 2013-02-04 09:58:06Z xsharp $
 */

defined('SMARTY_VERSION') || define('SMARTY_VERSION', 2);

require_once ZEED_PATH_3rd . 'smarty' . SMARTY_VERSION . '/Smarty.class.php';

/**
 * 对Smarty的扩展
 *
 * @todo 支持自定义标签
 * @todo 支持PackJavaScript(压缩JavaScript)
 * @todo 支持Locales(本地化/多语言)
 */
class Zeed_Smarty extends Smarty
{
    public $gzOutput = false;
    protected $_module;
    protected $_theme;
    protected $_defaultTheme = 'template';
    
    /**
     *
     * @var Zeed_Smarty
     */
    private static $_smarty = null;
    private static $_instanceCount = 0;
    
    /**
     * 用于存放所有SMARTY实例需要的共享赋值数据
     *
     * @var Array
     */
    private static $tplVarsGlobal;
    
    /**
     *
     * @return Array $config
     * @return Zeed_Smarty
     */
    public static function instance()
    {
        if (! self::$_smarty instanceof Zeed_Smarty) {
            $config = Zeed_Config::loadGroup('smarty');
            if (! is_array($config) || count($config) == 0) {
                throw new Zeed_Exception('Smarty config load fail.');
            }
            $smarty = new self();
            $smarty->debugging = isset($config['debugging']) ? $config['debugging'] : 0;
            $smarty->php_handling = isset($config['php_handling']) ? $config['php_handling'] : 0;
            $smarty->caching = $config['caching'];
            $smarty->cache_lifetime = $config['cache_lifetime'];
            $smarty->cache_dir = $config['cache_dir'];
            $smarty->template_dir = $config['template_dir'];
            $smarty->compile_dir = $config['compile_dir'];
            $smarty->config_dir = $config['config_dir'];
            if (isset($config['plugins_dir'])) {
                $_plugins_dir = array();
                if (is_array($config['plugins_dir'])) {
                    $_plugins_dir = $config['plugins_dir'];
                    foreach ($config['plugins_dir'] as $key => $val) {
                        if (! is_dir($val)) {
                            unset($_plugins_dir[$key]);
                        }
                    }
                } elseif (is_dir($config['plugins_dir'])) {
                    $_plugins_dir[] = $config['plugins_dir'];
                }
                if (count($_plugins_dir)) {
                    $smarty->plugins_dir = $_plugins_dir;
                }
            }
            if (! is_dir($smarty->compile_dir)) {
                Zeed_Util::mkpath($smarty->compile_dir);
            }
            if ($config['gz_output']) {
                $smarty->gzOutput = true;
            }
            $smarty->assign('_POWERED_BY', 'Powered by ZeedFramework');
            if (isset($config['auto_assign_vars']) && is_array($config['auto_assign_vars'])) {
                $smarty->assign($config['auto_assign_vars']);
            }
            self::$_smarty = &$smarty;
        }
        self::$_instanceCount ++;
        
        return self::$_smarty;
    }
    
    /**
     * 设置当前模块名,
     * 如果设置了模块名, 且不为空字符串,
     * 则$smarty->display('XXX.html')将自动解析为$smarty->display('MODULE-NAME/THEME/XXX.html');
     * 如果$smarty->template_dir目录没有按模块分目录, 设置module为空字符串即可,
     * $smarty->display('XXX.html')将自动解析为$smarty->display('THEME/XXX.html');
     *
     * @param $module string
     * @return Zeed_Smarty
     */
    public function setModule($module)
    {
        $this->_module = $module;
        return $this;
    }
    
    /**
     *
     * @return Zeed_Smarty
     */
    public function resetModule()
    {
        $this->_module = null;
        return $this;
    }
    
    /**
     * 设置风格
     *
     * @param $theme string
     */
    public function setTheme($theme)
    {
        $this->_theme = $theme;
        return $this;
    }
    
    /**
     *
     * @return Zeed_Smarty
     */
    public function resetTheme()
    {
        $this->_theme = null;
        return $this;
    }
    
    /**
     * Overrider Smarty中的Display方法, 输出页面或获取解析好的内容
     *
     * @param $resource_name String
     * @param $cache_id String
     * @param $compile_id String
     * @param $display Boolean
     * @return String
     */
    public function display($resource_name = null, $cache_id = null, $compile_id = null, $parent = null, $display = true)
    {
        /**
         * 只有文件模式的模板才支持主题模板
         * 当使用自定义的Smarty Resource时跳过主题检测直接显示
         */
        if (! preg_match('/^[a-z]{1,}:/i', $resource_name) && ! file_exists($absfile = $this->_findTemplateFile($resource_name))) {
            if (! is_null($this->_module) && $this->_module != '' && strpos($resource_name, $this->_module . '/') !== 0) {
                if (! is_null($this->_theme)) {
                    $filename = $this->_module . '/' . $this->_theme . '/' . $resource_name;
                    $absfile = $this->_findTemplateFile($filename);
                    if (file_exists($absfile)) {
                        $resource_name = $filename;
                    } else {
                        $resource_name = $this->_module . '/' . $this->_defaultTheme . '/' . $resource_name;
                    }
                } else {
                    $resource_name = $this->_module . '/' . $this->_defaultTheme . '/' . $resource_name;
                }
            } else if (! is_null($this->_module) && $this->_module == '') {
                if (! is_null($this->_theme)) {
                    $filename = $this->_theme . '/' . $resource_name;
                    $absfile = $this->_findTemplateFile($filename);
                    if (file_exists($absfile)) {
                        $resource_name = $filename;
                    } else {
                        $resource_name = $this->_defaultTheme . '/' . $resource_name;
                    }
                } else {
                    $resource_name = $this->_defaultTheme . '/' . $resource_name;
                }
            }
        }
        if (! $display || (! headers_sent() && $this->gzOutput)) {
            $content = parent::fetch($resource_name, $cache_id, $compile_id, $parent, false);
            
            if (! $display) {
                return $content;
            } else {
                $this->gzPrint($content);
            }
        } else {
            parent::display($resource_name, $cache_id, $compile_id, $parent);
        }
        
        return '';
    }
    
    /**
     * 在设置的模板模板中查找模板的磁盘绝对路径.
     * 兼容Smarty2/Smarty3.
     *
     * @param $filename string
     * @return string
     */
    private function _findTemplateFile($filename)
    {
        if (is_string($this->template_dir)) {
            if (file_exists($return = $this->template_dir . $filename)) {
                return $return;
            }
        } elseif (is_array($this->template_dir) && count($this->template_dir)) {
            foreach ($this->template_dir as $_dir) {
                if (file_exists($return = $_dir . $filename)) {
                    return $return;
                }
            }
        }
        
        return $filename;
    }
    
    /**
     * 使用gzip压缩输出页面
     *
     * @param $text String
     * @param $level Integer
     */
    public function gzPrint($text, $level = 6)
    {
        $returntext = $text;
        
        if (function_exists("crc32") and function_exists("gzcompress") and $this->gzOutput) {
            if (strpos(" " . $_SERVER[HTTP_ACCEPT_ENCODING], "x-gzip")) {
                $encoding = "x-gzip";
            }
            if (strpos(" " . $_SERVER[HTTP_ACCEPT_ENCODING], "gzip")) {
                $encoding = "gzip";
            }
            if ($encoding) {
                header("Content-Encoding: $encoding");
                $size = strlen($text);
                $crc = crc32($text);
                $returntext = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
                $returntext .= substr(gzcompress($text, $level), 0, - 4);
                $returntext .= pack("V", $crc);
                $returntext .= pack("V", $size);
            }
        }
        
        echo $returntext;
    }
    
    /**
     * 保存解析好的内容
     *
     * @param $resource_name string
     * @param $filepath string
     * @return boolean
     */
    public function save($resource_name, $filepath)
    {
    }
    public function packJavaScript($content)
    {
    }
    
    /**
     * Overrider Smarty中的assign方法
     */
    public function assign_stripslashes($k, $v = null, $nocache = false, $scope = SMARTY_LOCAL_SCOPE)
    {
        if (is_array($k)) {
            return parent::assign(ss($k), null, $nocache, $scope);
        } else {
            return parent::assign($k, ss($v), $nocache, $scope);
        }
    }
    
    /**
     * 删除模板中已经赋予的值
     *
     * @param $keys Array
     * @return Zeed_Smarty
     * @return Zeed_Smarty
     */
    public function clear_vars_by_key($keys)
    {
        if (is_array($keys) && count($keys) > 0) {
            foreach ($keys as $k) {
                unset($this->_tpl_vars[$k]);
            }
        }
        
        return $this;
    }
}
function ss($v)
{
    if (get_magic_quotes_gpc()) {
        return is_array($v) ? array_map('ss', $v) : stripslashes($v);
    }
    
    return is_array($v) ? array_map('ss', $v) : $v;
}

/**
 * 适配URL前缀
 *
 * @param $source string
 * @return string
 */
function module_url_prefix($source = null)
{
    $modules = Zeed_Config::loadGroup('bootstrap.controllers');
    
    $search = $replace = array();
    foreach ($modules as $urlprefix => $folder) {
        $search[] = '/' . dirname($folder) . '/';
        $replace[] = '/' . $urlprefix . '/';
    }
    
    return str_replace($search, $replace, $source);
}
function taglib_plugin_handler($name, $type, $template, &$callback, &$script)
{
    switch ($type) {
        case Smarty::PLUGIN_FUNCTION :
            $taglibConf = Zeed_Config::loadGroup('taglib');
            if (isset($taglibConf[$name])) {
                $callback = array($taglibConf[$name]['class'], $taglibConf[$name]['function']);
                return true;
            } else {
                return false;
            }
        default :
            return false;
    }
}

// End ^ LF ^ encoding
