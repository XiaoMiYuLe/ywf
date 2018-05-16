<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-09 17:53:02
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/ecoman.index.html" */ ?>
<?php /*%%SmartyHeaderCode:131386618257fa137e2289e7-01284123%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9fad2818eaffc63726e08c1bddf1e8f4b530df7' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/ecoman.index.html',
      1 => 1475056731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '131386618257fa137e2289e7-01284123',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wrapper_prefix_center' => 0,
    '_STATIC_CDN' => 0,
    'is_invitaiton' => 0,
    'remarks' => 0,
    'avatar' => 0,
    'wrapper_suffix_center' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57fa137e2c2677_19665715',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fa137e2c2677_19665715')) {function content_57fa137e2c2677_19665715($_smarty_tpl) {?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['wrapper_prefix_center']->value)===null||$tmp==='' ? '' : $tmp);?>



<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/tab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go_top.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/alert.js"></script>
<!-- 弹框 js-->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/myAlert.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/scripts/upload/uploadPreview.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/webjs/cas/uppic.js"></script>

<script>$("head>title").html("经纪人认证");</script>
<!--中间：内容-->
 <form id="edit_form" action="/cas/ecoman/addpic" method="post" enctype="multipart/form-data">
<div class="container_content">
    <!--我的账户-->
    <div class="top">
        <div class="one">
            <div class="blue-line"></div>
            <h2>经纪人认证</h2>
        </div>
    </div>
    <div class="bottom">
       <!-- <div class="pic">
            <img src="/static/ywf/img/icon/jiaru.png" />
        </div>  -->
           <ul id="warp">
			  <li>
			   <div class="pic">
			   <?php if ($_smarty_tpl->tpl_vars['is_invitaiton']->value==2){?>
			   <p class="l-sh">审核中</p>
			   <?php }elseif($_smarty_tpl->tpl_vars['is_invitaiton']->value==3){?>
			   <p class="l-sh">审核失败，请重新上传</p>
			   <p class='l-sh-1'><?php if ($_smarty_tpl->tpl_vars['remarks']->value){?>审核失败原因:<?php echo $_smarty_tpl->tpl_vars['remarks']->value;?>
<?php }?></p>
			   <?php }?>
			   		<?php if ($_smarty_tpl->tpl_vars['avatar']->value){?>
            		<img id="imgShow_WU_FILE_0" src="<?php echo $_smarty_tpl->tpl_vars['avatar']->value;?>
" width="100" height="100"/>
            		<?php }else{ ?>
            		<img id="imgShow_WU_FILE_0" src="/static/ywf/img/icon/jiaru.png" width="100" height="100" />
            		<?php }?>
        		</div> 
			  	<?php if ($_smarty_tpl->tpl_vars['is_invitaiton']->value==1){?>	
			  	<p class="browse"><input type="file" name="avatar" value="" id="up_img_WU_FILE_0" style="opacity: 0;cursor: pointer;">浏览</p>
			  	<?php }elseif($_smarty_tpl->tpl_vars['is_invitaiton']->value==2){?>
			  	<?php }elseif($_smarty_tpl->tpl_vars['is_invitaiton']->value==3){?>
			  	<p class="browse"><input type="file" name="avatar" value="" id="up_img_WU_FILE_0" style="opacity: 0;cursor: pointer;">浏览</p>
			  	 <?php }elseif($_smarty_tpl->tpl_vars['is_invitaiton']->value==4){?>
			  	 <p>审核未通过</p>
			  	<?php }?>
			  	
			  </li>
		  </ul>
                   
        <p class="tiaokuan">
            <i class="choose" id="choose"></i>
            <span>已阅读并同意<a target="_blank" href="/cas/ecoman/ecoman">《经纪人条款》</a></span>
        </p>
        <?php if ($_smarty_tpl->tpl_vars['is_invitaiton']->value==1){?>	
        <p class="uploading"><a href="javascript:;" id="qrsc">确认上传</a></p>
        <?php }elseif($_smarty_tpl->tpl_vars['is_invitaiton']->value==2){?>
        <?php }elseif($_smarty_tpl->tpl_vars['is_invitaiton']->value==3){?>
        <p class="uploading"><a href="javascript:;" id="qrsc">确认上传</a></p>
        <?php }elseif($_smarty_tpl->tpl_vars['is_invitaiton']->value==4){?>
        <?php }?>
        <div class="prompt">
            <span>温馨提示</span>
            <ul>
                <li>1、注册手机号与名片号码保持一致；</li>
                <li>2、名片为实物名片拍摄，不接受图片名片及修改过内容的名片</li>
                <li>3、名片限金融从业人员，销售人员所属名片。</li>
            </ul>
        </div>
    </div>  
</div>
</form>
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