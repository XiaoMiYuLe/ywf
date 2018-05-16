<?php
require_once dirname(__FILE__) . '/view.init.php';

$smarty->assign('continue', $this->continue);

$msg = $this->input->query('msg');
$smarty->assign('loginmsg', $msg);

$smarty->display('sign.in.html');