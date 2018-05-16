<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 15:57:08
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/resetpassword.resetpaypwd.html" */ ?>
<?php /*%%SmartyHeaderCode:68425359257fc9b543b8f71-97559147%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c322995b8f3dce3f0aa9c3a8a0899752c779c6b4' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/resetpassword.resetpaypwd.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '68425359257fc9b543b8f71-97559147',
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
  'unifunc' => 'content_57fc9b543fe3b7_75043924',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fc9b543fe3b7_75043924')) {function content_57fc9b543fe3b7_75043924($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/ywf/webjs/cas/resetpassword.resetpaypwd.js" type="text/javascript"></script>

<script>$("head>title").html("修改交易密码");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:630px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">修改交易密码</span>
        </div>
        <div class="two_xgdlmm">
            <div id="div1">
                <label>原交易密码：</label>
                <input type="password" name="oldpassword" value="" maxlength="6" placeholder="请输入旧交易密码" id="input1"/>
            </div>
            <div>
                <label>新交易密码：</label>
                <input type="password" name="newpassword" value="" maxlength="6" placeholder="请输入6位纯数字" id="input2"/>
            </div>
            <div>
                <label>确认新交易密码：</label>
                <input type="password" name="newpasswordOne" value="" maxlength="6" placeholder="请确认新密码" id="input3"/>
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