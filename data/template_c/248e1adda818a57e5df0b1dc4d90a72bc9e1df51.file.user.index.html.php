<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-16 22:55:05
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/user.index.html" */ ?>
<?php /*%%SmartyHeaderCode:122876051957f9fc653abd10-14621965%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '248e1adda818a57e5df0b1dc4d90a72bc9e1df51' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/user.index.html',
      1 => 1476629704,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '122876051957f9fc653abd10-14621965',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f9fc65433f24_93734587',
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'is_ecoman' => 0,
    'invitaiton' => 0,
    'username' => 0,
    'money' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f9fc65433f24_93734587')) {function content_57f9fc65433f24_93734587($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>


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
/ywf/webjs/cas/user.index.js"></script>

<!-- datepicker -->
<link href="/static/scripts/datepicker/css/datepicker3.css" type="text/css" rel="stylesheet" />
<script src="/static/scripts/datepicker/js/bootstrap-datepicker.js"></script>
<script src="/static/scripts/datepicker/js/locales/bootstrap-datepicker.zh-CN.js" charset="UTF-8"></script>

<script>$("head>title").html("我的账户");</script>
<!--中间：内容-->
<div class="container_content">
    <!--我的账户-->
    <div class="top">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">我的账户</span>
            <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['is_ecoman']->value==1;?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1){?>
            
            <?php }else{ ?>
            <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['invitaiton']->value==1;?>
<?php $_tmp2=ob_get_clean();?><?php if ($_tmp2){?>
            <p>
                <i></i>
                <span>系统通知：用户 <?php echo $_smarty_tpl->tpl_vars['username']->value;?>
，邀请您加入经纪人,</span>
                <a href="javascript:;" onclick="location.href = '/cas/ecoman/index'">点击接受邀请</a>
            </p>
            <?php }?>
            <?php }?>
        </div>
        <div class="two">
            <p class="left">
                <span>总资产</span>
                <em><?php echo $_smarty_tpl->tpl_vars['money']->value['all_asset'];?>
元</em>
            </p>
            <div style="width:2px;height:40px;background-color:#e8e8e8;float:left;margin-top:22px"></div>
            <p class="right">
                <span>昨日收益</span>
                <em><?php echo $_smarty_tpl->tpl_vars['money']->value['last_income'];?>
元</em>
            </p>
        </div>
        <div class="three" style="height: 166px;">
            <p>
                <span>累计收益</span>
                <em><?php echo $_smarty_tpl->tpl_vars['money']->value['count_income'];?>
元</em>
                <a href="javascript:;" onclick="location.href = '/cas/user/income'">收益记录</a>
            </p>
            <div style="width:2px;height:90px;background-color:#f4f4f4;float:left;margin-top:42px"></div>
            <p>
                <span>待收本金</span>
                <em><?php echo $_smarty_tpl->tpl_vars['money']->value['principal_all'];?>
元</em>
                <a href="javascript:;" onclick="location.href = '/cas/order/index'">投资记录</a>
            </p>
            <div style="width:2px;height:90px;background-color:#f4f4f4;float:left;margin-top:42px"></div>
            <p>
                <span>冻结金额</span>
                <em><?php echo $_smarty_tpl->tpl_vars['money']->value['brokerage_one'];?>
元</em>
            </p>
            <div style="width:2px;height:90px;background-color:#f4f4f4;float:left;margin-top:42px"></div>
            <p>
                <span>可用余额</span>
                <em><?php echo $_smarty_tpl->tpl_vars['money']->value['asset_sum'];?>
元</em>
                <span>
                    <button style="background-color:#ff7003" onclick="location.href = '/cas/recharge/index'">充值</button>
                    <button style="background-color:#2197d7" onclick="location.href = '/cas/cash/index'">提现</button>
                </span>
            </p>
        </div>
    </div>
    <!--资金流水-->
    <div class="low">
        <div class="one">
            <div class="blue-line"></div>
            <span class="title">资金流水</span>
        </div>
        <div class="two">
            <div class="date-div">
                <label>起止日期：</label> 
                <input type="text" data-date-format="yyyy-mm-dd" name="start_time" class="datepicker-input" placeholder="起始日期" value=""  />
                <i class="time-icon1"></i>
                -
                <input type="text" class="datepicker-input" data-date-format="yyyy-mm-dd" name="end_time"  placeholder="结束日期" value="" />
                <i class="time-icon2" style="left: 440px;"></i>&nbsp;
                <button class="btn-default" style="height: 30px;" id="queding">确定</button>
            </div>
            <div class="tab">
                <label>交易类型：</label>
                <button class="tab-btn" id="quanbu1">全部</button>
                <button id="goumai1">购买产品</button>
                <button id="shouyi1">产品收益</button>
                <button id="chongzhi1">充值</button>
                <button id="tixian1">提现</button>
                <button id="yongjin1">佣金</button>
            </div>
        </div>
        <div class="three" id="three">
            <table class="" id="" style="white-space: nowrap;" >
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>类型</th>
                        <th>金额（元）</th>
                        <th>账户余额（元）</th>
                    </tr>
                </thead>

                <tbody id="quanbu">
                </tbody>
            </table>

            <table class="" id=""  style="white-space: nowrap;display:none" >
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>类型</th>
                        <th>金额（元）</th>
                        <th>账户余额（元）</th>
                    </tr>
                </thead>
                <tbody id="goumai">

                </tbody>
            </table>
            
              <table class="" id=""  style="white-space: nowrap;display:none" >
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>类型</th>
                        <th>金额（元）</th>
                        <th>账户余额（元）</th>
                    </tr>
                </thead>
                <tbody id="shouyi">

                </tbody>
            </table>
            
              <table class="" id=""  style="white-space: nowrap;display:none" >
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>类型</th>
                        <th>金额（元）</th>
                        <th>账户余额（元）</th>
                    </tr>
                </thead>
                <tbody id="chongzhi">

                </tbody>
            </table>
            
              <table class="" id=""  style="white-space: nowrap;display:none" >
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>类型</th>
                        <th>金额（元）</th>
                        <th>账户余额（元）</th>
                    </tr>
                </thead>
                <tbody id="tixian">

                </tbody>
            </table>

            <table class="" id=""  style="white-space: nowrap;display:none" >
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>类型</th>
                        <th>金额（元）</th>
                        <th>账户余额（元）</th>
                    </tr>
                </thead>
                <tbody id="yongjin">

                </tbody>
            </table>
            
            
        </div>
    </div>

    <div class="page_count"></div>
    <div id="paging" class="m-pagination"></div>
    <div id="page"  style="display: none;" class="m-pagination"></div>
    <div id="page1" style="display: none;" class="m-pagination"></div>
    <div id="page2" style="display: none;" class="m-pagination"></div>
    <div id="page3" style="display: none;" class="m-pagination"></div>
    <div id="page4" style="display: none;" class="m-pagination"></div>
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