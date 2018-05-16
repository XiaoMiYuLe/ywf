<?php /* Smarty version Smarty-3.1-DEV, created on 2016-10-08 15:11:13
         compiled from "/usr/local/httpd/htdocs/php/view/panel/template/inc/main.side.html" */ ?>
<?php /*%%SmartyHeaderCode:122445171757f89c110d2310-85221016%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ee55bff23ff547e53ca46b1b34ff7c62d593a0a0' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/panel/template/inc/main.side.html',
      1 => 1475056750,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '122445171757f89c110d2310-85221016',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'navigations' => 0,
    'v' => 0,
    'loggedInUser' => 0,
    'allow_navs' => 0,
    'vv' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_57f89c11136be7_75695473',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f89c11136be7_75695473')) {function content_57f89c11136be7_75695473($_smarty_tpl) {?><!-- .aside -->
<aside class="bg-dark lter aside-md hidden-print" id="nav">
    <section class="vbox">
        <section class="w-f scrollable">
            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

                <!-- nav -->
                <div class="tab-content">
	                <div id="nav_content" class="tab-pane fade active in nav-content">
		                <nav class="nav-primary hidden-xs">
		                    <ul class="nav" id="left_nav">
		                        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navigations']->value['nav_two']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
		                        <?php if ($_smarty_tpl->tpl_vars['v']->value['parent_id']==1&&mb_strlen($_smarty_tpl->tpl_vars['v']->value['hid'], 'UTF-8')==11&&($_smarty_tpl->tpl_vars['loggedInUser']->value['groupid']==1||strpos($_smarty_tpl->tpl_vars['allow_navs']->value,$_smarty_tpl->tpl_vars['v']->value['hid'])!==false)){?>
		                        <li>
		                            <a href="javascript:;" class="nav-two">
		                                <i class="fa icon <?php echo $_smarty_tpl->tpl_vars['v']->value['icon'];?>
">
		                                    <b class="<?php echo $_smarty_tpl->tpl_vars['v']->value['icon_bg'];?>
"></b>
		                                </i>
		                                <span class="pull-right">
		                                    <i class="fa fa-angle-down text"></i>
		                                    <i class="fa fa-angle-up text-active"></i>
		                                </span>
		                                <span><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</span>
		                            </a>
		                            <ul class="nav lt nav-three">
		                                <?php  $_smarty_tpl->tpl_vars['vv'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['vv']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navigations']->value['nav_three']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['vv']->key => $_smarty_tpl->tpl_vars['vv']->value){
$_smarty_tpl->tpl_vars['vv']->_loop = true;
?>
		                                <?php if ($_smarty_tpl->tpl_vars['vv']->value['parent_id']==$_smarty_tpl->tpl_vars['v']->value['navigation_id']&&($_smarty_tpl->tpl_vars['loggedInUser']->value['groupid']==1||strpos($_smarty_tpl->tpl_vars['allow_navs']->value,$_smarty_tpl->tpl_vars['vv']->value['hid'])!==false)){?>
		                                <li>
		                                    <a href="<?php echo $_smarty_tpl->tpl_vars['vv']->value['link'];?>
" class="load-content" hid="<?php echo $_smarty_tpl->tpl_vars['vv']->value['hid'];?>
">
		                                        <i class="fa fa-angle-right"></i>
		                                        <span><?php echo $_smarty_tpl->tpl_vars['vv']->value['title'];?>
</span>
		                                    </a>
		                                </li>
		                                <?php }?>
		                                <?php } ?>
		                            </ul>
		                        </li>
		                        <?php }?>
		                        <?php } ?>
		                    </ul>
		                </nav>
	                </div>
                </div>
                <!-- / nav -->
            </div>
        </section>

        <footer class="footer lt hidden-xs b-t b-dark">
            <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-dark btn-icon">
                <i class="fa fa-angle-left text"></i>
                <i class="fa fa-angle-right text-active"></i>
            </a>
        </footer>
    </section>
</aside>
<!-- /.aside --><?php }} ?>