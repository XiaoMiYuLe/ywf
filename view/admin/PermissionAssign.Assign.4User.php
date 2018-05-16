<?php
require_once dirname(__FILE__) . '/view.init.php';

$smarty->assign('selfPermissionids', $this->selfPermissionids);
$smarty->assign('groupPermissions', $this->groupPermissions);
$smarty->assign('userinfo', current($this->userinfo));
$smarty->assign('allApps', $this->allApps);
$smarty->assign('appkey', $this->appkey);
$smarty->assign('permissions', $this->permissions);

$smarty->display('permissionassign.assign.4user.html');