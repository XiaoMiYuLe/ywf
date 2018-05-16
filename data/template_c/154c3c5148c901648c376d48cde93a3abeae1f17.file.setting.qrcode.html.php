<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-10 09:03:52
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/setting.qrcode.html" */ ?>
<?php /*%%SmartyHeaderCode:179220541057fae8f80b6591-77581466%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '154c3c5148c901648c376d48cde93a3abeae1f17' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/setting.qrcode.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '179220541057fae8f80b6591-77581466',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fae8f81018a2_94356904',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fae8f81018a2_94356904')) {function content_57fae8f81018a2_94356904($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go_top.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/alert.js"></script>

<!--引入二维码生成jquery qrcode-->
<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/qrcode/jquery.qrcode.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/setting.qrcode.js"></script>

<script>$("head>title").html("邀请好友");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:472px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">邀请好友加入</span>
        </div>
        <div class="two_inviteFriends">
            <div id="code" class="img_ewm"></div>
            <div class="text">
                <p>推荐须知：</p>
                <p>请扫描二维码注册下载亿万福APP邀请好友</p>
            </div>
        </div>
        <span style="color:#808080;margin-left:48px;display: inline-block;margin-top:26px;font-size:18px">你的推荐码:<span id="sp"></span></span>
    </div>
</div>
</div>
<!--回到顶部开始-->
<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>
<!--回到顶部结束-->

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>