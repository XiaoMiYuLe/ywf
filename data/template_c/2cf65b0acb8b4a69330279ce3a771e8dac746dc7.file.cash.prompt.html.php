<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 11:39:11
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/cash.prompt.html" */ ?>
<?php /*%%SmartyHeaderCode:122638797357fc5edfb22fe8-85642769%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2cf65b0acb8b4a69330279ce3a771e8dac746dc7' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/cash.prompt.html',
      1 => 1475056731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '122638797357fc5edfb22fe8-85642769',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fc5edfb62f83_31099259',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fc5edfb62f83_31099259')) {function content_57fc5edfb62f83_31099259($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:664px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">提示</span>
        </div>
        <div class="two_tip">
            <div class="img_tip"></div>
            <p>提现成功!</p>
            <p>
                <a href="/cas/user/index">返回个人中心</a>
            </p>
        </div>
    </div>
</div>
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>