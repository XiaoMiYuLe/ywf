<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 11:32:24
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/cash.index.html" */ ?>
<?php /*%%SmartyHeaderCode:24544662157fc5d481edb94-00742328%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '58d56beb8cad0c179a9a73b7308aa36ce88e6d69' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/cash.index.html',
      1 => 1475056731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '24544662157fc5d481edb94-00742328',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'balance' => 0,
    'bank' => 0,
    'number' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fc5d4826f3a8_84383934',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fc5d4826f3a8_84383934')) {function content_57fc5d4826f3a8_84383934($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>



<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go_top.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/alert.js"></script>
<!-- 弹框 js-->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/myAlert.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/cash.index.js"></script>

<script>$("head>title").html("提现");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:665px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">提现</span>
        </div>
        <div class="two_recharge" >
            <p class="ye2">账户余额<span><?php echo $_smarty_tpl->tpl_vars['balance']->value['asset'];?>
元</span></p> 
            <div class="money_input">
                <label>提现至银行卡：</label>
                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['bank']->value['bank_name'];?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1){?>
                <span><?php echo $_smarty_tpl->tpl_vars['bank']->value['bank_name'];?>
&nbsp;&nbsp;&nbsp;&nbsp;尾号 <?php echo $_smarty_tpl->tpl_vars['bank']->value['bank_no'];?>
 </span>
                <?php }else{ ?>
                <span>未绑卡 </span>
                <?php }?>
                <i class="zs_icon"></i>
            </div>
            <div class="money_input" id="tx_money">
                <label>提现金额：</label>
                <input type="text" vlaue="" id="tixian" placeholder="请输入提现金额"/>
            </div>
            <div class="money_input" id="sxf">
                <label>手续费：</label>
                <span><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['number']->value['number'];?>
<?php $_tmp2=ob_get_clean();?><?php if ($_tmp2==0){?><?php echo 2;?>
<?php }else{ ?><?php echo 0;?>
<?php }?>.00元</span>
            </div>
            <div class="money_input" id="pay_password">
                <label>交易密码：</label>
                <input type="password" maxlength="6" minlength="6" id="password" vlaue="" placeholder="请输入交易密码"/>
                <a href="/cas/resetpassword/forgotPaypwd">忘记密码？</a>
            </div>
            <button class="cz_btn form-submit" id="tz_btn">提现</button>
            <div class="text" id="tx_text">
                <p>温馨提示</p>
                <p>1、每月免费提现2次，超过2次每笔收2元手续费；</p>
                <p>2、提现单笔2万（含）以下预计3小时内到账；</p>
                <p>3、提现单笔2万以上，下一工作日16：00前到账；</p>
            </div>
            <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['balance']->value['asset'];?>
" id="asset"/>
            <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['balance']->value['bank_status'];?>
" id="bank_status"/>
            <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['balance']->value['status'];?>
" id="status"/>
        </div>
    </div>
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