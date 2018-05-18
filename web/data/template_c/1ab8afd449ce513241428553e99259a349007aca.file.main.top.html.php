<?php /* Smarty version Smarty-3.1-DEV, created on 2018-05-18 10:42:02
         compiled from "D:\phpstudy\WWW\ywf\web\view\index\template\inc\main.top.html" */ ?>
<?php /*%%SmartyHeaderCode:264955afe3d7a7011e1-75004411%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1ab8afd449ce513241428553e99259a349007aca' => 
    array (
      0 => 'D:\\phpstudy\\WWW\\ywf\\web\\view\\index\\template\\inc\\main.top.html',
      1 => 1478587147,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '264955afe3d7a7011e1-75004411',
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
  'unifunc' => 'content_5afe3d7a7570f6_87267804',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5afe3d7a7570f6_87267804')) {function content_5afe3d7a7570f6_87267804($_smarty_tpl) {?><!-- 头部结构开始-->
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
            }
        });
</script>

<!-- 头部结构结束-->
<?php }} ?>