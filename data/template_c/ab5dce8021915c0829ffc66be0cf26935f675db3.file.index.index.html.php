<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-25 15:37:39
         compiled from "/usr/local/httpd/htdocs/php/view/page/template/index.index.html" */ ?>
<?php /*%%SmartyHeaderCode:83111096157f89d67cbe309-50505886%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ab5dce8021915c0829ffc66be0cf26935f675db3' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/page/template/index.index.html',
      1 => 1477380618,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '83111096157f89d67cbe309-50505886',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f89d67d138c6_80523980',
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    '_STATIC_CDN' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f89d67d138c6_80523980')) {function content_57f89d67d138c6_80523980($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>


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
/ywf/webjs/page/index.index.js"></script>


<style type="text/css">
    .two_mtbd p{
        padding-left:34px;	
        padding-right:34px;
        padding-top:28px;
        font-size:15px;
        line-height: 26px;
        color:#666;
    }
</style>


<script>$("head>title").html("关于我们");</script>
<div class="container">
    <!--左边：内容-->
    <div class="container_left" id="security_left">
        <div class="top" id="security_top">
            <p>关于我们</p>
            <!--<p>
             <i></i>
             <i></i>
             <i></i>
            </p>-->
        </div>
        <div style="width:140px;height:1px;background-color:#e8e8e8;margin:0 auto"></div>
        <div class="low">
            <ul>
                <li id="1" class="about">
                    <span style="padding-left:14px">亿万福介绍</span>
                </li>
                <li id="2" class="about">
                    <span class="">公司介绍</span>
                </li>
                <li id="3" class="about">
                    <span>安全保障</span>
                </li>
                
                <li class="media" onclick="location.href='/page/media/index'">
                    <span>媒体报道</span>
                </li>
                <li class="media" onclick="location.href='/page/wealth/index'">
                    <span>财富动态</span>
                </li>
                <li class="platform" onclick="location.href='/page/platform/index'">
                    <span>平台公告</span>
                </li>
                <li class="help" onclick="location.href='/page/help/index'">
                    <span>帮助中心</span>
                </li>
                
                <li id="9" class="about">
                    <span>联系我们</span>
                </li>
            </ul>
        </div>
    </div>
    <!--中间：内容-->
    <div class="container_content" >
        <!--我的账户-->
        <div class="top" style="min-height:600px">
            <div class="one">
                <div class="blue-line"></div>
                <span class="title" id="title"></span>
            </div>
            <div class="two_mtbd" style="min-height:560px" id="body">
                
            </div>
        </div>
    </div>
    
    <div class="page_count"></div>
    <div id="paging"></div>
</div>
<!-- 尾部结构结束  -->

<!--回到顶部开始-->
<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_front']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>