<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-17 12:09:26
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/bank.addbank.html" */ ?>
<?php /*%%SmartyHeaderCode:1744393784580399a9862b27-80781961%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '00d960682a50bdccb6376ded90b6902fce6226d0' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/bank.addbank.html',
      1 => 1476677363,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1744393784580399a9862b27-80781961',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_580399a98ce286_59194467',
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'bank' => 0,
    'k' => 0,
    'v' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_580399a98ce286_59194467')) {function content_580399a98ce286_59194467($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go_top.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/myAlert.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/yhbk/bk.js"></script>

<script>$("head>title").html("绑定银行卡");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:800px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">绑定新的银行卡</span>
        </div>
        <div class="two_bankcard">
            <p>持卡人信息</p>
            <div class="bankcard">
                <label>真实姓名：</label>
                <input type="text" value="" name="owner"  placeholder="请输入真实姓名" style="height:40px;width:268px;margin-left:17px; padding-left:10px;font-size:14px" />
            </div>
            <div  class="bankcard">
                <label>身份证号：</label>
                <input type="text" value="" name="cert_no" placeholder="请输入身份证号码" style="height:40px;width:268px;margin-left:17px; 
                       padding-left:10px;font-size:14px"/>
            </div>
            <p>银行卡信息</p>
            <div  class="bankcard">
                <label>所属银行：</label>
                <select name="bk_name" style="height:40px;width:279px;margin-left:17px;padding-left:10px;font-size:14px">
                    <option>选择银行(必填)</option>
                    <?php if ($_smarty_tpl->tpl_vars['bank']->value){?>
                   <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['bank']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
&nbsp;&nbsp;&nbsp;(<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
)</option>
                    <?php } ?>
                    <?php }?>
                </select>
            </div>
            <div  class="bankcard">
                <label>&#12288;开户行：</label>
                <input type="text" value="" name="subbank_name" placeholder="(选填)详细到支行" style="height:40px;width:268px;margin-left:17px;  padding-left:10px;font-size:14px" />
            </div>
            <div  class="bankcard">
                <label>银行卡号：</label>
                <input type="text" name="card_no" value="" placeholder="银行卡信息需与持卡人一致" style="height:40px;width:268px;margin-left:17px;padding-left:10px;font-size:14px" />
            </div>
            <div class="bankcard">
                <label>手机号码：</label>
                <input type="text" name="phone" value="" placeholder="请输入银行预留手机号" style="height:40px;width:268px;margin-left:15px;padding-left:10px;font-size:14px"/>
            </div>
            <div class="bankcard">
            <button class="bankcard_btn">确认绑卡</button>
            </div>
            <!--  <button class="dx">发送短信</button>
             <button class="pay">确认支付</button>
             <button class="sc">支付查询</button> -->
            <div class="text">
                <p>温馨提示：</p>
                <p>1. 每位用户只能添加一张银行卡，该卡也是您提现的唯一银行卡</p>
                <p>2. 认证时会丛卡内试扣2.18元，认证结束后转入您的平台账户余额</p>
            </div>
        </div>
    </div>
</div>
</div>
<div id="km"></div>
<!-- 内容结束  -->

<!--回到顶部开始-->
<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>
<!--回到顶部结束-->

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>