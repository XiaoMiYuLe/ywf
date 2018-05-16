<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-12 13:25:51
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/resetpassword.index.html" */ ?>
<?php /*%%SmartyHeaderCode:65848399757fdc95f90c675-61949960%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '782d55210f9f6df408713dcff787d87c21955817' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/resetpassword.index.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '65848399757fdc95f90c675-61949960',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_URL' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fdc95f94fee4_36740744',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fdc95f94fee4_36740744')) {function content_57fdc95f94fee4_36740744($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/ywf/webjs/cas/resetpassword.index.js" type="text/javascript"></script>

<script>$("head>title").html("修改登录密码");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:630px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">修改登录密码</span>
        </div>
        <div class="two_xgdlmm">
            <div class="" id="div1">
                <label>原登录密码：</label>
                <input type="password" name="oldpassword" value="" placeholder="请输入旧登录密码" id="input1"/>
            </div>
            <div class="">
                <label>新登录密码：</label>
                <input type="password" name="newpassword" value="" placeholder="6-16位，同时包含数字和字母两种" id="input2"/>
            </div>
            <div class="">
                <label>确认新登录密码：</label>
                <input type="password" name="newpasswordOne" value="" placeholder="请确认新密码" id="input3"/>
            </div>
            <div class="btn">
                <button id="form-submit">确认</button>
            </div>
        </div>
    </div>
</div>
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>