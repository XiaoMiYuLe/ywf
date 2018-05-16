<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-25 18:07:39
         compiled from "/usr/local/httpd/htdocs/php/view/page/template/advert.index.html" */ ?>
<?php /*%%SmartyHeaderCode:1077443947580f2679a1aaa3-85212083%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5909d2e0cf7bd84499134c404052e26d23d752d3' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/page/template/advert.index.html',
      1 => 1477390058,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1077443947580f2679a1aaa3-85212083',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_580f2679a8a6c7_26272711',
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    'id' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_580f2679a8a6c7_26272711')) {function content_580f2679a8a6c7_26272711($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>

<script>$("head>title").html("亿万福");</script>
<div style="text-align:center;">
<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['id']->value;?>
<?php $_tmp1=ob_get_clean();?><?php if (($_tmp1==4)){?>
<img width="100%" src="/static/ywf/img/ad/jjr.png">
<?php }else{?><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['id']->value;?>
<?php $_tmp2=ob_get_clean();?><?php if (($_tmp2==3)){?>
<img width="100%" src="/static/ywf/img/ad/chanpin.png">
 <?php }}?>
</div>
    <!-- 尾部结构结束  -->

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_front']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>