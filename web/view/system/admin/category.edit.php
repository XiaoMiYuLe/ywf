<?php
require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$smarty->assign($data);

$smarty->display('category.edit.html');

// End ^ LF ^ encoding
