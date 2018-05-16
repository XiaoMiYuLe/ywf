<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-09 16:25:24
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/user.customrecord.html" */ ?>
<?php /*%%SmartyHeaderCode:196354054157f9fef41c7e26-78389959%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '28b0b9cd0f9c0dff3751f98379b7f574bc84a369' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/user.customrecord.html',
      1 => 1475056733,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '196354054157f9fef41c7e26-78389959',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    '_STATIC_CDN' => 0,
    'loggedInUser' => 0,
    'broker' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f9fef422be91_49533979',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f9fef422be91_49533979')) {function content_57f9fef422be91_49533979($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go_top.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/alert.js"></script>
<!--分页插件-->
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination.css"/>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/jquery-pagination/jquery.pagination-1.2.7.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/user.customrecord.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/middleman.js"></script>

<script>$("head>title").html("经纪人");</script>
<!--中间：内容-->
<div class="middleman">
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
                        <span>我的账户</span>
                    </li>
                    <li onclick="location.href = '/cas/order/index'">
                        <span>我的理财</span>
                    </li>
                    <li onclick="location.href = '/cas/user/broker'">
                        <span id="mybroker">经纪人</span>
                    </li>
                    <li onclick="location.href = '/cas/user/coupon'">
                        <span>优惠券</span>
                    </li>
                    <li onclick="location.href = '/cas/setting/qrcode'">
                        <span>邀请好友</span>
                    </li>
                    <li onclick="location.href = '/cas/bank/index'">
                        <span>银行卡</span>
                    </li>
                    <li onclick="location.href = '/cas/setting/index'">
                        <span>设置</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container_content">
            <!--我的账户-->
            <div class="top">
                <div class="one">
                    <div class="blue-line"></div>
                    <h2>经纪人</h2>
                </div>
                <div class="two">
                    <p>
                        <span><?php echo $_smarty_tpl->tpl_vars['broker']->value['brokerage_one'];?>
</span>
                        <em>元</em>
                        <a href="javascript:;">已结佣金</a>
                    </p>
                    <div style="width:2px;height:27px;background-color:#b3b3b3;float:left;margin-top:25px"></div>
                    <p>
                        <span><?php echo $_smarty_tpl->tpl_vars['broker']->value['brokerage_two'];?>
</span>
                        <em>元</em>
                        <a href="javascript:;">待结佣金</a>
                    </p>		                      
                </div>

                <div class="middledown">
                    <ul class="father">
                        <li onclick="location.href = '/cas/user/broker'" id="myyongjin">我的佣金</li>
                        <li onclick="location.href = '/cas/user/customRecord'" id="kehujiaoyi" class="current">客户交易</li>
                        <li onclick="location.href = '/cas/user/myClient'" id="mykehu">我的客户</li>
                    </ul>
                    <div class="menu_box  ">
                        <!--客户交易 start-->
                        <div class="second_menu qie">
                            <h2>订单状态：</h2>
                            <ol class="son">
                                <li class="current1" id="jixizhong1">计息中</li>
                                <li id="yiduifu1">已兑付</li>
                            </ol> 		   	  	  	       
                            <div class="three  menu_list " style="padding-top: 70px;">
                                <table class="down_tab cur" id=""  style="white-space: nowrap;">
                                    <thead>
                                        <tr>
                                            <th>客户名称</th>
                                            <th>购买项目</th>									            	    	  
                                            <th>投资金额（元）</th>
                                            <th>下单时间</th>
                                            <th>兑付日期</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jixizhong">
                                    </tbody>

                                </table>
                                <table class="down_tab" id=""  style="white-space: nowrap;"  >
                                    <thead>
                                        <tr>
                                            <th>客户名称</th>
                                            <th>购买项目</th>									            	    	  
                                            <th>投资金额（元）</th>
                                            <th>下单时间</th>
                                            <th>兑付日期</th>
                                        </tr>
                                    </thead>

                                    <tbody id="yiduifu">
                                    </tbody>

                                </table>
                            </div>
                        </div>	
                        <!--客户交易 end-->
                    </div>  
                </div>
            </div>

            <div class="page_count "></div>
            <div id="paging"></div>
            <div id="page"></div>
        </div>
    </div>

    <!--回到顶部开始-->
    <div class="sidebar_nav">
        <a href="" class="gotop"><span>回顶部</span></a>
        <a href="" class="kefu"><span>客服</span></a>
        <a href="" class="app"><span>手机APP</span></a>
    </div>

    
    <script>
        $(document).ready(function () {
            var pathname = window.location.pathname;

            switch (pathname) {
                case '/cas/user/customRecord':
                    $("<span style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px' class='span_add'></span>").prependTo("#mybroker");
                    $("#mybroker").addClass('span_blue');
                    break;
                default:
                    break;
            }
        });
    </script>
    

</div>
<!--回到顶部结束-->
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_front']->value)===null||$tmp==='' ? '' : $tmp);?>
<?php }} ?>