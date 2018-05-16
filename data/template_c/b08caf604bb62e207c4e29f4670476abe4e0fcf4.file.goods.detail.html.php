<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-15 00:11:26
         compiled from "/usr/local/httpd/htdocs/php/view/goods/template/goods.detail.html" */ ?>
<?php /*%%SmartyHeaderCode:44554768457f9ea6ed53460-44218647%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b08caf604bb62e207c4e29f4670476abe4e0fcf4' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/goods/template/goods.detail.html',
      1 => 1476461484,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '44554768457f9ea6ed53460-44218647',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f9ea6ee81e15_07233407',
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    '_STATIC_CDN' => 0,
    'goods' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f9ea6ee81e15_07233407')) {function content_57f9ea6ee81e15_07233407($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/gotop.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/product_detail.js"></script>
<!--分页插件-->
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination.css"/>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination-1.2.7.min.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination.css"/>
<!-- 弹框 js-->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/myAlert.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/goods/goods.detail.js"></script>

<script>
    $(document).ready(function () {
        var goods_name = $('.goods_name').attr('goods_name');
        $("head>title").html(goods_name);

    });
</script>


<div class="product_detail_content">
    <div class="product_first">
        <div class="product_first_l fl">
            <h2 class="goods_name" goods_name="<?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_name'];?>
">
                <?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_name'];?>

                <span><?php echo $_smarty_tpl->tpl_vars['goods']->value['comment'];?>
</span>
            </h2>
            <ul>
                <li>
                    <div class="title title1">
                        <span  class="shuzi"><?php echo $_smarty_tpl->tpl_vars['goods']->value['yield'];?>
</span>
                        <span>%</span>
                    </div>
                    <p>预期年化收益</p>
                </li>
                <li class="sep">
                    <div class="title title2">
                        <span class="shuzi"><?php if ($_smarty_tpl->tpl_vars['goods']->value['order_id']){?><?php echo $_smarty_tpl->tpl_vars['goods']->value['distance_days'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['goods']->value['financial_period'];?>
<?php }?></span>
                        <span>天</span>
                    </div>
                    <p>期限</p>
                </li>
                <li>
                    <div class="title title3">
                        <span class="shuzi"><?php if ($_smarty_tpl->tpl_vars['goods']->value['order_id']){?><?php echo $_smarty_tpl->tpl_vars['goods']->value['buy_money'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['goods']->value['low_pay'];?>
<?php }?></span><span>元</span>
                    </div>
                    <p><?php if ($_smarty_tpl->tpl_vars['goods']->value['order_id']){?>订单金额<?php }else{ ?>起投金额<?php }?></p>
                </li>
            </ul>
            <div class="product_text1">
                <div class="product_tex">
                    <span>起息日期：</span>
                    <p><?php echo $_smarty_tpl->tpl_vars['goods']->value['start_time'];?>
</p>
                </div>
                <div class="product_tex">
                    <span>结息日期：</span>
                    <p><?php echo $_smarty_tpl->tpl_vars['goods']->value['end_time'];?>
</p>
                </div>
                <div class="product_tex">
                    <span>兑付日期：</span>
                    <p><?php echo $_smarty_tpl->tpl_vars['goods']->value['deal_date'];?>
</p>
                </div>
                <div class="product_tex">
                    <span>定价转让：</span>
                    <p><?php if ($_smarty_tpl->tpl_vars['goods']->value['order_id']){?><?php echo $_smarty_tpl->tpl_vars['goods']->value['distance_order_show'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['goods']->value['transfer_show'];?>
<?php }?></p>

                </div>
                <div class="product_tex">
                    <span>兑付方式：</span>
                    <p><?php echo $_smarty_tpl->tpl_vars['goods']->value['deal_way'];?>
</p>
                </div>
            </div>
        </div>
        <div class="product_first_r fr">
            <?php if ($_smarty_tpl->tpl_vars['goods']->value['order_id']){?>
            <h2>
                转让产品
                <span></strong></span>
            </h2>
            <?php }else{ ?>
            <h2>
                投资金额
                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_pattern'];?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_pattern'];?>
<?php $_tmp2=ob_get_clean();?><?php if (($_tmp1!=4&&$_tmp2!=1)){?>
                <span>剩余可投：<strong><?php echo $_smarty_tpl->tpl_vars['goods']->value['spare_fee'];?>
元</strong></span>
                <?php }?>
            </h2>
            <?php }?>

            <div class="product_first_down">
                <?php if ($_smarty_tpl->tpl_vars['goods']->value['order_id']){?>
                <?php }else{ ?>
                 <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_pattern'];?>
<?php $_tmp3=ob_get_clean();?><?php if (($_tmp3!=4)){?>
                <div class="pro_bar">
                    <span class="progress"></span>
                    <em class="progress_num"><?php echo $_smarty_tpl->tpl_vars['goods']->value['schedule'];?>
%</em>
                </div>
                 <?php }?>
                <?php }?>
                <div class="product_inputs">
                    <a href="javascript:;" class="reduce_btn"></a>
                    <input type="text" class="text_box" id="buy_money" value="<?php if ($_smarty_tpl->tpl_vars['goods']->value['order_id']){?><?php echo $_smarty_tpl->tpl_vars['goods']->value['buy_money'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['goods']->value['low_pay'];?>
<?php }?>">
                    <span>元</span>
                    <a href="javascript:;" class="plus_btn"></a>	  	  
                </div> 
                <p>预期到期收益：<strong id="yq"></strong></p>
                <div class="txt">
                    <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_pattern'];?>
<?php $_tmp4=ob_get_clean();?><?php if (($_tmp4!=4)){?>
                    <span class="fl">起投金额：<em><?php echo $_smarty_tpl->tpl_vars['goods']->value['low_pay'];?>
元</em></span>
                    <span class="fr">递增金额：<em><?php echo $_smarty_tpl->tpl_vars['goods']->value['increasing_pay'];?>
元</em></span>
                    <?php }else{ ?>
                    <span class="fl">您有体验金：<strong><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['goods']->value['voucher_money'];?>
<?php $_tmp5=ob_get_clean();?><?php if ($_tmp5==-1){?>未登录<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['goods']->value['voucher_money'];?>
元<?php }?></strong></span>
                    <?php }?>
                </div> 
                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_status'];?>
<?php $_tmp6=ob_get_clean();?><?php if (($_tmp6==1)){?>
                <a href="javascript:;" class="product_btn"><?php if ($_smarty_tpl->tpl_vars['goods']->value['order_id']){?>立即投资( 转让价<?php echo $_smarty_tpl->tpl_vars['goods']->value['transfer_price_show'];?>
元 )<?php }else{ ?>立即投资<?php }?></a>
                <?php }else{?><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_status'];?>
<?php $_tmp7=ob_get_clean();?><?php if (($_tmp7==2)){?>
                <a href="javascript:;" class="product_btn" style="background:#cccccc">已售罄</a>
                <?php }else{?><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_status'];?>
<?php $_tmp8=ob_get_clean();?><?php if (($_tmp8==3)){?>
                <a href="javascript:;" class="product_btn" style="background:#cccccc">已下架</a>
                <?php }}}?>

            </div>
        </div>
    </div>
    <ul class="product_second">
        <li id="detail" class="current">
            产品详情
            <span></span>
        </li>
        <li id="safe">
            安全保障
            <span></span>
        </li>
        <li id="record">
            投资记录
            <span></span>
            <i style="font-size: 14px;">(<em style="color: #ff7003;"><?php echo $_smarty_tpl->tpl_vars['goods']->value['buy_num'];?>
</em>)</i>
        </li>
    </ul>
    <div class="product_third " id="product_third1">
        <?php if ($_smarty_tpl->tpl_vars['goods']->value['goods_detail']){?>
        <?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_detail'];?>

        <?php }else{ ?>
        <img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;">
        <?php }?>
    </div>
    <div class="product_third " id="product_third2" style="display:none;">
        <?php if ($_smarty_tpl->tpl_vars['goods']->value['safety']){?>
        <?php echo $_smarty_tpl->tpl_vars['goods']->value['safety'];?>

        <?php }else{ ?>
        <img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;">
        <?php }?>
    </div>
    <div class="product_third product_third3 " id="product_third3" style="display:none;">
        <div class="low"> 
            <div class="three three_ytab" id="three">
                <table class="" id=""  style="white-space: nowrap;" >
                    <thead>
                        <tr>
                            <th>会员</th>
                            <th>投资金额</th>
                            <th>投资日期</th>
                            <th>投资状态</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
            </div>

        </div> 
    </div>

    <div class="page_count"></div>
    <div id="paging" class="m-pagination"></div>
</div>	
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['financial_period'];?>
" id="qx"/>
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['yield'];?>
" id="yield"/>
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_id'];?>
" id="g" />
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_pattern'];?>
" id="goods_pattern" />
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['increasing_pay'];?>
" id="increasing_pay" />  
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['low_pay'];?>
" id="low_pay"/>
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['high_pay'];?>
" id="high_pay"/>
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['order_id'];?>
" id="order_id"/>
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['buy_money'];?>
" id="buy_money_order"/>
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['distance_days'];?>
" id="qx_order"/> 
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['voucher_money'];?>
" id="voucher_money"/>                 
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_status'];?>
" id="goods_status" />

<!--侧边导航-->
<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_front']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>