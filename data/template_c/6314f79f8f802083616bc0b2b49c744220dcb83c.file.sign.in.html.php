<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-08 15:11:13
         compiled from "/usr/local/httpd/htdocs/php/view/admin/template/sign.in.html" */ ?>
<?php /*%%SmartyHeaderCode:85126509957f89c111451a1-71847838%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6314f79f8f802083616bc0b2b49c744220dcb83c' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/admin/template/sign.in.html',
      1 => 1475056723,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '85126509957f89c111451a1-71847838',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_SITE_NAME' => 0,
    '_STATIC_CDN' => 0,
    '_STATIC_URL' => 0,
    'login_username' => 0,
    'continue' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f89c1116a977_57449998',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f89c1116a977_57449998')) {function content_57f89c1116a977_57449998($_smarty_tpl) {?><!DOCTYPE html>
<html lang="zh-CN" class="bg-dark js no-touch no-android no-chrome firefox no-iemobile no-ie no-ie10 no-ie11 no-ios">
<head>
	<meta charset="utf-8" />
	<title><?php echo $_smarty_tpl->tpl_vars['_SITE_NAME']->value;?>
</title>
	<meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/bootstrap/3.1.0/css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/panel/css/animate.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/panel/css/apps.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/admin/css/sign.css" type="text/css"/>
	
	<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery/1.11.0/jquery.min.js"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-form/3.03/jquery.form.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/admin/js/sign.js"></script>
</head>
<body>

<a class="navbar-brand block" href="/admin"><?php echo $_smarty_tpl->tpl_vars['_SITE_NAME']->value;?>
</a>

<section id="aui_iwrapper" class="animated fadeInUp aui-iwrapper">
    <section class="panel panel-default bg-white m-t-lg">
        <header class="panel-heading text-center">
            <strong>登录</strong>
        </header>
        <form action="/admin/sign/login" id="form_sign"  class="panel-body wrapper-lg" method="post" title="登录" data-validate="parsley">
            <div class="form-group">
                <label class="control-label">用户名</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="用户名" data-maxlength="20" data-minlength="3" data-required="true" data-trigger="change" value="<?php echo $_smarty_tpl->tpl_vars['login_username']->value;?>
" />
                <span class="notice notice-username"></span>
            </div>
            <div class="form-group">
                <label class="control-label">密码</label>
                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="密码" data-required="true" data-trigger="change" />
                <span class="notice notice-password"></span>
            </div>
            <div class="form-group">
                <label class="control-label">验证码</label>
                <input type="text" name="captcha" class="captcha form-control" id="login-captcha" placeholder="验证码" data-maxlength="6"  data-required="true" data-trigger="change" autocomplete="off" />
                <span class="notice notice-captcha"></span>
            </div>
            <div class="form-group">
                <img src="/admin/Captcha/image?" alt="点击换一张" id="vcodeimg" title="看不清楚，换一张" style="cursor:pointer;" onclick="this.src='/admin/Captcha/image?t='+Math.random();" >
            </div>

            <button type="submit" id="sign_submit" class="btn btn-primary">登录</button>
            <button type="reset" class="btn btn-info">重置</button>
            <input type="hidden" name="continue" value="<?php echo $_smarty_tpl->tpl_vars['continue']->value;?>
" />
            <div class="line line-dashed"></div>
            <h4 class="text text-danger notice-back"></h4>
        </form>
    </section>
</section>

<!-- footer -->
<footer id="footer">
    <div class="text-center padder">
        <p>
            <small>上海科匠信息科技有限公司<br>&copy; 2014</small>
        </p>
    </div>
</footer>

</body>
</html><?php }} ?>