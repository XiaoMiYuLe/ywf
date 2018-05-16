<?php
require_once dirname(__FILE__) . '/view.init.php';

$smarty->assign('groupPermissions', $this->groupPermissions);
$smarty->assign('groupinfo', current($this->groupinfo));
$smarty->assign('allApps', $this->allApps);
$smarty->assign('appkey', $this->appkey);
$smarty->assign('permissions', $this->permissions);

$smarty->display('permissionassign.assign.4group.html');