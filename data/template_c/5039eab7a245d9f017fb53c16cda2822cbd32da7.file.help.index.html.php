<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-26 19:02:23
         compiled from "/usr/local/httpd/htdocs/php/view/page/template/help.index.html" */ ?>
<?php /*%%SmartyHeaderCode:90247768757f8bfb8f15d51-59030507%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5039eab7a245d9f017fb53c16cda2822cbd32da7' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/page/template/help.index.html',
      1 => 1477479741,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '90247768757f8bfb8f15d51-59030507',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f8bfb9046730_16488243',
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    '_STATIC_CDN' => 0,
    'navigation' => 0,
    'v' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f8bfb9046730_16488243')) {function content_57f8bfb9046730_16488243($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go_top.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/alert.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/show_hidden.js"></script>

<!--分页插件-->
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination.css"/>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination-1.2.7.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/page/help.index.js"></script>

<script>$("head>title").html("帮助中心");</script>
<div class="container">
    <!--左边：内容-->
    <div class="container_left" id="helpcenter_left">
        <div class="top">
            <p>帮助中心</p>
            <!--<p>
             <i></i>
             <i></i>
             <i></i>
            </p>-->
        </div>
        <div style="width:140px;height:1px;background-color:#e8e8e8;margin:0 auto"></div>
        <div class="low">
            <ul>
                <?php if (!empty($_smarty_tpl->tpl_vars['navigation']->value)){?>
                <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['navigation']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                <li>
                    <span class="help" id="<?php echo $_smarty_tpl->tpl_vars['v']->value['category_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</span>
                </li>
                <?php } ?>
                <?php }?>
            </ul>
        </div>
    </div>
    
    <!--中间：内容-->
    <div class="container_content" >
        <!--我的账户-->
        <div class="top" style="min-height:530px">
            <div class="one">
                <div class="blue-line"></div>
                <span class="title" id="title"></span>
            </div>
            <div class="two_helpcenter">
                <ul id="list">
                    
                </ul>
            </div>

        </div>
        
        <div id="paging"></div>
    </div>
    
</div>

<!--回到顶部开始-->
<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_front']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>