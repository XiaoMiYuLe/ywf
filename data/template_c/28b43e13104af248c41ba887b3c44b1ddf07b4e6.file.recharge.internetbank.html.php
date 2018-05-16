<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-24 10:43:53
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/recharge.internetbank.html" */ ?>
<?php /*%%SmartyHeaderCode:198400969657fa05d1e3dbc1-40492201%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '28b43e13104af248c41ba887b3c44b1ddf07b4e6' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/recharge.internetbank.html',
      1 => 1477277028,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '198400969657fa05d1e3dbc1-40492201',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fa05d1e8d396_81274986',
  'variables' => 
  array (
    'keywords' => 0,
    'description' => 0,
    'page_title' => 0,
    'feed_url' => 0,
    '_STATIC_CDN' => 0,
    'asset' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fa05d1e8d396_81274986')) {function content_57fa05d1e8d396_81274986($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="Keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
        <meta name="Description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
        <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
        <title>&#12288;</title>
        <link rel="alternate" type="application/rss+xml" title="RSS|<?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['feed_url']->value;?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/css/reset.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/css/style.css">
        <script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery/1.11.0/jquery.min.js"></script>
        <script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/md5/md5.min.js"></script>
        <!--<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/front/js/common.js" type="text/javascript"></script>-->
        <!--  
        <script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/front/js/transport.js" type="text/javascript"></script>
        <script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/front/js/utils.js" type="text/javascript"></script> -->
        <script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-form/jquery.form.js" type="text/javascript"></script>
        <!-- 弹框 js-->
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/myAlert.js"></script>

        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/recharge/wy_recharge.js"></script>
<script type="text/javascript">document.title = '充值';</script>
    </head>
    <body>
    <?php echo $_smarty_tpl->getSubTemplate ("index/template/inc/center_top.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:906px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">充值</span>
        </div>
        <div class="two_recharge">
            <p class="ye">账户余额<span><?php echo $_smarty_tpl->tpl_vars['asset']->value;?>
元</span></p> 
            <div class="pay">
                <label>充值方式：</label>
                <span>
                    <img src="/static/ywf/img/pay2.png" alt="" onclick="location = '/cas/recharge/index'"/>
                </span>
                <span class="cur">
                    <img src="/static/ywf/img/pay1.png" alt="" />
                    <i></i>
                </span>
            </div>
            <div class="bank">
                <label>选择银行：</label>
                <div class="img_bank">
                    <span id="0801020000">
                        <img src="/static/ywf/img/bank/gs.png">
                    </span>
                    <span id="0801050000">
                        <img src="/static/ywf/img/bank/js.png">
                    </span>
                    <!--<span id="0803010000">
                        <img src="/static/ywf/img/bank/BOCM.png">
                    </span>-->
                    <span id="0801040000">
                        <img src="/static/ywf/img/bank/zg.png">
                    </span>
                    <span id="0803030000">
                        <img src="/static/ywf/img/bank/gd.png">
                    </span>
                    <span id="0803090000">
                        <img src="/static/ywf/img/bank/CIB.png">
                    </span>
                    <span id="0803050000">
                        <img src="/static/ywf/img/bank/ms.png">
                    </span>
                    <span id="0803060000">
                        <img src="/static/ywf/img/bank/gf.png">
                    </span>
                    <span id="0803100000">
                        <img src="/static/ywf/img/bank/pf.png">
                    </span>
                    <span id="0803020000">
                        <img src="/static/ywf/img/bank/CITIC.png">
                    </span>
                    <span id="0801000000">
                        <img src="/static/ywf/img/bank/yz.png">
                    </span>
                    <span id="0803080000">
                        <img src="/static/ywf/img/bank/CMB.png">
                    </span>
                    <span id="0803040000">
                        <img src="/static/ywf/img/bank/HXB.png">
                    </span>
                    <span id="0803060000">
                        <img src="/static/ywf/img/bank/gf.png">
                    </span>
                    <span id="0804105840">
                        <img src="/static/ywf/img/bank/pa.png">
                    </span>
                    <span id="0801030000">
                        <img src="/static/ywf/img/bank/ny.png">
                    </span>
                     <span id="0804031000">
                        <img src="/static/ywf/img/bank/bj.png">
                    </span>
                     <span id="0803160000">
                        <img src="/static/ywf/img/bank/zs.png">
                    </span>
                     <span id="0803202220">
                        <img src="/static/ywf/img/bank/xhc.png">
                    </span>
                </div>
            </div>
            <div class="money">
                <label>充值金额：</label>
                <input type="text" vlaue="" id="money" placeholder="100元起"/>
            </div>
            <button class="cz_btn"  id="button2">充值</button>
            <div class="text">
                <p>温馨提示</p>
                <p>1、单笔最小充值金额为100元，最大充值金额为100万。</p>
                <p>2、您需开通网上银行才能进行在线充值，如果您还没有开通网上银行，请致电所属银行客服电话询问开通流程。</p>
                <p>3、不同银行对线上支付均有不同的限额规定，如支付超过限额，请致电银行客服申请提高额度再来充值。</p>
                <p>各大银行的限额表您可查看： 《银行线上支付限额表》</p>
                <p>4、根据国家法规的规定，不受理各类信用卡的充值申请。</p>
                <p>5、充值中如果遇到任何问题，您也可点击右侧的在线客服寻求帮助。</p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var iHtml = "<i></i>";
        $('.bank').delegate('span', 'click', function (event) {
            $(this).append(iHtml).siblings('span').children('i').remove();
            $(this).addClass('checked').siblings('span').removeClass('checked'); 
            $(this).css({
                border: '1px solid #2197d7',
                height: 44,
                width: 148
            }).siblings('span').css({
                border: 0,
                height: 46,
                width: 150
            });
            ;
        });
    });
</script>

</div>
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>