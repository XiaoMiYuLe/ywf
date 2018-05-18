<?php /* Smarty version Smarty-3.1-DEV, created on 2018-05-18 10:42:02
         compiled from "D:\phpstudy\WWW\ywf\web\view\index\template\index.index.html" */ ?>
<?php /*%%SmartyHeaderCode:57125afe3d7a95ab60-52373508%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '137904fcd38eb2d232e1b3d292ac835c850f232f' => 
    array (
      0 => 'D:\\phpstudy\\WWW\\ywf\\web\\view\\index\\template\\index.index.html',
      1 => 1478587146,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '57125afe3d7a95ab60-52373508',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_front' => 0,
    '_STATIC_CDN' => 0,
    'advert' => 0,
    'v' => 0,
    'newgoods' => 0,
    'nv' => 0,
    'goods' => 0,
    'article_9' => 0,
    'article_12' => 0,
    'article_11' => 0,
    'article_13' => 0,
    'article_14' => 0,
    'wrapper_suffix_front' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5afe3d7aa39629_80202379',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5afe3d7aa39629_80202379')) {function content_5afe3d7aa39629_80202379($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_front']->value)===null||$tmp==='' ? '' : $tmp);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/index.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/gotop.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/goods.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tableft.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/jquery.SuperSlide.2.1.1.js"></script>
<script>$("head>title").html("亿万福");</script>
<div class="h350_banner" style="position: relative;">
<div class="banner">
    <ul>
        <?php if ($_smarty_tpl->tpl_vars['advert']->value){?>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['advert']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
        <li class="h350_min_w980" style="background: url('<?php echo $_smarty_tpl->tpl_vars['v']->value['image'];?>
') no-repeat center center;">
            <a target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['v']->value['link_url'];?>
"></a></li>
        <?php } ?>
        <?php }?>
    </ul>
    </div>	
    <div class="banner_dian hd">

        <ul></ul>

    </div>

</div>
<div class="banner_bottom_bg">
    <div class="banner_bottom">
        <div class="banner_list">
            <span class="icon icon_1"></span>
            <h3>雄厚背景</h3>
        </div>
        <div class="banner_list">
            <span class="icon icon_2"></span>
            <h3>严格风控</h3>
        </div>
        <div class="banner_list">
            <span class="icon icon_3"></span>
            <h3>安全兑付</h3>
        </div>
    </div>
</div>
<div class="body_content_bg">
    <div class="body_content w1200">
        <div class="first">
            <p class="f1">共有 <strong> 12400 </strong>位注册用户，总销售 <strong> 2.9 </strong>亿+，已兑付<strong>  2.5 </strong> 亿+</p>
            <p>数据截止日期：2016年10月</p>
        </div>
        <div class="second">
            
            <?php if ($_smarty_tpl->tpl_vars['newgoods']->value){?>
            <?php  $_smarty_tpl->tpl_vars['nv'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nv']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['newgoods']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nv']->key => $_smarty_tpl->tpl_vars['nv']->value){
$_smarty_tpl->tpl_vars['nv']->_loop = true;
?>
            <div class="left">
                <h1>新手福利</h1>
                <p class="xin">新手专享产品</p>
                <div class="rate fl">
                    <span class="shuzi"><?php echo $_smarty_tpl->tpl_vars['nv']->value['yield'];?>
</span>
                    <span>%</span>
                    <p>预期年化收益</p>
                </div>
                <div class="time fr">
                    <p class="tian"><?php echo $_smarty_tpl->tpl_vars['nv']->value['financial_period'];?>
天</p>
                    <p class="sy">期限</p>
                </div>
                <a href="/goods/goods/detail?goods_id=<?php echo $_smarty_tpl->tpl_vars['nv']->value['goods_id'];?>
" class="btn">立即投资</a>
                <span class="icon"></span>
            </div>
            <?php } ?>
            <?php }?>
            
            <div class="right">
                <h1>精选项目</h1>
                <?php if ($_smarty_tpl->tpl_vars['goods']->value){?>
                <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['goods']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
                <div class="left  rl">
                    <p class="xin"><?php echo $_smarty_tpl->tpl_vars['v']->value['goods_name'];?>
</p>
                        <div class="rate fl">
                            <span class="shuzi"><?php echo $_smarty_tpl->tpl_vars['v']->value['yield'];?>
</span>
                            <span>%</span>
                            <p>预期年化收益</p>
                        </div>
                        <div class="time fr">
                            <p class="tian"><?php echo $_smarty_tpl->tpl_vars['v']->value['financial_period'];?>
天</p>
                            <p class="sy">期限</p>
                        </div>
                    <a href="/goods/goods/detail?goods_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['goods_id'];?>
" class="btn">立即投资</a> 
                </div>
                <?php } ?>
                <?php }?>
            </div>
        </div>
        
        <div class="third">
            <div class=" third_box third_l">
                <div class="title">财富动态
                    <a href="javascript:;" onclick="location.href='/page/wealth/index'" style="float:right;margin-right:38px;color:#9d9d9d;font-size: 14px;">更多>></a>
                </div>
                <ul class="artile">
                    <?php if ($_smarty_tpl->tpl_vars['article_9']->value){?>
                    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['article_9']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
                    <li> 
                        <a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['link'];?>
"><img width="270" height="150" src="<?php echo $_smarty_tpl->tpl_vars['v']->value['image'];?>
" alt=""></a>
                        <p><a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</a></p>
                    </li>
                    <?php } ?>
                    <?php }?>
                </ul>
            </div>
            
            <div class=" third_box third_r">
                <div class="title">精选活动</div>
                <ul class="artile">
                    <?php if ($_smarty_tpl->tpl_vars['article_12']->value){?>
                    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['article_12']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
                    <li> 
                        <a href=""><img title="<?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
" width="270" height="210" src="<?php echo $_smarty_tpl->tpl_vars['v']->value['image'];?>
" alt=""></a>
                    </li>
                    <?php } ?>
                    <?php }?>
                </ul>
            </div>
        </div>
        
        <div class=" four">
            <div class="four_box  four_l " >
                <div class="title">媒体报道
                    <a href="javascript:;" onclick="location.href='/page/media/index'" style="float:right;margin-right:38px;color:#9d9d9d;font-size: 14px;">更多>></a>
                    
                </div>
                <ul class="artile four_artile">
                    <?php if ($_smarty_tpl->tpl_vars['article_11']->value){?>
                    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['article_11']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
                    <li> 
                        <a href="/page/media/detail?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['content_id'];?>
"><img width="220" height="70" src="<?php echo $_smarty_tpl->tpl_vars['v']->value['image'];?>
" alt=""></a>
                        <a style="color: #333333" href="/page/media/detail?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['content_id'];?>
"><p class="mtitle"><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</p></a>
                        <div class="text">
                            <span><?php echo $_smarty_tpl->tpl_vars['v']->value['alias'];?>
</span>
                        </div>
                    </li>
                    <?php } ?>
                    <?php }?>
                </ul>
            </div>
            <div class=" four_box   four_tab">
                <ul class="four_tab_title">
                    <li class="current"><a href="javascript:;">理财公告</a></li>
                    <li><a href="javascript:;">常见问题</a></li>
                </ul>
                <ol class="four_tab_content">
                    <?php if (!empty($_smarty_tpl->tpl_vars['article_13']->value)){?>
                    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['article_13']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
                       <li><a href="/page/platform/detail?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['news_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['news_title'];?>
</a></li>
                    <?php } ?>
                    <?php }?>
                    <a href="javascript:;" onclick="location.href='/page/platform/index'" style="float:right;margin-right:20px;margin-bottom:9px;color:#9d9d9d;">查看更多>></a>
                </ol>
                <ol class="four_tab_content" style="display:none;">
                    <?php if (!empty($_smarty_tpl->tpl_vars['article_14']->value)){?>
                    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['article_14']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
                       <li><a href="/page/help/detail?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['content_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</a></li>
                    <?php } ?>
                    <?php }?>
                    <a href="javascript:;" onclick="location.href='/page/help/index'" style="float:right;margin-right:20px;margin-bottom:9px;color:#9d9d9d;">查看更多>></a>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="sidebar_nav">
    <a href="" class="gotop"><span>回顶部</span></a>
    <a href="" class="kefu"><span>客服</span></a>
    <a href="" class="app"><span>手机APP</span></a>
</div>



<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_suffix_front']->value)===null||$tmp==='' ? '' : $tmp);?>

<?php }} ?>