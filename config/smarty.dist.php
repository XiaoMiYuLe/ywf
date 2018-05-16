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
 * @since      Jul 6, 2010
 * @version    SVN: $Id$
 */

/**
 * 是否开启调试模式, 只有调用$smarty->display()显示内容时才会弹出调试窗体
 */
$config['debugging'] = false;

/**
 * 是否使用SMARTY缓存.
 * 默认: false
 */
$config['caching'] = false;

/**
 * 缓存生命周期, 单位为秒.
 * 默认: 90
 */
$config['cache_lifetime'] = 90;

/**
 * SMARTY缓存保存目录
 */
$config['cache_dir'] = ZEED_PATH_DATA . 'tmp';

/**
 * 模板开始目录.
 * 默认: ZEED_PATH_VIEW, 即:全局视图所在目录.
 */
$config['template_dir'] = ZEED_PATH_VIEW;

/**
 * 模板编译(解析成PHP代码)缓存文件目录.
 * 默认: ZEED_PATH_DATA . 'template_c/'
 */
$config['compile_dir'] = ZEED_PATH_DATA . 'template_c/';

/**
 * 模板允许的自定义配置加载开始目录.
 * 默认: ZEED_PATH_VIEW, 即:全局视图所在目录.
 */
$config['config_dir'] = ZEED_PATH_VIEW;

/**
 * 是否允许执行PHP, 默认为0
 * @ee Smarty::PHP_ALLOW
 * @see http://www.smarty.net/docs/zh_CN/advanced.features.tpl
 */
$config['php_handling'] = 3;

/**
 * 自定义SMARTY插件目录, 需要是全路径.
 * 配置：$config['plugins_dir'] = array('plugins', ZEED_PATH_LIB . 'smarty/plugins');
 */


/**
 * 扩展配置, 是否使用Gzip输出页面.
 * 如果是SSI页面, 并且使用了Apache的压缩模块, 会出现问题.
 * 默认: false
 */
$config['gz_output'] = false;

/**
 * 配置主题
 * 默认为 template
 */
$config['theme'] = array(
        'admin' => 'template',
        'frontend' => 'template',
        'cas' => 'template'
);

/**
 * 站点URI地址信息, 模板中赋值
 */
if (file_exists(dirname(__FILE__) . '/urlmapping.php')) {
    $urlmapping = Zeed_Config::loadGroup('urlmapping');
    $config['auto_assign_vars'] = array(
            '_SITE_NAME' => $urlmapping['site_name'], 
            '_STORE_URL' => $urlmapping['store_url'], 
            '_STORE_URL_LOGIN' => $urlmapping['store_url_login'],
            '_STATIC_URL' => $urlmapping['static_url'], 
            '_STATIC_CDN' => $urlmapping['static_cdn']);
}

return $config;

// End ^ LF ^ UTF-8
