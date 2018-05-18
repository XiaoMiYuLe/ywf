<?php
require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$smarty->assign('navigations', $data['navigations']);
$smarty->assign('navigation', $data['navigation']);

$smarty->display('navigation.edit.html');

// End ^ LF ^ encoding
