<?php /* Smarty version Smarty-3.1-DEV, created on 2018-05-18 10:42:02
         compiled from "D:\phpstudy\WWW\ywf\web\view\index\template\inc\center_top.html" */ ?>
<?php /*%%SmartyHeaderCode:318215afe3d7a8c25c8-22485874%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f82d5fea721d2a6e37d342fd2d4073f2dcf6b355' => 
    array (
      0 => 'D:\\phpstudy\\WWW\\ywf\\web\\view\\index\\template\\inc\\center_top.html',
      1 => 1478587147,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '318215afe3d7a8c25c8-22485874',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_STATIC_CDN' => 0,
    'loggedInUser' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5afe3d7a8fcf51_99021760',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5afe3d7a8fcf51_99021760')) {function content_5afe3d7a8fcf51_99021760($_smarty_tpl) {?><!-- 头部结构开始-->
<div class="nav_top_bg">
    <div class="nav_top w1200">
        <div class="nav_text fl">
            <a href="">客服电话：400-1369-998</a>
        </div>
        <ul class="nav_top_left fl">
            <li id="sj" style="position:relative;">
                <a href="javascript:;">手机版</a>
                <img id="sjshow" width="145" height="145" style="z-index: 99;position: absolute;top:30px;left:0;display: none;" src="/static/ywf/img/icon/webchart.jpg">
            </li>

            <li style="background: url(<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/img/icon/weixin.png) left center no-repeat;position: relative;" id="wx">
                <a href="javascript:;" id="wx">官方微信</a>
                <img id="wxshow" width="145" height="145" style="z-index: 99;position: absolute;top:30px;left:0;display: none;" src="/static/ywf/img/icon/webchart.jpg">
            </li>
            <li  style="background: url(<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/img/icon/help.png) left center no-repeat;">
                <a href="javascript:;" onclick="location.href='/page/help/index'">帮助中心<span></span></a>

            </li>
        </ul>
        <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value;?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1){?>
        <div class="nav_top_bg buy_nav"> 
            <div class="nav_top_right buy_nav_top_right fr">
                <a class="logo tel" href="/cas/user/index"><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['hidephone'];?>
</a>

                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['is_ecoman'];?>
<?php $_tmp2=ob_get_clean();?><?php if ($_tmp2==1){?>
                <a class="ren" href="">认证
                    <span></span>
                </a>
                <?php }else{ ?>

                <?php }?>
                <a class="regist tuichu" style="color: lightgray;" href="/cas/sign/out">退出</a>
            </div>
        </div>
        <?php }else{ ?>  
        <div class="nav_top_right fr">
            <a href="/cas/sign/in" class="logo">登录</a>
            <a href="/cas/sign/up" class="regist">立即注册</a>
        </div>
        <?php }?>
    </div>
</div>

<div class="nav_bg">
    <div class="nav">
        <a onclick="location.href = '/index/index'" href="javascript:;" class="logo">亿万福</a>
        <ul>
            <li onclick="location.href = '/index/index'"><a href="javascript:;" id="index" class="current">首页</a></li>
            <li onclick="location.href = '/goods/goods/index'"><a href="javascript:;" id="goods">理财产品</a></li>
            <li><a href="javascript:;" onclick="location.href = '/page/newbies/index'" id="newbies">新手指南</a></li>
            <li><a href="javascript:;" onclick="location.href = '/page/guarantee/index'" id="guarantee">安全保障</a></li>
            <li><a href="javascript:;" onclick="location.href = '/page/index/index'" id="aboutus">关于我们</a></li>
            <li onclick="location.href = '/cas/user/index'"><a href="javascript:;" id="mycount1">我的账户</a></li>
        </ul>
    </div>
</div>
<!-- 头部结构结束-->

<!--用户中心-->
<div class="container">
    <!--左边：内容-->
    <div class="container_left">
        <div class="top">
            <p><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['hidephone'];?>
</p>
            <p>
                <i></i>
                <i></i>
                <i></i>
            </p>
        </div>
        <div style="width:140px;height:1px;background-color:#e8e8e8;margin:0 auto"></div>
        <div class="low">
            <ul>
                <li onclick="location.href = '/cas/user/index'">
                    <span id="mycount">我的账户</span>
                </li>
                <li onclick="location.href = '/cas/order/index'">
                    <span id="mymoney">我的理财</span>
                </li>

                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['is_ecoman'];?>
<?php $_tmp3=ob_get_clean();?><?php if ($_tmp3==1){?>
                <li onclick="location.href = '/cas/user/broker'">
                    <span id="mybroker">经纪人</span>
                </li>
                <?php }else{ ?>
                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['is_invitaiton'];?>
<?php $_tmp4=ob_get_clean();?><?php if ($_tmp4==1){?>
                <li onclick="location.href = '/cas/ecoman/index'">
                    <span id="mybroker">经纪人</span>
                </li>
                <?php }else{?><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['is_invitaiton'];?>
<?php $_tmp5=ob_get_clean();?><?php ob_start();?><?php echo $_tmp5==2;?>
<?php $_tmp6=ob_get_clean();?><?php if ($_tmp6){?>
                <li onclick="location.href = '/cas/ecoman/index'">
                    <span id="mybroker">经纪人</span>
                </li>
                <?php }else{?><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['is_invitaiton'];?>
<?php $_tmp7=ob_get_clean();?><?php ob_start();?><?php echo $_tmp7==3;?>
<?php $_tmp8=ob_get_clean();?><?php if ($_tmp8){?>
                <li onclick="location.href = '/cas/ecoman/index'">
                    <span id="mybroker">经纪人</span>
                </li>
                <?php }}}?>

                <?php }?>

                <li onclick="location.href = '/cas/user/coupon'">
                    <span id="mycoupon">优惠券</span>
                </li>
                <li onclick="location.href = '/cas/setting/qrcode'">
                    <span id="myinvite">邀请好友</span>
                </li>
                <li onclick="location.href = '/cas/bank/index'">
                    <span id="mybank">银行卡</span>
                </li>
                <li onclick="location.href = '/cas/setting/index'">
                    <span id="mysetting">设置</span>
                </li>
            </ul>
        </div>
    </div>

    
    <script>
        $('#sj').mouseover(function(){
           $('#sjshow').show();
        });
        $("#sj").mouseout(function(){
            $("#sjshow").hide();
        });
        $('#wx').mouseover(function(){
           $('#wxshow').show();
        });
        $('#wx').mouseout(function(){
           $('#wxshow').hide();
        });

    </script>
    

    
    <script>
        $(document).ready(function () {
            var pathname = window.location.pathname;

            switch (pathname) {
                case '/index/index':
                    $("#index").css('border-bottom','3px solid #2197d7').css('color','#2197d7');
                    break;
                case '/goods/goods/index':
                    $("#goods").css('border-bottom','3px solid #2197d7').css('color','#2197d7');
                    break;
                case '/page/newbies/index':
                    $("#newbies").css('border-bottom','3px solid #2197d7').css('color','#2197d7');
                    break;
                case '/page/guarantee/index':
                    $("#guarantee").css('border-bottom','3px solid #2197d7').css('color','#2197d7');
                    break;
                case '/page/index/index':
                    $("#aboutus").css('border-bottom','3px solid #2197d7').css('color','#2197d7');
                    break;
                case '/cas/user/index':
                    $("#mycount1").css('border-bottom','3px solid #2197d7').css('color','#2197d7');
                    break;
                case '/cas/user/index':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mycount");
                    $("#mycount").addClass('span_blue');
                    break;
                case '/cas/order/index':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mymoney");
                    $("#mymoney").addClass('span_blue');
                    break;
                case '/cas/user/broker':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mybroker");
                    $("#mybroker").addClass('span_blue');
                    break;
                case '/cas/ecoman/index':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mybroker");
                    $("#mybroker").addClass('span_blue');
                    break;
                case '/cas/user/coupon':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mycoupon");
                    $("#mycoupon").addClass('span_blue');
                    break;
                case '/cas/setting/qrcode':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#myinvite");
                    $("#myinvite").addClass('span_blue');
                    break;
                case '/cas/bank/index':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mybank");
                    $("#mybank").addClass('span_blue');
                    break;
                case '/cas/setting/index':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mysetting");
                    $("#mysetting").addClass('span_blue');
                    break;
                    
                case '/cas/bank/addBank':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mybank");
                    $("#mybank").addClass('span_blue');
                    break;
                case '/cas/resetpassword/forgotPaypwd':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mysetting");
                    $("#mysetting").addClass('span_blue');
                    break;
                case '/cas/cash/index':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mycount");
                    $("#mycount").addClass('span_blue');
                    break;
                case '/cas/recharge/index':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mycount");
                    $("#mycount").addClass('span_blue');
                    break;    
                case '/cas/recharge/internetbank':
                   $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mycount");
                   $("#mycount").addClass('span_blue');
                   break;
                case '/cas/resetpassword/index':
                   $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mysetting");
                   $("#mysetting").addClass('span_blue');
                   break;    
                case '/cas/resetpassword/resetPaypwd':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mysetting");
                    $("#mysetting").addClass('span_blue');
                    break;       
                case '/cas/setting/contact':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mysetting");
                    $("#mysetting").addClass('span_blue');
                    break;      
                case '/cas/setting/recommender':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mysetting");
                    $("#mysetting").addClass('span_blue');
                    break;   
                default:
                    break;
            }
        });
    </script>
    <?php }} ?>