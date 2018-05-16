<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-14 14:38:58
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/setting.tradepwd.html" */ ?>
<?php /*%%SmartyHeaderCode:206609075058007d8210f277-05662565%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9218c3445eb4040dfb71b44bef68865dd7bafc5b' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/setting.tradepwd.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '206609075058007d8210f277-05662565',
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
  'unifunc' => 'content_58007d82152f85_22539894',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58007d82152f85_22539894')) {function content_58007d82152f85_22539894($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/ywf/webjs/cas/setting.tradepwd.js" type="text/javascript"></script>

<script>$("head>title").html("设置交易密码");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:630px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">设置交易密码</span>
        </div>

        <div style="margin-top: 8px;">交易密码用于保障您的账户安全</div>
        <div class="two_xgdlmm">
            <div class="" id="div1">
                <label>交易密码：</label>
                <input type="password" name="pay_pwd" value="" placeholder="6位数字" minlength="6" maxlength="6" id="input1"/>
            </div>
            <div class="">
                <label>确认密码：</label>
                <input type="password" name="repeatpay_pwd" value="" placeholder="请再次输入密码" minlength="6" maxlength="6" id="input2"/>
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