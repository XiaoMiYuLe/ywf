<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-13 18:25:20
         compiled from "/usr/local/httpd/htdocs/php/view/goods/template/goods.index.html" */ ?>
<?php /*%%SmartyHeaderCode:147703963057f89d5464d375-22482480%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd9ba8050e2cf0a56e77300f717e9b4d17943e948' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/goods/template/goods.index.html',
      1 => 1476354317,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '147703963057f89d5464d375-22482480',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f89d546ca5d5_41386178',
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    '_STATIC_CDN' => 0,
    'time' => 0,
    'count' => 0,
    'buy_money' => 0,
    'order_new' => 0,
    'v' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f89d546ca5d5_41386178')) {function content_57f89d546ca5d5_41386178($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/gotop.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/goods.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tableft.js"></script>
<!-- 分页插件 -->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/goods/goods.index.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination.css"/>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination-1.2.7.min.js"></script>

<script>$("head>title").html("理财产品");</script>
<!-- 商品列表模块开始 -->
<div class="goods_content">
    <ul class="product_second goods_product_second">
        <li class="current" id="licai">
            在线理财
            <span></span>
        </li>
        <li id="zhuanrang">
            转让专区
            <span></span>
        </li>
        <li id="yuyue">
            产品预约
            <span></span>
        </li>
    </ul>
    <div class="goods_box">
        <div class="goods_box_l" >
            <div id="licai_content"></div>
            <div id="page" class="m-pagination"></div>
        </div>
        <div class="goods_box_l" style="display: none;">
            <div id="zhuanrang_content"></div>
            <div id="page1" class="m-pagination"></div>
        </div>
        <div class="goods_box_l" style="display: none">
            <div id="yuyue_content"></div> 
            <div id="page2" class="m-pagination"></div>
        </div>
        
        <div class="goods_side">
            <div class="side_img">
                <img src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/img/pic/pic_4.png" alt="" >
            </div>
            <div class="side_mode">
                <div class="side_text">
                    <p>7天平台数据</p>
                    <span>更新至<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
</span>
                </div>
                <ul class="add_text">
                    <li>
                        <p><?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</p>
                        <span>累计投资笔数 (笔)</span>
                    </li>
                    <li>
                        <p><?php echo $_smarty_tpl->tpl_vars['buy_money']->value;?>
</p>
                        <span>累计投资总额 (元)</span>
                    </li>
                </ul>
            </div>
            <div class="side_mode">
                <div class="side_text">
                    <p>投资动态</p>
                    <span>更新至<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
</span>
                </div>
                <div  class="add_text add_textdown z_scroll">
                    <ul>
                        <?php if ($_smarty_tpl->tpl_vars['order_new']->value){?>
                        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['order_new']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
                        <li>	
                            <p>投资: <strong><?php echo $_smarty_tpl->tpl_vars['v']->value['buy_money'];?>
</strong> 元</p>
                            <span>用户:<?php echo $_smarty_tpl->tpl_vars['v']->value['phone'];?>
 <?php echo $_smarty_tpl->tpl_vars['v']->value['goods_name'];?>
</span>
                        </li> 
                        <?php } ?> 
                        <?php }?>
                    </ul>
                </div>
                <div class="add_white"></div>
            </div>
        </div>

    </div>


</div>
<!-- 商品列表模块结束 -->

<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_front']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>