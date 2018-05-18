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

$parentid = $data['parentid'];
$groups = $data['groups'];

foreach ($groups as $k => $v) {
    if ($v['parentid'] < 1) {
        $parent_groups[] = $v;
    } else if ($v['parentid'] == $parentid) {
        $son_groups[] = $v;
    }
}
$groups_list = $parentid ? $son_groups : $parent_groups;
$smarty->assign("parentid", $parentid);
$smarty->assign("parent_groups", $parent_groups);
$smarty->assign("groups_list", $groups_list);

$smarty->display('group.index.html');

// End ^ Native EOL ^ UTF-8