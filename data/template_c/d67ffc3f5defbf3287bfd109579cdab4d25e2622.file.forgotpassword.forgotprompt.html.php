<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-09 16:13:32
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/forgotpassword.forgotprompt.html" */ ?>
<?php /*%%SmartyHeaderCode:86500814257f9fc2c1c1109-49689160%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd67ffc3f5defbf3287bfd109579cdab4d25e2622' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/forgotpassword.forgotprompt.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '86500814257f9fc2c1c1109-49689160',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix' => 0,
    'wrapper_suffix' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f9fc2c204058_82104368',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f9fc2c204058_82104368')) {function content_57f9fc2c204058_82104368($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix']->value)===null||$tmp==='' ? '' : $tmp);?>


<script>$("head>title").html("找回成功");</script>
<div class="login_content reminder">
    <div class="register">
        <div class="reg_right">
            <div class="sucess">
                <h2></h2>
                <span>密码设置成功！</span>
                <p>您的密码已经成功设置，<strong id="timer"></strong> 秒后会跳转到首页。</p>
                <a href="/index/index">立即跳转</a>
            </div>	         	  
        </div>
        <div class="reg_left"></div>
    </div>   	
</div>


<script>
    $(document).ready(function () {
        var nums = 4;
        var numnow;
        $("#timer").html(4);
        var ss = setInterval(function () {
            nums--;
            numnow = nums;
            $("#timer").html(numnow);
            if (nums == 0) {
                clearInterval(ss);
                window.location.href='/index/index';
            }
        }, 1000)
    });
</script>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>