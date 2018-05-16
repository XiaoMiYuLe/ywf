<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-14 22:11:05
         compiled from "/usr/local/httpd/htdocs/php/view/bts/template/order.prompt.html" */ ?>
<?php /*%%SmartyHeaderCode:13187640125800e7799243d8-91833031%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2871fd890270b9245d60eb9332afc8592e2204c9' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/bts/template/order.prompt.html',
      1 => 1475056728,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13187640125800e7799243d8-91833031',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    '_STATIC_CDN' => 0,
    'order_no' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5800e779973da3_78477835',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5800e779973da3_78477835')) {function content_5800e779973da3_78477835($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/gotop.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/rightnow_buy.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/tableft.js"></script>
<script>$("head>title").html("购买成功");</script>
<div class="login_content reminder buy_sucess_content">
    <div class="register">
        <div class="reg_right reg_right11">
            <div class="sucess">
                <h2></h2>
                <span style="text-align:center;height:40px;line-height:40px;">购买成功</span> 
                <p>订单号：<em><?php echo $_smarty_tpl->tpl_vars['order_no']->value;?>
</em></p>
                <p class="p2">您已购买成功，<strong id="timer"></strong> 秒后会跳转到个人中心。</p>
                <div class="btn">
                    <a href="/cas/user/index" class="jump">立即跳转</a>
                    <a href="/goods/goods/index" class="gobuy">继续购买 >
                    </a>
                </div>
            </div>	         	  
        </div>

    </div>   	
</div>
<script>
    $(document).ready(function () {
        var nums = 10;
        var numnow;
        $("#timer").html(10);
        var ss = setInterval(function () {
            nums--;
            numnow = nums;
            $("#timer").html(numnow);
            if (nums == 0) {
                clearInterval(ss);
                window.location.href='/cas/user/index';
            }
        }, 1000)
    });
</script>
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_front']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>