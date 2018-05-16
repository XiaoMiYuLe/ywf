<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-09 16:55:01
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/recharge.prompt.html" */ ?>
<?php /*%%SmartyHeaderCode:23939833257fa05e521a6d6-17080005%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '645193c2a9e346d0bb83c780d2894b7e46c99a1c' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/recharge.prompt.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23939833257fa05e521a6d6-17080005',
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
  'unifunc' => 'content_57fa05e525ed49_51504935',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fa05e525ed49_51504935')) {function content_57fa05e525ed49_51504935($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>

<script>$("head>title").html("充值成功")</script>

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
            <p>充值成功!</p>
            <p>
                <a href="/cas/user/index">返回个人中心</a>
            </p>
        </div>
    </div>
</div>
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>