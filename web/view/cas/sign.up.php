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

$smarty->assign('captchaId', $this->captchaId);
$smarty->assign('error', $this->error);
$smarty->assign('continue', $this->continue);
$smarty->assign('phone', $this->input->post('phone'));
$smarty->assign('realname', $this->input->post('realname'));

$smarty->display('sign.up.html');

// End ^ Native EOL ^ UTF-8