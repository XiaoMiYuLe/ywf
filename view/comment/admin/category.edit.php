<?php
require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$smarty->assign('categories', $data['categories']);
$smarty->assign('category', $data['category']);

$smarty->display('category.edit.html');

// End ^ LF ^ encoding
