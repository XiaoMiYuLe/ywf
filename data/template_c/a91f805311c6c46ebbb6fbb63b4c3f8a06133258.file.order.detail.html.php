<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 15:47:53
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/order.detail.html" */ ?>
<?php /*%%SmartyHeaderCode:166347409657fb76c2ed6a81-63158629%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a91f805311c6c46ebbb6fbb63b4c3f8a06133258' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/order.detail.html',
      1 => 1476170870,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '166347409657fb76c2ed6a81-63158629',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fb76c301c4b0_02799201',
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'show' => 0,
    'orderInfo' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fb76c301c4b0_02799201')) {function content_57fb76c301c4b0_02799201($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>

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
/ywf/webjs/cas/order.detail.js"></script>

<script>$("head>title").html("订单详情");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:658px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">订单详情</span>
        </div>
        <div class="two_orderdatelist" >
            <p>订单状态：<span><?php echo $_smarty_tpl->tpl_vars['show']->value;?>
</span></p> 
            <div class="item" id="item_one">
                <label>订单号：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['order_no'];?>
</span>
            </div>
            <div class="item">
                <label>产品名称：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['goods_name'];?>
</span>
            </div>
            <div class="item">
                <label>预期年化收益：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['yield'];?>
%</span>
            </div>
            <div class="item">
                <label>投资金额：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['buy_money'];?>
元</span>
            </div>
            <div class="item">
                <label>实付金额：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['real_money'];?>
元</span>
            </div>
            <div class="item">
                <label>预计到期收益：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['bts_yield'];?>
元</span>
            </div>
            <div class="item">
                <label>下单时间：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['ctime'];?>
</span>
            </div>
            <div class="item">
                <label>起息日期：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['start_time'];?>
</span>
            </div>
            <div class="item">
                <label>结息日期：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['end_time'];?>
</span>
            </div>
            <div class="item">
                <label>兑付日期：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['cash_time'];?>
</span>
            </div>
            <div class="item">
                <a id="word" href="javascript:;">电子合同</a>
                <a onclick="location.href = '/goods/goods/detail?goods_id=' + <?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['goods_id'];?>
" href="javascript:;">产品详情</a>
            </div>
        </div>
    </div>
</div>
</div>

<input id="order_no" value="<?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['order_no'];?>
" type="hidden" />
<!--回到顶部开始-->
<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>

<!--回到顶部结束-->
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>