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

$smarty->assign('loginCaptchaId', $this->captchaId);
$smarty->assign('login_username', $this->input->post('username'));
$smarty->assign('loginerror', $this->error);
$smarty->assign('continue', $this->continue);

$msg = $this->input->query('msg');
$sig = $this->input->query('sig');
if (empty($msg) || md5($msg.'@Wang#Wu#Wang@') != $sig) {
    $msg = false;
    $sig = false;
}
$smarty->assign('loginmsg', $msg);
$smarty->assign('loginsig', $msg);

$smarty->display('sign.in.html');

// End ^ Native EOL ^ UTF-8