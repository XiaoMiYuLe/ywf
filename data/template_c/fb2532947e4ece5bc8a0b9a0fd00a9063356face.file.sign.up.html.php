<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 16:03:40
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/sign.up.html" */ ?>
<?php /*%%SmartyHeaderCode:185695906257f9eda5cfc318-82694688%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb2532947e4ece5bc8a0b9a0fd00a9063356face' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/sign.up.html',
      1 => 1476172992,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185695906257f9eda5cfc318-82694688',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f9eda5d41277_29876803',
  'variables' => 
  array (
    'wrapper_prefix' => 0,
    '_STATIC_CDN' => 0,
    'wrapper_suffix' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f9eda5d41277_29876803')) {function content_57f9eda5d41277_29876803($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/signup.js"></script>

<script>$("head>title").html("注册");</script>
<div style="clear:both"></div>

<div class="login_content">
    <div class="register">
        <div class="reg_right">
            <h1  class="reg_title">注册
                <div class="reg_link" style="float:right;font-size:14px;position: absolute;top: 23px;right: 14px;">
                    已注册，<a href="/cas/sign/in" style="color:#2197d7;">免费登陆</a>
                </div>
            </h1>   	 	
            <div class="reg_from  yreg_from
                 ">
                <form class="_form" >
                    <div class="val_group">
                        <label class="val_label" for="mobile">手机号</label>
                        <div class="val">
                            <input type="text" id="phone" value="" placeholder="请输入您的手机号" value="" class="input_text " >
                        </div>
                    </div>

                    <div class="val_group" >
                        <label class="val_label ">图形验证码</label>
                        <div class="val">
                            <input placeholder="请输入图形验证码" name="imgcode" value="" class="input_text input_sep" type="text">
                            <img onclick="this.src='/cas/Captcha/image?t='+Math.random();" style="cursor:pointer;margin-left: 10px;" title="看不清楚，换一张" id="vcodeimg" src="/cas/Captcha/image?">
                        </div>
                    </div>
                    
                    <div class="val_group" >
                        <label class="val_label ">验证码</label>
                        <div class="val">
                            <input type="text" id="code" value="" placeholder="请输入短信验证码" class="input_text input_sep">
                            <input type="button" class="btn get_verify" id="sendCode" value="获取验证码">
                        </div>
                    </div>

                    <div class="val_group">
                        <label class="val_label" for="password">登录密码</label>
                        <div class="val">
                            <input type="password"  id="pwd" value="" placeholder="请输入6-16位字符密码" class="input_text " >
                        </div>
                    </div>

                    <div class="val_group">
                        <label class="val_label" for="mobile">确认密码</label>
                        <div class="val">
                            <input type="password" id="repwd" value=""  placeholder="请再次输入密码"class="input_text " >
                        </div>
                    </div>

                    <div class="val_group">
                        <label class="val_label" for="mobile">推荐人(选填)</label>
                        <div class="val">
                            <input type="text" id="recommender"  value="" placeholder="请输入推荐的手机号或推荐码"class="input_text " >
                        </div>
                    </div>
                    <div class="val_action val_action1">
                        <input type="button" name="name" id="sub" class="btn" value="完成注册">
                    </div>
                    <div class="free_register free_register1">
                        注册即同意<a href="">《亿万福网站服务协议》</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="reg_left"></div>        	
    </div>   	
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>