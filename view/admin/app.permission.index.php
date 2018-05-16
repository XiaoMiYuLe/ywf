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
 * @since      2011-3-21
 * @version    SVN: $Id$
 */

require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$smarty->assign("app_now", $data['app_now']);
$smarty->assign("apps", $data['apps']);
$smarty->assign("permissions", $data['permissions']);
$smarty->assign("app_permissions", $data['app_permissions']);
$smarty->assign("ordername", $data['ordername']);
$smarty->assign("orderby", $data['orderby']);
$smarty->assign("page", $data['page']);
$smarty->assign("perpage", $data['perpage']);
$smarty->assign("count", $data['count']);

$smarty->display('app.permission.index.html');

// End ^ Native EOL ^ UTF-8