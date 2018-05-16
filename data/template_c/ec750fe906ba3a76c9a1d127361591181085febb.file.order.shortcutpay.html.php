<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-18 02:13:57
         compiled from "/usr/local/httpd/htdocs/php/view/bts/template/order.shortcutpay.html" */ ?>
<?php /*%%SmartyHeaderCode:30841192757fa05becc9da6-42317119%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ec750fe906ba3a76c9a1d127361591181085febb' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/bts/template/order.shortcutpay.html',
      1 => 1476728033,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '30841192757fa05becc9da6-42317119',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fa05bedc9614_63480447',
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    '_STATIC_CDN' => 0,
    'goods_name' => 0,
    'order_no' => 0,
    'type' => 0,
    'real_money' => 0,
    'buy_money' => 0,
    'yield' => 0,
    'cash_time' => 0,
    'card' => 0,
    'voucher_count' => 0,
    'voucher' => 0,
    'v' => 0,
    'phone' => 0,
    'asset' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fa05bedc9614_63480447')) {function content_57fa05bedc9614_63480447($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/gotop.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/rightnow_buy.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/order/order.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/order/yuer.js"></script>
<script>$("head>title").html("支付验证");</script>
<div class="buy_content remaining_buy">
    <div class="box_content">
        <div class="buy_first">
            <div class="buy_first_l">
                <h2><?php echo $_smarty_tpl->tpl_vars['goods_name']->value;?>

                    <a href="">
                        （订单号：<strong><?php echo $_smarty_tpl->tpl_vars['order_no']->value;?>
</strong>）
                    </a>
                    <span><?php if ($_smarty_tpl->tpl_vars['type']->value){?><?php echo $_smarty_tpl->tpl_vars['real_money']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['buy_money']->value;?>
<?php }?>元</span>
                </h2>
                <p>
                    <span>年化收益：</span>
                    <em><?php echo $_smarty_tpl->tpl_vars['yield']->value;?>
%</em>
                </p>
                <p>
                    <span>兑付日期：</span>
                    <em><?php echo $_smarty_tpl->tpl_vars['cash_time']->value;?>
</em>
                </p>
            </div>
            <div class="buy_first_r">
                <p class="mt">支付遇到任何问题，请致电</p>
                <p>400-1369-998</p>
            </div>	  	 
        </div>
        <div class="buy_icon"> 	  	  	  
  	 	  </div>
        <div class="top" style="min-height:786px">   	  	 
            <div class="two_recharge buy_two_recharge " style="border-bottom: none;margin-right: 30px;">            
                <div class="pay">
                    <label>支付方式：</label>
                    <span class="cur">
                        <img src="/static/ywf/img/pic/pay2.png" alt=""/>
                        <i></i>
                    </span>
                    <span>
                        <img src="/static/ywf/img/pic/pay3.png" alt="" />
                    </span>
                </div>
                <!-- 改的结构 -->
                <div class="buy_box"  style="">
                    <div class="money_input" style="height:50px;padding-top: 20px;">
                        <label style="float:left;margin-top:10px;">投资账户：</label>
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
			              			|   <strong><?php echo $_smarty_tpl->tpl_vars['card']->value['quota'];?>
</strong>
			              		</li>
			              	</ul>
					    </div>
                    </div>
                    <?php if ($_smarty_tpl->tpl_vars['type']->value){?>
                    <?php }else{ ?>
                       <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['voucher_count']->value;?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1>0){?>
                      <div class="money_input">
                        <label style="float: left;margin-top: 10px;">使用优惠券：</label>
                        <div class="out-discount">
                            <span  id="discount" ><?php echo $_smarty_tpl->tpl_vars['voucher_count']->value;?>
</span>
                            <input type="hidden"  id="voucher_k" value="">
                            <i></i>
                            <ul class="discount">
	                            <li>
	                                 <span><?php echo $_smarty_tpl->tpl_vars['voucher_count']->value;?>
</span>
	                            </li>
                            <?php if ($_smarty_tpl->tpl_vars['voucher']->value){?>
                            	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['voucher']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
	                                <li>
	                                    <input type="radio"  name="danxuan" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
	                                    <input type="hidden"  name="voucher_type_kj" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['type'];?>
">
	                                    <input type="hidden"  name="voucher_money" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['voucher_money'];?>
">
	                                    <input type="hidden"  name="use_money" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['use_money'];?>
">
	                                    <span class="pp"><?php echo $_smarty_tpl->tpl_vars['v']->value['right_title'];?>
<b><?php echo $_smarty_tpl->tpl_vars['v']->value['content_money'];?>
</b></span>
	                                    <span><?php echo $_smarty_tpl->tpl_vars['v']->value['remarks'];?>

	                                </li>
                                <?php } ?>
                             <?php }?>
                            </ul>
                        </div>	
                    </div>
                    <?php }?>
                    <?php }?>
                    <div class="buy_content_line" style="height: 2px;border-bottom: 1px solid #ccc;margin-top: 18px;"></div>
                    <!-- 改的结构 -->
                    <div class="money_input" style="">
                        <label>支付金额：</label>
                        <span><em style="color:#ff7003;font-size: 18px;"><?php if ($_smarty_tpl->tpl_vars['type']->value){?><?php echo $_smarty_tpl->tpl_vars['real_money']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['buy_money']->value;?>
<?php }?></em>元</span>
                    	</span><span id="voucher_tishi" style="color:red;"></span>
                    </div>	
                    <div class="money_input">
                        <label>手机号码：</label>
                        <span><?php echo $_smarty_tpl->tpl_vars['phone']->value;?>
</span>
                    </div>
                    <div class="yzm yzm1" style="padding-left: 26px;">
                        <label >短信验证：</label>
                        <input type="text" vlaue="" id="check_code" class=""/>
                        <button class="getyzm_btn getyzm_btn1" id="code">获取验证码</button>
                    </div>                            
                    <button class="cz_btn cz_btn1" id="button1" style="margin-left: 123px;">确认支付</button>  
                    <div class="text ytext1">
                        <b class="choose" id="choose"></b>
                        <em>已阅读并同意<a target="_blank" href="/cas/word/agreement">《借款协议》</a>充分了解相关权利和义务，并愿意承担相关风险</em>
                    </div>
                </div>
                <input type="hidden" id="order" value="<?php echo $_smarty_tpl->tpl_vars['order_no']->value;?>
">
                <input type="hidden" id="money" value="<?php echo $_smarty_tpl->tpl_vars['buy_money']->value;?>
">
                <!-- 改的结构 -->
                <div class="buy_box"  style="display:none;">
                    <div class="money_input">
                        <label>可用余额：</label>
                        <div class="yu">
                            <span><?php echo $_smarty_tpl->tpl_vars['asset']->value;?>
元</span>
                            <!-- <em>余额不足</em> -->
                            <em>    </em>
                            <a href="/cas/recharge/index">充值</a>
                        </div>
                    </div>
                    <?php if ($_smarty_tpl->tpl_vars['type']->value){?>
                    <?php }else{ ?>
                    <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['voucher_count']->value;?>
<?php $_tmp2=ob_get_clean();?><?php if ($_tmp2>0){?>
                    <div class="money_input" style="padding-top: 20px;">
                        <label style="float: left;margin-top: 10px;">使用优惠券：</label>
                        <div class="out-discount">
                            <span  id="discount" class="ydiscount"><?php echo $_smarty_tpl->tpl_vars['voucher_count']->value;?>
</span>
                            <input type="hidden"  id="voucher_y" value="">
                            <i></i>
                            <ul class="discount  yydiscount">
                                <li>
                                    <span><?php echo $_smarty_tpl->tpl_vars['voucher_count']->value;?>
</span>
                                </li>
                               <?php if ($_smarty_tpl->tpl_vars['voucher']->value){?>
                            	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['voucher']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
	                                <li>
	                                    <input type="radio"  name="danxuan" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
	                                    <input type="hidden"  name="voucher_type_yr" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['type'];?>
">
	                                    <input type="hidden"  name="voucher_money_yr" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['voucher_money'];?>
">
	                                    <input type="hidden"  name="use_money_yr" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['use_money'];?>
">
	                                    <span class="pp"><?php echo $_smarty_tpl->tpl_vars['v']->value['right_title'];?>
<b><?php echo $_smarty_tpl->tpl_vars['v']->value['content_money'];?>
</b></span>
	                                    <span><?php echo $_smarty_tpl->tpl_vars['v']->value['remarks'];?>
</span>
	                                </li>
                                <?php } ?>
                             <?php }?>
                            </ul>
                        </div>	
                    </div>
                    <?php }?>
                    <?php }?>
                    <div class="buy_content_line" style="height: 2px;border-bottom: 1px solid #ccc;margin-top: 18px;"></div>					  <div class="money_input">
                        <label>支付金额：</label>
                        <span><em style="color:#ff7003;font-size: 18px;"><?php if ($_smarty_tpl->tpl_vars['type']->value){?><?php echo $_smarty_tpl->tpl_vars['real_money']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['buy_money']->value;?>
<?php }?></em>元</span>
                        <span id="voucher_tishi_yr" style="color:red;"></span>
                    </div>	
                    <div class="money_input">
                        <label>交易密码：</label>
                        <input type="password" minlength="6" maxlength="6" id="pay_pwd" vlaue="" placeholder="请输入交易密码"/>
                    </div> 
                    <div class="val_group">	
                        <p class="fgtpwd"><a href="/cas/resetpassword/forgotPaypwd" style="color:#2197d7;font-size:14px;margin-left:298px;">忘记密码?</a></p>					
                    </div>                                     
                    <button class="cz_btn cz_btn1" id="button2">确认支付</button>   
                    <div class="text ytext1" style="line-height:0px;padding-top:20px;">
                        <b class="choose" id="choose1"></b>
                        <em>已阅读并同意<a target="_blank" href="/cas/word/agreement">《借款协议》</a>充分了解相关权利和义务，并愿意承担相关风险</em>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div> 
 <input type="hidden"  name="buy_money_voucher" value="<?php echo $_smarty_tpl->tpl_vars['buy_money']->value;?>
">


<!-- 尾部结构结束  -->
<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>


<script type="text/javascript">
    $(function () {
        var iHtml = "<i></i>";
        $('.bank').delegate('span', 'click', function (event) {
            $(this).append(iHtml).siblings('span').children('i').remove();
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


<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_front']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>