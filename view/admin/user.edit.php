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

$smarty->assign("userid", $data['userid']);
$smarty->assign("user", $data['user']);
$smarty->assign("groups", $data['groups']);
$smarty->assign("user_groupids", isset($data['user_groupids']) ? $data['user_groupids'] : array());

$smarty->display('user.edit.html');
