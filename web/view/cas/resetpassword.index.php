<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');
$smarty->assign($data);

$smarty->display('resetpassword.index.html');

// End ^ Native EOL ^ UTF-8
