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
$user_info = $data['user_info'];
//var_dump($user_info);exit;
$birthday = $user_info['birthday'];
$year = substr($birthday,0,4);
$month = substr($birthday,5,2);
$day = substr($birthday,8,2);
$user_info['year'] = $year;
$user_info['month'] = $month;
$user_info['day'] = $day;
//header("content-type:text/html;charset=utf-8 ");
//var_dump($user_info);exit;

$smarty->assign($data);
$smarty->assign('user_info',$user_info);

$smarty->display('user.index.html');

// End ^ Native EOL ^ UTF-8