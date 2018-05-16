<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 11:08:43
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/setting.recommender.html" */ ?>
<?php /*%%SmartyHeaderCode:28958534957fc57bbe2efd8-62967670%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3933a5eeca0b6683b7923a78889a38cd741308db' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/setting.recommender.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28958534957fc57bbe2efd8-62967670',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_URL' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fc57bbe75108_91510427',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fc57bbe75108_91510427')) {function content_57fc57bbe75108_91510427($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/ywf/webjs/cas/setting.recommender.js" type="text/javascript"></script>

<script>$("head>title").html("修改推荐人");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:630px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">修改推荐人</span>
        </div>
        <div class="two_xgdlmm">
            <div id="div1">
                <label>推荐人：</label>
                <input type="text" value="" name="recommender" placeholder="推荐人的手机号或邀请码" id="zhjymm_input1"/>
            </div>
            <div class="btn">
                <button id="form-submit" style="margin-left:1px">确认</button>
            </div>
        </div>
    </div>
</div>
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>