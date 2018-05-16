<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-18 19:52:33
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/order.index.html" */ ?>
<?php /*%%SmartyHeaderCode:199489009857f9fed2489943-76690228%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ff630d07b75ae4da9c99c584cfb339796920be10' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/order.index.html',
      1 => 1476791536,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '199489009857f9fed2489943-76690228',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f9fed24de9e6_66654350',
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f9fed24de9e6_66654350')) {function content_57f9fed24de9e6_66654350($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>

<!--回到顶部-->
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
/ywf/webjs/cas/order.index.js"></script>

<script>$("head>title").html("我的理财");</script>

<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height: 495px;">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">我的理财</span>
        </div>
        <div class="two_money">
            <div class="tab">
                <label>订单状态：</label>
                <button class="tab-btn" id="jixizhong">计息中</button>
                <button id="yiduifu" onclick="">已兑付</button>
                <button onclick="" id="yuyuezhong">预约中</button>
            </div>
        </div>
        <div class="three" id="three">

            <table class="" id="" style="white-space: nowrap;">
                <thead>
                    <tr>
                        <th>订单号</th>
                        <th>产品名称</th>
                        <th>状态</th>
                        <th>投资金额</th>
                        <th>年化益率</th>
                        <th>购买时间</th>
                        <th>到期时间</th>
                        <th>详情</th>
                        <!--<th>转让</th>
                        <th>操作</th>-->
                    </tr>
                </thead>

                <tbody id="jixi">

                </tbody>
            </table>
            
            <table class="" id="" style="white-space: nowrap;display:none" >
                <thead>
                    <tr>
                        <th>订单号</th>
                        <th>产品名称</th>
                        <th>状态</th>
                        <th>投资金额</th>
                        <th>年化益率</th>
                        <th>购买时间</th>
                        <th>到期时间</th>
                        <th>查看</th>
                    </tr>
                </thead>

                <tbody id="duifu">
                </tbody>
            </table>

            <table class="" id="" style="white-space: nowrap;display:none">
                <thead>
                    <tr>
                       <th>订单号</th>
                        <th>产品名称</th>
                        <th>状态</th>
                        <th>投资金额</th>
                        <th>年化益率</th>
                        <th>购买时间</th>
                        <th>到期时间</th>
                        <th>查看</th>
                    </tr>
                </thead>

                <tbody id="yuyue">

                </tbody>
            </table>
        </div>
    </div>

    <!--分页-->
        <div class="page_count"></div>
    <div id="paging" ></div>
    <div id="page"></div>

   
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