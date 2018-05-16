<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-20 16:00:25
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/zhaohang.index.html" */ ?>
<?php /*%%SmartyHeaderCode:14389214875808799972a4e9-52531378%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd7729de3aa2275928293e92cdbb2f2a1a13290a3' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/zhaohang.index.html',
      1 => 1476950028,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14389214875808799972a4e9-52531378',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    'data' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_58087999783996_15679217',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58087999783996_15679217')) {function content_58087999783996_15679217($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


<script src="/static/ywf/webjs/yhbk/pay.js" type="text/javascript"></script>
<script>$("head>title").html("签约成功")</script>

<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:664px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">提示</span>
        </div>
        <div class="two_tip">
            <div class="img_tip"></div>
            <?php if ($_smarty_tpl->tpl_vars['data']->value['status']==0){?>
            <p>签约成功!</p>
            <?php }else{ ?>
            	<p><?php echo $_smarty_tpl->tpl_vars['data']->value['error'];?>
</p>
            <?php }?>
            <p>
                <a href="/cas/user/index">返回个人中心</a>
            </p>
        </div>
    </div>
</div>
</div>
<input name="phone" type="text" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['phone'];?>
">
<input name="order_no" type="text" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_no'];?>
">
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>