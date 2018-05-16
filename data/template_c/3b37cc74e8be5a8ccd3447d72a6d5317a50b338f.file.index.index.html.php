<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-10 11:24:11
         compiled from "/usr/local/httpd/htdocs/php/view/admin/template/index.index.html" */ ?>
<?php /*%%SmartyHeaderCode:49012033957fb09dbe93f46-50714921%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3b37cc74e8be5a8ccd3447d72a6d5317a50b338f' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/admin/template/index.index.html',
      1 => 1475056723,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '49012033957fb09dbe93f46-50714921',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix' => 0,
    'loggedInUser' => 0,
    'wrapper_suffix' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fb09dbed9776_28869538',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fb09dbed9776_28869538')) {function content_57fb09dbed9776_28869538($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix']->value)===null||$tmp==='' ? '' : $tmp);?>


<section class="vbox">          
    <header class="header bg-white b-b clearfix">
        <p class="h4">Welcome, <?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['username'];?>
</p>
    </header>
    
    <section class="scrollable wrapper w-f">
    
    </section>
</section>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>