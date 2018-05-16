<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-08 15:17:02
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/sign.in.html" */ ?>
<?php /*%%SmartyHeaderCode:108649429057f89d6f001657-30552141%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '67b493d569fff990f382d973247dd20bee4afabf' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/sign.in.html',
      1 => 1475056733,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '108649429057f89d6f001657-30552141',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix' => 0,
    '_STATIC_CDN' => 0,
    'wrapper_suffix' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f89d6f046af2_66158842',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f89d6f046af2_66158842')) {function content_57f89d6f046af2_66158842($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/sign.in.js"></script>

<script>$("head>title").html("登录");</script>

<div class="login_content">
    <div class="register">
        <div class="reg_left"></div>
        <div class="reg_right">
            <h1  class="reg_title">登录</h1>
            <div class="reg_from">
                <form class="_form" id="form" action="/cas/sign/in" method="POST" enctype="multipart/form-data">
                    <div class="val_group">
                        <label class="val_label" for="mobile"></label>
                        <div class="val">
                            <input type="text" name="phone" id="phone" placeholder="请输入您的手机号" class="input_text input_text1" >
                        </div>
                    </div>
                    <div class="val_group">
                        <label class="val_label" for="password"></label>
                        <div class="val">       
                            <input type="password" name="password" id="password" placeholder="请输入登录密码" class="input_text input_text2" >
                        </div>
                    </div>
                    <div class="val_group" >
                        <label class="val_label hidden"></label>
                        <div class="val">
                            <input type="text"  placeholder="请输入验证码" name="code" value="" class="input_text input_sep">
                            <img onclick="this.src='/cas/Captcha/image?t='+Math.random();" style="cursor:pointer;margin-left: 10px;" title="看不清楚，换一张" id="vcodeimg" src="/cas/Captcha/image?">
                        </div>
                    </div>
                    <div class="val_group">
                        <div class="val" style="overflow: hidden;">
                            <p class="fgtpwd"><a href="/cas/forgotpassword">忘记密码?</a></p>
                        </div>
                    </div>
                    <div class="val_action">
                        <input type="button" class="btn input-submit" id="form-submit" value="登录">
                    </div>
                    <div class="free_register">
                        没有账号？<a href="/cas/sign/up">免费注册</a>
                    </div>
                </form>
            </div>
        </div>
    </div>   	
</div>
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>