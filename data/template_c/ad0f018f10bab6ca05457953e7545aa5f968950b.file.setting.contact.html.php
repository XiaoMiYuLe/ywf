<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-11 15:08:40
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/setting.contact.html" */ ?>
<?php /*%%SmartyHeaderCode:85742390857fc8ff87320b2-11899107%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad0f018f10bab6ca05457953e7545aa5f968950b' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/setting.contact.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '85742390857fc8ff87320b2-11899107',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_URL' => 0,
    'contact' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fc8ff879bbb0_23542965',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fc8ff879bbb0_23542965')) {function content_57fc8ff879bbb0_23542965($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/ywf/webjs/cas/setting.contact.js" type="text/javascript"></script>

<script>$("head>title").html("联系方式");</script>
<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:630px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">修改联系方式</span>
        </div>
        <div class="two_xgdlmm">
            <div id="div1">
                <label>详细地址：</label>
                <input type="text" value="<?php if ($_smarty_tpl->tpl_vars['contact']->value){?><?php echo $_smarty_tpl->tpl_vars['contact']->value['address'];?>
<?php }?>" name="address" placeholder="请输入详细地址" id="zhjymm_input1"/>
            </div>
            <div>
                <label>邮&#12288;&#12288;编：</label>   
                <input type="text" value="<?php if ($_smarty_tpl->tpl_vars['contact']->value){?><?php echo $_smarty_tpl->tpl_vars['contact']->value['zip_code'];?>
<?php }?>" name="zip_code" placeholder="请输入邮编" id="zhjymm_input1"/>
            </div>
            <div>
                <label>联系人&#12288;：</label>
                <input type="text" value="<?php if ($_smarty_tpl->tpl_vars['contact']->value){?><?php echo $_smarty_tpl->tpl_vars['contact']->value['contacts_person'];?>
<?php }?>" name="contacts_person" placeholder="请输入联系人姓名" id="zhjymm_input1"/>
            </div>
            <div>
                <label>联系电话：</label>
                <input type="text" value="<?php if ($_smarty_tpl->tpl_vars['contact']->value){?><?php echo $_smarty_tpl->tpl_vars['contact']->value['contacts_phone'];?>
<?php }?>" name="contacts_phone" placeholder="请输入联系人电话" id="zhjymm_input1"/>
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