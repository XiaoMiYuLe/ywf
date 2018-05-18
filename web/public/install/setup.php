<?php

/**
 * 校验是否已安装
 */
require_once 'CheckInstall.php';

if (! Install_CheckInstall::run()) {
    header('location:install');
}
