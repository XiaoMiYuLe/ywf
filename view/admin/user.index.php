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

$groups = $data['groups'];

$parent_groups = array();
foreach ($groups as $k => $v) {
    if ($v['parentid'] < 1) {
        $parent_groups[] = $v;
    }
}

$smarty->assign("groupid", $data['groupid']);
$smarty->assign("users", $data['users']);
$smarty->assign("parent_groups", $parent_groups);
$smarty->assign("ordername", $data['ordername']);
$smarty->assign("orderby", $data['orderby']);
$smarty->assign("page", $data['page']);
$smarty->assign("perpage", $data['perpage']);
$smarty->assign("count", $data['count']);

$smarty->display('user.index.html');

// End ^ Native EOL ^ UTF-8