<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-25 15:38:38
         compiled from "/usr/local/httpd/htdocs/php/view/page/template/media.index.html" */ ?>
<?php /*%%SmartyHeaderCode:72953809257f9ec05477411-14190735%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2a673cf457a058cfbc9fdc48492b39fccdb3cc31' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/page/template/media.index.html',
      1 => 1477381116,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '72953809257f9ec05477411-14190735',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f9ec054e8c57_30356926',
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    '_STATIC_CDN' => 0,
    'articles' => 0,
    'v' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f9ec054e8c57_30356926')) {function content_57f9ec054e8c57_30356926($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>


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
/ywf/webjs/page/media.index.js"></script>


<style type="text/css">
    .two_mtbd ul li{
       margin-left: 26px;	
       margin-right: 150px;
       border-bottom:1px dashed #ddd;
       line-height: 56px;
       overflow: hidden;    
    }
    .two_mtbd ul li .mtbd1{
       font-size:16px;
       color:#333;
       float:left;
    }
    .two_mtbd ul li .mtbd2{
      font-size:16px;
      color:#ddd;
      float: right;
    }
    li>span:hover{
        cursor: pointer;
    }
</style>


<script>$("head>title").html("媒体报道");</script>
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
        <div class="top" style="min-height:560px">
            <div class="one">
                <div class="blue-line"></div>
                <h2 style="font-size:20px;line-height: 50px;padding-left: 25px;" id="title">媒体报道</h2>
            </div>
           
            <div class="two_mtbd" style="min-height:480px" id="body">
                <ul style="min-height:480px" id="list">
                    <?php if (!empty($_smarty_tpl->tpl_vars['articles']->value)){?>
                    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['articles']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                    <li>
                        <span class="mtbd1"><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</span>
                        <span class="mtbd2"><?php echo mb_substr($_smarty_tpl->tpl_vars['v']->value['mtime'],0,10);?>
</span>
                    </li>
                    <?php } ?>
                    <?php }?>
                </ul>
            </div>
        </div>

        <div class="page_count"></div>
        <div id="paging"></div>
    </div>
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