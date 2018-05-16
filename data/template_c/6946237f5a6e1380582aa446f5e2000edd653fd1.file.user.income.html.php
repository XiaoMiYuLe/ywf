<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 16:30:46
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/user.income.html" */ ?>
<?php /*%%SmartyHeaderCode:174863067257fca3365a2858-69950502%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6946237f5a6e1380582aa446f5e2000edd653fd1' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/user.income.html',
      1 => 1475056733,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '174863067257fca3365a2858-69950502',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fca3365f5181_79288731',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fca3365f5181_79288731')) {function content_57fca3365f5181_79288731($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go_top.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/alert.js"></script>
<!--分页插件-->
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination.css"/>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination-1.2.7.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/user.income.js"></script>

<script>$("head>title").html("收益记录");</script>

<!--中间：内容-->
<div class="container_content">
    <!--资金流水-->
    <div class="top">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">收益记录</span>
        </div>
        <div class="three" id="three">
            <table class="" id="table1"  style="white-space: nowrap;" >
                <thead>
                    <tr>
                        <th>产品名称</th>
                        <th>购买时间</th>
                        <th>收益金额</th>
                        <th>收益方式</th>
                        <th>账户余额</th>
                    </tr>
                </thead>

                <tbody id="tbody">

                </tbody>
            </table>
        </div>
    </div>

    <div class="page_count"></div>
    <div id="paging" class="m-pagination"></div>

</div>
</div>

<!--回到顶部开始-->
<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>
<!--回到顶部结束-->
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>