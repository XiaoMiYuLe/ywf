<?php
/**
 * Bluemobi Project
 *
 * LICENSE
 *
 * http://www.bluemobi.cn/license/bm
 *
 * @category   BM
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano  ( GTalk: cyrano0919@gmail.com )
 * @since      July 6, 2015
 * @version    SVN: $Id$
 */

define('ZEED_BOOT', dirname(__FILE__) . '/');
define('ZEED_IN_PRODUCTION', 0);
define('ZEED_ROOT', str_replace('\\', '/', realpath(ZEED_BOOT . '../') . '/'));
define('ZEED_PAHT_BIN', ZEED_ROOT . 'bin/');
define('ZEED_PATH_APPS', ZEED_ROOT . 'application/');
define('ZEED_PATH_CONF', ZEED_ROOT . 'config/');
define('ZEED_PATH_VIEW', ZEED_ROOT . 'view/');
define('ZEED_PATH_DATA', ZEED_ROOT . 'data/');
define('ZEED_PATH_UPLOAD', ZEED_ROOT . 'upload/');

require_once 'install/setup.php';

if (file_exists(ZEED_BOOT . '/../config/env.php')) {
    include ZEED_BOOT . '/../config/env.php';
} else {
    error_reporting(E_ALL);
}

require_once 'Zeed.php';
require_once ZEED_PATH . 'Zeed/Loader' . EXT;
Zeed_Loader::registerAutoload();

Zeed::packageClass(array(
        'Zeed_Benchmark', 
        'Zeed_Request_Abstract',
        'Zeed_Request_Http',
        'Zeed_Controller_Front', 
        'Zeed_Controller_Dispatcher_Interface', 
        'Zeed_Controller_Dispatcher', 
        'Zeed_Controller_Action_Interface', 
        'Zeed_Controller_Action', 
        'Zeed_Controller_Router_Rewrite', 
        'Zeed_Controller_Router_Route_Module', 
        'Zeed_Controller_Request'), null, ZEED_IN_PRODUCTION);

Zeed_Benchmark::start('_total_execution');

try {
    $front = Zeed_Controller_Front::getInstance();
    $front->batchSetControllerDirectories(Zeed_Config::loadGroup('bootstrap'));
    $front->dispatch();
} catch (Zeed_Exception $e) {
    
    if (ini_get('display_errors')) {
        Zeed_Benchmark::print_r($e->toString());
    }
}

Zeed_Benchmark::stop('_total_execution');


// End ^ LF ^ UTF-8
