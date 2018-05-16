<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-09 16:13:21
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/forgotpassword.resetpassword.html" */ ?>
<?php /*%%SmartyHeaderCode:197637785057f9fc21e5bbb5-04686971%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33853c4ad433a93e9a73b889b9ca3d6fcbb6590e' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/forgotpassword.resetpassword.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '197637785057f9fc21e5bbb5-04686971',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix' => 0,
    '_STATIC_URL' => 0,
    'wrapper_suffix' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f9fc21ea0cc2_81166716',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f9fc21ea0cc2_81166716')) {function content_57f9fc21ea0cc2_81166716($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix']->value)===null||$tmp==='' ? '' : $tmp);?>


<script>$("head>title").html("忘记密码");</script>
<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/ywf/webjs/cas/forgotpassword.resetpassword.js" type="text/javascript"></script>
<div class="login_content find reset">
    <div class="register">
        <div class="reg_right"> 
            <h1 class="reg_title find_title">
                <span>重置登录密码</span>
            </h1>	
            <div class="reg_process">
                <ul>
                    <li style="text-align: left;" class="current"><span>验证身份</span></li>
                    <li><span>重置登录密码</span></li>
                    <li style="text-align: right;"><span>重置成功</span></li>
                </ul>
            </div> 	
            <div class="reg_from" style="margin-top:20px;">
                <form action="" method="post" target="" class="_form" autocomplete="" enctype="multipart/form-data">
                    <div class="val_group">
                        <label class="val_label" for="password">登录密码</label>
                        <div class="val">
                            <input type="password" id="oldpassword" placeholder="请输入6-16位字符密码" class="input_text " >
                        </div>
                    </div>

                    <div class="val_group">
                        <label class="val_label" for="mobile">确认密码</label>
                        <div class="val">
                            <input type="password" id="newpassword" placeholder="请再次输入密码" class="input_text">
                        </div>
                    </div>

                    <div class="val_action val_action2 ">
                        <input type="button" id="form-submit" name="name" class="btn" value="下一步">
                    </div>

                </form>
            </div>
        </div>
        <div class="reg_left"></div>        	
    </div>   	
</div>
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix']->value)===null||$tmp==='' ? '' : $tmp);?>

<?php }} ?>