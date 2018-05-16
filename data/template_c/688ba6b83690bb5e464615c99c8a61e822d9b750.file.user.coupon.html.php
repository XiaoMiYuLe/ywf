<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-09 17:22:30
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/user.coupon.html" */ ?>
<?php /*%%SmartyHeaderCode:129659815357fa0c5613eb92-87819131%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '688ba6b83690bb5e464615c99c8a61e822d9b750' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/user.coupon.html',
      1 => 1475056733,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '129659815357fa0c5613eb92-87819131',
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
  'unifunc' => 'content_57fa0c56195986_55216330',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fa0c56195986_55216330')) {function content_57fa0c56195986_55216330($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


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
/ywf/webjs/cas/user.coupon.js"></script>

<script>$("head>title").html("优惠券");</script>

<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:622px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">优惠券</span>
        </div>
        <div class="two_money">
            <div class="tab">
                <label>状态：</label>
                <button class="tab-btn" id="weishiyong1">未使用</button>
                <button id="yishiyong1">已使用</button>
                <button id="yiguoqi1">已过期</button>
            </div>
        </div>
        <div class="three" id="three">
            <table class="yhq" id=""  style="white-space: nowrap;" >
                <thead>
                    <tr>
                        <th>分类</th>
                        <th>金额</th>
                        <th>开始时间</th>
                        <th>截止时间</th>
                        <th>使用条件</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody id="weishiyong">
                    
                </tbody>
            </table>
            
            <table class="yhq" id=""  style="white-space: nowrap;display:none" >
                <thead>
                    <tr>
                        <th>分类</th>
                        <th>金额</th>
                        <th>开始时间</th>
                        <th>截止时间</th>
                        <th>使用条件</th>
                    </tr>
                </thead>
                <tbody id="yishiyong">
                    
                </tbody>

            </table>
            
            <table class="yhq" id=""  style="white-space: nowrap;display:none" >
                <thead>
                    <tr>
                        <th>分类</th>
                        <th>金额</th>
                        <th>开始时间</th>
                        <th>截止时间</th>
                        <th>使用条件</th>
                    </tr>
                </thead>
                <tbody id="yiguoqi">

                </tbody>
            </table>
        </div>
    </div>
    
    <div class="page_count"></div>
    <div id="paging"></div>
    <div id="page"></div>
    <div id="page1"></div>
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