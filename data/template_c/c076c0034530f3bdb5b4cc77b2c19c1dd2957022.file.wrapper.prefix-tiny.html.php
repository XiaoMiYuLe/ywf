<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-08 15:11:13
         compiled from "/usr/local/httpd/htdocs/php/view/panel/template/wrapper.prefix-tiny.html" */ ?>
<?php /*%%SmartyHeaderCode:155875133757f89c11054a72-16071863%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c076c0034530f3bdb5b4cc77b2c19c1dd2957022' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/panel/template/wrapper.prefix-tiny.html',
      1 => 1475056750,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '155875133757f89c11054a72-16071863',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_STORE_URL' => 0,
    '_STATIC_URL' => 0,
    '_STATIC_CDN' => 0,
    '_SITE_NAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f89c11067e36_95916389',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f89c11067e36_95916389')) {function content_57f89c11067e36_95916389($_smarty_tpl) {?><!DOCTYPE html>
<html lang="zh-CN" class="app">
<head>
<script type="text/javascript" language="JavaScript">
    var STORE_URL = '<?php echo $_smarty_tpl->tpl_vars['_STORE_URL']->value;?>
';
    var STATIC_URL = '<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
';
    var STATIC_CDN = '<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
';
</script>
<?php echo $_smarty_tpl->getSubTemplate ("panel/template/inc/header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- USER DEFINED ASSETS -->
<!-- USER DEFINED ASSETS END@-->
<title><?php echo $_smarty_tpl->tpl_vars['_SITE_NAME']->value;?>
</title>
</head><?php }} ?>