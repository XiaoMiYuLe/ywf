<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-09 16:54:32
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/recharge.index.html" */ ?>
<?php /*%%SmartyHeaderCode:21708675457fa05c813d634-75009007%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6ec77cb7e1f9dd69cf68fbe5cd398b53f4de2137' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/recharge.index.html',
      1 => 1475056732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21708675457fa05c813d634-75009007',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'asset' => 0,
    'card' => 0,
    'loggedInUser' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fa05c81a49c3_88246500',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fa05c81a49c3_88246500')) {function content_57fa05c81a49c3_88246500($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/myAlert.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/recharge/recharge.js"></script>
<script>$("head>title").html("充值");</script>


<!--中间：内容-->
<div class="container_content" >
    <!--我的账户-->
    <div class="top" style="min-height:786px">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">充值</span>
        </div>
        <div class="two_recharge" >
            <p class="ye">账户余额<span><?php echo $_smarty_tpl->tpl_vars['asset']->value;?>
元</span></p> 
            <div class="pay">
                <label>充值方式：</label>
                <span class="cur">
                    <img src="/static/ywf/img/pay2.png" alt=""/>
                    <i></i>
                </span>
                <span>
                    <img src="/static/ywf/img/pay1.png" alt="" onclick="location = '/cas/recharge/internetbank'"/>
                </span>
            </div>


            <div class="money_input" style="height:50px;padding-top: 20px;">
                <label style="float:left;margin-top:10px;">投资账户：</label>
                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['card']->value['bank_name'];?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1){?>
                <div class="yinghang" style="float:left;">
                    <ul>
                        <li>
                            <i style="padding-left: 0px;"><img src="<?php echo $_smarty_tpl->tpl_vars['card']->value['link'];?>
" alt=""/></i>
                            <span><?php echo $_smarty_tpl->tpl_vars['card']->value['bank_name'];?>
</span>
                            <strong style="color:#333;">尾号<?php echo $_smarty_tpl->tpl_vars['card']->value['bank_no'];?>
</strong> 
                        </li>
                        <li >
                            |    <strong><?php echo $_smarty_tpl->tpl_vars['card']->value['quota'];?>
</strong>
                        </li>
                    </ul>
                </div>
                <?php }else{ ?>
                <a href="/cas/bank/addbank"><span id="wbk" style="color:red;">未绑卡</span></a>
                <?php }?>
            </div> 


            <div class="money_input">
                <label>手机号码：</label>
                <span><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['hidephone'];?>
</span>
            </div>
            <div class="money_input">
                <label>充值金额：</label>
                <input type="text" vlaue="" id="money"  placeholder="100元起"/>
            </div>
            <div class="yzm">
                <label >验证码：</label>
                <input type="text" vlaue="" id="check_code" class="" placeholder="请输入验证码"/>
                <button class="getyzm_btn" id="code">获取验证码</button>
            </div>
            <button class="cz_btn" id="button1">充值</button>
            <input type="hidden" value="" id="order">
            <div class="text">
                <p>温馨提示</p>
                <p>1、单笔最小充值金额为2元，最大充值金额为100万。</p>
                <p>2、您需开通网上银行才能进行在线充值，如果您还没有开通网上银行，请致电所属银行客服电话询问开通流程。</p>
                <p>3、不同银行对线上支付均有不同的限额规定，如支付超过限额，请致电银行客服申请提高额度再来充值。</p>
                <p>各大银行的限额表您可查看： 《银行线上支付限额表》</p>
                <p>4、根据国家法规的规定，不受理各类信用卡的充值申请。</p>
                <p>5、充值中如果遇到任何问题，您也可点击右侧的在线客服寻求帮助。</p>
            </div>
        </div>
    </div>
</div>
</div>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_center']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>