<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-09 17:57:03
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/bank.index.html" */ ?>
<?php /*%%SmartyHeaderCode:102442963757fa146f9dd295-87107716%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed0619af62b2853af1cc8c0bbd423d94fac6b1ff' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/bank.index.html',
      1 => 1475056731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '102442963757fa146f9dd295-87107716',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'card' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fa146fa37ba1_59753373',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fa146fa37ba1_59753373')) {function content_57fa146fa37ba1_59753373($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go_top.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/alert.js"></script>

<script>$("head>title").html("我的银行卡");</script>

<!--中间：内容-->
<div class="container_content">
    <!--我的账户-->
    <div class="top" style="min-height:630px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">银行卡</span>
        </div>
        <div class="bank_cardover">
            <div>
                <img alt="" src="<?php echo $_smarty_tpl->tpl_vars['card']->value['link'];?>
">
                <p>
                    <a href=""><?php echo $_smarty_tpl->tpl_vars['card']->value['bank_name'];?>
</a>
                    <span>储蓄卡</span>
                </p>
                <p>
                    <?php echo $_smarty_tpl->tpl_vars['card']->value['bank_no'];?>

                </p>
                <p><?php echo $_smarty_tpl->tpl_vars['card']->value['quota'];?>
</p>
            </div>
            <p style="width:600px">为了您的账户安全，我们只允许绑定一张银行卡。所以在本息未归还之前，请不要注销相关卡片。
                如有紧急需要，请致电客服：400-1369-998</p>
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