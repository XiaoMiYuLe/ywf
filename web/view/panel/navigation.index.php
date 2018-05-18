<?php
require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$smarty->assign('navigations', $data['navigations']);

$smarty->display('navigation.index.html');

// End ^ LF ^ encoding
