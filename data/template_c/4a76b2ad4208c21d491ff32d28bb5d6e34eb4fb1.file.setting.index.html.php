<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-12 23:39:57
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/setting.index.html" */ ?>
<?php /*%%SmartyHeaderCode:27434834357fae94c37ebe8-51735691%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a76b2ad4208c21d491ff32d28bb5d6e34eb4fb1' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/setting.index.html',
      1 => 1476286793,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '27434834357fae94c37ebe8-51735691',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fae94c40c5f5_11818335',
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'loggedInUser' => 0,
    'userInfo' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fae94c40c5f5_11818335')) {function content_57fae94c40c5f5_11818335($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go_top.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/alert.js"></script>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/setting.index.js"></script>

<script>$("head>title").html("设置");</script>
<!--中间：内容-->
<div class="container_content">
    <!--我的账户-->
    <div class="top" style="height: 160px;">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">个人信息</span>
        </div>
        <div class="two_grxx">
            <i></i>
            <span><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['phone'];?>
，帐户安全级别：</span>
            <span class="progress"></span>
            <span class="progress_value">100%</span>
            <p>投资需要完成第<span>4、5、6</span>项</p>
        </div>
    </div>
    <!--资金流水-->
    <div class="low">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">账户安全</span>
        </div>
        <div class="two_zhaq">
            <ul>
                <li>
                    <div class="left">
                        <i id="icon1"></i>
                        <span>1.绑定手机</span>
                        <p>手机号码是您在亿万福重要的身份凭证</p>
                    </div>
                    <div class="right">
                        <span><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['phone'];?>
</span>
                    </div>
                </li>
                <li>
                    <div class="left">
                        <i id="icon2"></i>
                        <span>2.登录密码</span>
                        <p>登录亿万福的账号密码</p>
                    </div>
                    <div class="right"> 
                        <button onclick="location.href = '/cas/resetpassword/index'">修改</button>
                    </div>
                </li>
                <li>
                    <div class="left">
                        <i id="icon3"></i>
                        <span>3.交易密码</span>
                        <p>交易密码是进行交易使用的秘密</p>
                    </div>
                    <div class="right"> 
                        <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['is_pay_pwd'];?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1==1){?>    
                        <button onclick="location.href = '/cas/resetpassword/resetPaypwd'">修改</button>
                        <button onclick="location.href = '/cas/resetpassword/forgotPaypwd'">重置</button>
                        <?php }else{ ?>
                        <button onclick="location.href = '/cas/setting/tradePwd'">设置</button>
                        <?php }?>
                    </div>
                </li>
                <li>
                    <div class="left">
                        <i id="icon4"></i>
                        <span>4.邮寄地址</span>
                        <p>邮寄地址接收亿万福纸质资料使用</p>
                    </div>
                    <div class="right"> 
                        <span>（<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['address'];?>
<?php $_tmp2=ob_get_clean();?><?php if ($_tmp2){?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['address'];?>
<?php }else{ ?>未设置<?php }?>）</span>
                        <button onclick="location.href = '/cas/setting/contact'">设置</button>
                    </div>
                </li>
                <li>
                    <div class="left">
                        <i id="icon5"></i>
                        <span>5.实名认证</span>
                        <p>保障帐户安全，身份确认后，不能更换</p>
                    </div>
                    <div class="right">
                        <span>（<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['idcard'];?>
<?php $_tmp3=ob_get_clean();?><?php if ($_tmp3){?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['idcard'];?>
<?php }else{ ?>未认证<?php }?>）</span>
                    </div>
                </li>
                <li>
                    <div class="left">
                        <i id="icon6"></i>
                        <span>6.绑定银行卡</span>
                        <p>提现仅可至您所绑定银行卡中</p>
                    </div>
                    <div class="right"> 
                        <span>（<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['bank_no'];?>
<?php $_tmp4=ob_get_clean();?><?php if ($_tmp4){?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['bank_no'];?>
<?php }else{ ?>未绑卡<?php }?>）</span>
                    </div>
                </li>
                <li>
                    <div class="left">
                        <i id="icon7"></i>
                        <span>7.推荐人</span>
                        <p>推荐您使用亿万福的经纪人</p>
                    </div>
                    <div class="right"> 
                        <span><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['recommender'];?>
<?php $_tmp5=ob_get_clean();?><?php if ($_tmp5){?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['recommender'];?>
<?php }else{ ?><?php }?></span>
                        <button onclick="location.href = '/cas/setting/recommender'">修改</button>
                    </div>
                </li>
            </ul>
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