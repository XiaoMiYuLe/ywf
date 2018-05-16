<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-18 00:14:47
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/index.index.html" */ ?>
<?php /*%%SmartyHeaderCode:11712296405804f8f7bbcac4-72626155%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2ea0fce93b49b60227a32ad773f2763dee400540' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/index.index.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11712296405804f8f7bbcac4-72626155',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_STATIC_CDN' => 0,
    'user_code' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5804f8f7c00e26_74754322',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5804f8f7c00e26_74754322')) {function content_5804f8f7c00e26_74754322($_smarty_tpl) {?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>亿万福</title>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/cas/css/style.css">
<script src="/static/scripts/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
<script src="/static/scripts/jquery-form/3.03/jquery.form.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/cas/js/sign.up.js"></script>
</head>
<body style="background:#fff">
<header>
	<a href="" class="return"></a>
    <div class="title">注册</div>
</header>
<div class="headerDiv"><!--头部浮动菜单占位--></div>
<form name="form1" id="form1" action="/cas/signup/add" method="post">
<section class="regiest">
	<dl>
    	<dt><span>*</span>手机号码</dt>
        <dd><input class="txt" name="phone" type="text" value="" id="phone" placeholder="请输入手机号码"></dd>
    </dl>
    <dl>
    	<dt><span>*</span>短信验证码</dt>
        <dd><input class="yzbtn" name="" type="button" id="sendCode" value="发送验证码" ><div style="margin-right:102px;"><input class="txt" name="code" id="code" type="text" value="" placeholder="输入验证码"></div></dd>
    </dl>
    <dl>
    	<dt><span>*</span>登录密码</dt>
        <dd><input class="txt" name="pwd" type="text" id="pwd" value="" placeholder="6~18位，至少包括数字、字母中的两种"></dd>
    </dl>
    <dl>
    	<dt><span>*</span>确认登录密码</dt>
        <dd><input class="txt" name="repwd" id="repwd" type="text" value="" placeholder="请确认登录密码"></dd>
    </dl>
    <dl style="margin:0;">
    	<dt><div>推荐人(如有)</div></dt>
        <dd><input class="txt" name="recommender" id="recommender" type="text" value="<?php echo $_smarty_tpl->tpl_vars['user_code']->value;?>
" placeholder="请输入推荐人的手机号或推荐码"></dd>
    </dl>
            <div style="height:15px;">


        </div>
     <input class="btn" name="Submit" id="sub" type="button" value="立即注册">
</section>
</form>
</body>
</html>
<?php }} ?>