<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 15:57:20
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/resetpassword.forgotpaypwd.html" */ ?>
<?php /*%%SmartyHeaderCode:108874694257fc9b60dba271-52217309%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a656016e1764ce1f6d242d2c346972bca79ea448' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/resetpassword.forgotpaypwd.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '108874694257fc9b60dba271-52217309',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_URL' => 0,
    'phone' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fc9b60e02a18_16084564',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fc9b60e02a18_16084564')) {function content_57fc9b60e02a18_16084564($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/ywf/webjs/cas/resetpassword.forgotpaypwd.js" type="text/javascript"></script>

<script>$("head>title").html("忘记交易密码");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:630px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">找回交易密码</span>
        </div>
        <div class="two_xgdlmm">
            <div id="div1">
                <label>身份证号码：</label>
                <input type="text" value="" name="test_idcard" placeholder="请输入身份证号码" id="zhjymm_input1"/>
                <span>*未绑卡则不填写</span>
            </div>
            <input type="hidden" id="phone" value="<?php echo $_smarty_tpl->tpl_vars['phone']->value;?>
" />
            <div>
                <label>新交易密码：</label>
                <input type="password" value="" name="pay_pwd" placeholder="请输入6位纯数字" maxlength="6" id="zhjymm_input2"/>
            </div>
            <div>
                <label>确认新交易密码：</label>
                <input type="password" value="" name="repeatpay_pwd" placeholder="请确认新密码" maxlength="6" id="zhjymm_input3"/>
            </div>
            <div>
                <label>短信验证码：</label>
                <input type="text" value="" name="code" placeholder="请输入验证码" id="zhjymm_input4"/>
                <button id="sendCode">获取验证码</button>
            </div>
            <div class="btn">
                <button id="form-submit" style="margin-left:1px">确认</button>
            </div>
        </div>
    </div>
</div>
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>