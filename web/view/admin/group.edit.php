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

$group = $data['group'];
$parent_groups = $data['parent_groups'];

$selected_groupid = $group['parentid'] > 0 ? $group['parentid'] : $group['groupid'];

$smarty->assign("group", $group);
$smarty->assign("parent_groups", $parent_groups);
$smarty->assign("selected_groupid", $selected_groupid);

$smarty->display('group.edit.html');
