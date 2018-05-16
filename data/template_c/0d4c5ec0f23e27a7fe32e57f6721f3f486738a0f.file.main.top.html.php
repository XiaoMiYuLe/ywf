<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-08 15:11:13
         compiled from "/usr/local/httpd/htdocs/php/view/panel/template/inc/main.top.html" */ ?>
<?php /*%%SmartyHeaderCode:201182078057f89c110afa81-40403372%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0d4c5ec0f23e27a7fe32e57f6721f3f486738a0f' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/panel/template/inc/main.top.html',
      1 => 1475056750,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '201182078057f89c110afa81-40403372',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_STATIC_URL' => 0,
    '_SITE_NAME' => 0,
    'loggedInUser' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f89c110cfd01_82195511',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f89c110cfd01_82195511')) {function content_57f89c110cfd01_82195511($_smarty_tpl) {?><!-- .header -->
<header class="bg-dark dk header navbar navbar-fixed-top-xs">
    <div class="navbar-header aside-md">
        <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
            <i class="fa fa-bars"></i>
        </a>
        <a href="javascript:;" class="navbar-brand" data-toggle="fullscreen">
            <img src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_URL']->value;?>
/panel/img/logo_bluemobi.png" class="m-r img-circle"><?php echo $_smarty_tpl->tpl_vars['_SITE_NAME']->value;?>

        </a>
        <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
            <i class="fa fa-cog"></i>
        </a>
    </div>
    
    <ul class="nav navbar-nav hidden-xs">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle dker" data-toggle="dropdown">
                <i class="fa fa-building-o"></i> 
                <span class="font-bold">面板</span>
            </a>
            <section class="dropdown-menu aside-xl on animated fadeInLeft no-borders lt">
                <div class="wrapper lter m-t-n-xs">
                    <a href="#" class="thumb pull-left m-r">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['avatar'];?>
" class="img-circle">
                    </a>
                    <div class="clear">
                        <a href="javascript:;"><span class="text-white font-bold">@<?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['fullname'];?>
</span></a>
                        <small class="block">超级管理员</small>
                    </div>
                </div>
            </section>
        </li>
    </ul>
    
    <?php if (false){?>
    <ul class="nav navbar-nav hidden-xs reset-combo">
        <li class="col-sm-12">
            最近登录时间：1212121212
        </li>
    </ul>
    <ul class="nav navbar-nav hidden-xs reset-combo">
        <li class="col-sm-12">
            最后登录IP：123123123
        </li>
        <li class="col-sm-12">
            当前登录IP：123131313
        </li>
    </ul>
    <?php }?>
     
    <ul class="nav navbar-nav navbar-right hidden-xs nav-user">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="thumb-sm avatar pull-left">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['avatar'];?>
">
                </span>
                <?php echo $_smarty_tpl->tpl_vars['loggedInUser']->value['fullname'];?>
 <b class="caret"></b>
            </a>
            <ul class="dropdown-menu animated fadeInRight">
                <li>
                    <a href="/admin/sign/out" class="sign-out">注销</a>
                </li>
            </ul>
        </li>
    </ul>      
</header>
<!-- /.header --><?php }} ?>