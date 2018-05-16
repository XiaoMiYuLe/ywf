<?php

error_reporting(E_ALL & ~E_NOTICE);

@ini_set('memory_limit', '128M');
@ini_set('session.auto_start', 0);
@ini_set('session.cache_expire', 180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies', 1);
//是否输出错误, 正式线上产品设置为0
@ini_set('display_errors', 1); 
if (PATH_SEPARATOR === ':' || PATH_SEPARATOR === ';') {
    @ini_set('include_path', '{#framework_path_zend#}' . DIRECTORY_SEPARATOR . 'library'
            . PATH_SEPARATOR . '{#framework_path_zeed#}' . DIRECTORY_SEPARATOR . 'library');
}

date_default_timezone_set('PRC');

if (extension_loaded("mbstring")) {
    mb_internal_encoding('UTF-8');
}

// Global define
isset($_SERVER["REQUEST_TIME"]) || ($_SERVER["REQUEST_TIME"] = time());

defined('DATETIME_FORMAT') || define('DATETIME_FORMAT', 'Y-m-d H:i:s');
defined('TIMENOW') || define('TIMENOW', $_SERVER["REQUEST_TIME"]);
defined('DATENOW') || define('DATENOW', date(DATETIME_FORMAT, TIMENOW));

define('SMARTY_VERSION', 3);

// Library of project
if (is_dir($prjLib = dirname(__FILE__) . '/../library/')) {
    $prjLib = realpath($prjLib);
    set_include_path($prjLib . PATH_SEPARATOR . get_include_path());
    defined('ZEED_PATH_LIB') || define('ZEED_PATH_LIB', str_replace('\\', '/', $prjLib . '/'));
}

// Application path
if (defined('ZEED_PATH_APPS')) {
    set_include_path(ZEED_PATH_APPS . PATH_SEPARATOR . get_include_path());
}

// End ^ LF ^ encoding
