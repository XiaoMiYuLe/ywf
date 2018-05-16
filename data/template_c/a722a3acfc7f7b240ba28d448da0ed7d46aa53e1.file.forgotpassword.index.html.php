<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 19:14:45
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/forgotpassword.index.html" */ ?>
<?php /*%%SmartyHeaderCode:149565904357f9fc05031ef1-16609726%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a722a3acfc7f7b240ba28d448da0ed7d46aa53e1' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/forgotpassword.index.html',
      1 => 1476184467,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '149565904357f9fc05031ef1-16609726',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f9fc05089f20_11949597',
  'variables' => 
  array (
    'wrapper_prefix' => 0,
    '_STATIC_URL' => 0,
    'wrapper_suffix' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f9fc05089f20_11949597')) {function content_57f9fc05089f20_11949597($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/ywf/webjs/cas/forgotpassword.index.js" type="text/javascript"></script>

<script>$("head>title").html("忘记密码");</script>
<div class="login_content find">
    <div class="register">
        <div class="reg_right"> 
            <h1 class="reg_title find_title">
                <span>短信找回密码</span>
            </h1>	
            <div class="reg_process">
                <ul>
                    <li style="text-align: left;" class="current"><span>验证身份</span></li>
                    <li><span>重置登录密码</span></li>
                    <li style="text-align: right;"><span>重置成功</span></li>
                </ul>
            </div> 	
            <div class="reg_from" style="margin-top:20px;">
                <form action="/cas/forgotpassword/index" method="post" target="" autocomplete="" class="_form" enctype="multipart/form-data">
                    <div class="val_group">
                        <label class="val_label" for="mobile">手机号</label>
                        <div class="val">
                            <input type="text" id="phone" placeholder="请输入您的手机号"class="input_text " >
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
                        <label class="val_label ">短信验证码</label>
                        <div class="val">
                            <input type="text" class="input_text input_sep" placeholder="请输入验证码" value="" id="code">
                            <input type="button" value="获取验证码" id="sendCode" class="btn get_verify">
                        </div>
                    </div>			
                    <div class="val_action val_action2 ">
                        <input type="button" name="next" id="form-submit" class="btn" value="下一步">
                    </div>

                </form>
            </div>
        </div>
        <div class="reg_left"></div>        	
    </div>   	
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>