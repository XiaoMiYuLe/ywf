<?php
/**
 * 定义一些全局变量
 */
define('ZEED_BOOT', dirname(dirname(__FILE__)) . '/');
define('ZEED_ROOT', str_replace('\\', '/', realpath(ZEED_BOOT . '../') . '/'));

/**
 * 定义一些变量
 */
$res = array('status' => 0, 'error' => null, 'data' => null);

/**
 * 接收参数
 */
$step = isset($_GET['step']) ? (int) $_GET['step'] : 1;
$act = isset($_POST['act']) ? $_POST['act'] : ''; // 定义页面跳转的动作。空：首次页面跳转；submit：提交；

$db_host = isset($_POST['db_host']) ? trim(stripslashes($_POST['db_host'])) : 'localhost';
$db_username = isset($_POST['db_username']) ? trim(stripslashes($_POST['db_username'])) : '';
$db_password = isset($_POST['db_password']) ? trim(stripslashes($_POST['db_password'])) : '';
$db_name = isset($_POST['db_name']) ? trim(stripslashes($_POST['db_name'])) : '';
$admin_password = isset($_POST['admin_password']) ? trim(stripslashes($_POST['admin_password'])) : '';
$site_name = isset($_POST['site_name']) ? trim(stripslashes($_POST['site_name'])) : '';
$config_env_zend = isset($_POST['config_env_zend']) ? trim(stripslashes($_POST['config_env_zend'])) : '';
$config_env_zeed = isset($_POST['config_env_zeed']) ? trim(stripslashes($_POST['config_env_zeed'])) : '';

/**
 * 检查系统
 */
if ($step == 2) {
    require_once ZEED_BOOT . 'install/CheckSystem.php';
    
    $result_checksystem = Install_CheckSystem::run();
    $res = array('status' => $result_checksystem['status'], 'data' => $result_checksystem['data']);
} 

/**
 * 数据库设置
 */
elseif ($step == 3) {
    if ($act == 'submit' && (! $db_username || ! $db_name)) {
        $res = array('status' => 1, 'error' => '请填写用户名和数据库名称');
    }
} 

/**
 * 数据初始化
 */
elseif ($step == 4) {
    /* 写入 */
    if ($act == 'submit') {
        require_once ZEED_BOOT . 'install/SetDb.php';
        
        $result_db = Install_SetDb::run($db_username, $db_password, $db_name, $db_host);
        if ($result_db['status'] != 0) {
            $res = array('status' => $result_db['status'], 'error' => '数据初始化失败，请返回上一步确认数据库连接信息是否填写正确');
        }
    }
} 

/**
 * 设置超级管理员密码
 */
elseif ($step == 5) {
    /* 更新后台超级管理员密码 */
    if ($act == 'submit') {
        // 校验密码
        if (! $admin_password || strlen($admin_password) < 6 || strlen($admin_password) > 14) {
            $res = array('status' => 1, 'error' => '密码不能为空，且长度不能小于6位、不能大于14位');
        }
        
        if ($res['status'] == 0) {
            require_once ZEED_BOOT . 'install/SetAdminPassword.php';
            
            $result_setpwd = Install_SetAdminPassword::run($db_username, $db_password, $db_name, $db_host, $admin_password);
            if ($result_setpwd['status'] != 0) {
                $res = array('status' => $result_setpwd['status'], 'error' => '设置超级管理员密码失败，请尝试重新输入一次密码后再次提交');
            }
        }
    }
}

/**
 * 配置文件初始化
 */
elseif ($step == 6) {
    if ($act == 'submit') {
        /* 对参数进行校验 */
        if (! is_dir($config_env_zend) || ! is_dir($config_env_zend . '/library')) {
            $res = array('status' => 1, 'error' => 'Zend 框架引用路径错误');
        }
        if (! is_dir($config_env_zeed) || ! is_dir($config_env_zeed . '/library')) {
            $res = array('status' => 1, 'error' => 'Zeed 框架引用路径错误');
        }
        if (! $config_env_zend || ! $config_env_zeed) {
            $res = array('status' => 1, 'error' => '请填写框架引用路径');
        }
        
        if ($res['status'] == 0) {
            require_once ZEED_BOOT . 'install/SetConfig.php';
            
            $params = array('db_host' => $db_host, 'db_username' => $db_username, 'db_password' => $db_password, 'db_name' => $db_name);
            $result_config = Install_SetConfig::run($config_env_zend, $config_env_zeed, $site_name, $params);
            if ($result_config['status'] != 0) {
                $res = array('status' => $result_config['status'], 'error' => '配置文件初始化失败，请再尝试一次');
            }
        }
    }
}

/**
 * 安装完成，生成 .lock 文件以做标记
 */
elseif ($step == 7) {
    $file_lock = fopen(ZEED_BOOT . 'install/install.lock', 'w');
    fwrite($file_lock, '1');
    fclose($file_lock);
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title>YumZeed - 安装</title>
    <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  
    <link rel="stylesheet" href="/static/scripts/bootstrap/3.1.0/css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="/static/panel/css/animate.css" type="text/css"/>
	<link rel="stylesheet" href="/static/panel/css/font-awesome.min.css" type="text/css"/>
	<link rel="stylesheet" href="/static/panel/css/apps.css" type="text/css"/>
	<link rel="stylesheet" href="/static/scripts/fuelux/fuelux.css" type="text/css"/>
	<link rel="stylesheet" href="/static/panel/css/reset.css" type="text/css"/>
	
	<script src="/static/scripts/jquery/1.11.0/jquery.min.js"></script>
    
    <style>
        .wizard-box {background-color: #f5f5f5;}
        .steps {margin-top: 50px !important;}
        .step-content {min-height: 350px;}
    </style>
    
    <script>
    $(document).ready(function() {
        /**
         * 返回数据库设置
         */
        $('#btn_return_3').click(function(){
            $('#setup_form_3').find('input[name=act]').val('');
            $('#setup_form_3').submit();
        });
    });
    </script>
</head>
<body>
<section class="vbox">
    <!-- 头部 -->
    <header class="bg-success dk header navbar navbar-fixed-top-xs text-center">
		<h3>YumZeed - 安装</h3>
	</header>
	<!-- 头部 @end -->
	
	<!-- 安装流程正文 -->
	<section class="panel panel-default clearfix wizard-box">
	    <!-- 安装步骤提示 -->
        <div id="wizard" class="wizard wizard-vertical clearfix">
            <ul class="steps">
                <li class="<?php if ($step == 1) {echo 'active';} ?>"><span class="badge <?php if ($step == 1) {echo 'badge-info';} ?>">1</span>欢迎使用！</li>
                <li class="<?php if ($step == 2) {echo 'active';} ?>"><span class="badge <?php if ($step == 2) {echo 'badge-info';} ?>">2</span>检查系统</li>
                <li class="<?php if ($step == 3) {echo 'active';} ?>"><span class="badge <?php if ($step == 3) {echo 'badge-info';} ?>">3</span>数据库设置</li>
                <li class="<?php if ($step == 4) {echo 'active';} ?>"><span class="badge <?php if ($step == 4) {echo 'badge-info';} ?>">4</span>数据初始化</li>
                <li class="<?php if ($step == 5) {echo 'active';} ?>"><span class="badge <?php if ($step == 5) {echo 'badge-info';} ?>">5</span>超级管理员</li>
                <li class="<?php if ($step == 6) {echo 'active';} ?>"><span class="badge <?php if ($step == 6) {echo 'badge-info';} ?>">6</span>配置初始化</li>
                <li class="<?php if ($step == 7) {echo 'active';} ?>"><span class="badge <?php if ($step == 7) {echo 'badge-info';} ?>">7</span>安装完成</li>
            </ul>
        </div>
        <!-- 安装步骤提示 @end -->
        
        <!-- 安装步骤正文 -->
        <div class="step-content bg-white padder-t-lg">
            <div id="step1" class="step-pane <?php if ($step == 1) {echo 'active';} ?>">
                <section class="comment-body panel">
                    <header class="panel-heading bg-white b-b">
                        <span class="h3">欢迎使用！</span>
                        <span class="text-muted m-l-sm pull-right">
                            <a href="./?step=2" class="btn btn-primary btn-s-sm">下一步</a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <p>欢迎使用 YumZeed。在开始前，我们需要您数据库的一些信息。请准备好如下信息。</p>
                        <ol>
                        	<li>数据库名</li>
                        	<li>数据库用户名</li>
                        	<li>数据库密码</li>
                        	<li>数据库主机</li>
                        	<li>后台超级管理员密码</li>
                        	<li>站点名称</li>
                        	<li>底层框架：ZendFramework-1.11.3</li>
                        	<li>底层框架：ZeedFramework。请保证该框架版本与自己本地开发环境的 PHP 版本保持一致</li>
                        </ol>
                        <p><strong>如果出于任何原因，文件自动创建失败，请不要担心。这个向导的目的只是代您编辑配置文件，填入数据库信息。
                            您可以直接到目录 <code>config</code> 和 <code>application/（各模块）/configs</code> 中将配置文件 
                            <code>***.dist.php</code> 另存为 <code>***.php</code>，然后再根据需要编辑配置文件 <code>***.php</code> 中的内容
                        </strong></p>
                        <p>准备好了的话，Let's go&hellip;</p>
                    </div>
                </section>
            </div>
            
            <div id="step2" class="step-pane <?php if ($step == 2) {echo 'active';} ?>"">
                <section class="comment-body panel">
                    <header class="panel-heading bg-white b-b">
                        <span class="h3">检查系统</span>
                        <?php if ($res['status'] == 0) { ?>
                        <span class="text-muted m-l-sm pull-right">
                            <a href="./?step=3" class="btn btn-primary btn-s-sm">下一步</a>
                        </span>
                        <?php } else { ?>
                        <span class="red">请确保以下条件全部满足后，再刷新试试</span>
                        <?php } ?>
                    </header>
                    <div class="panel-body">
                        <section class="panel panel-default">
                            <table class="table table-striped m-b-none text-nm">
                                <thead class="text-lg">
                                    <tr>
                                        <th>检查项</th>
                                        <th>参考值</th>                    
                                        <th width="70">状态</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $res['data']; ?>
                                </tbody>
                            </table>
                        </section>
                        
                    </div>
                </section>
            </div>
            
            <form class="form-horizontal" id="setup_form_3" action="./?step=3" method="post">
                <div id="step3" class="step-pane <?php if ($step == 3) {echo 'active';} ?>"">
                    <section class="comment-body panel">
                        <header class="panel-heading bg-white b-b">
                            <span class="h3">数据库设置</span>
                            <?php if ($res['status'] == 1) { ?>
                            <span class="red"><?php echo $res['error']; ?></span>
                            <?php } ?>
                            <span class="text-muted m-l-sm pull-right">
                                <button type="submit" class="btn btn-primary btn-s-sm">下一步</button>
                                <input type="hidden" name="act" value="submit" />
                            </span>
                        </header>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="db_host" class="col-sm-2 control-label">数据库主机</label>
                                <div class="col-sm-4">
                                    <input type="text" name="db_host" class="form-control" id="db_host" placeholder="请输入数据库主机" value="<?php echo $db_host; ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="db_username" class="col-sm-2 control-label">用户名</label>
                                <div class="col-sm-4">
                                    <input type="text" name="db_username" class="form-control" id="db_username" placeholder="请输入用户名" value="<?php echo $db_username; ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="db_password" class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-4">
                                    <input type="text" name="db_password" class="form-control" id="db_password" placeholder="请输入密码" value="<?php echo $db_password; ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="db_name" class="col-sm-2 control-label">数据库名称</label>
                                <div class="col-sm-4">
                                    <input type="text" name="db_name" class="form-control" id="db_name" placeholder="请输入数据库名称" value="<?php echo $db_name; ?>" />
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </form>
            
            <!-- 数据库设置提交后的处理 -->
            <?php if ($step == 3 && $act == 'submit' && $res['status'] == 0) { ?>
            <form id="setup_form_3_submit" action="./?step=4" method="post">
                <input type="hidden" name="db_host" value="<?php echo $db_host; ?>" />
                <input type="hidden" name="db_username" value="<?php echo $db_username; ?>" />
                <input type="hidden" name="db_password" value="<?php echo $db_password; ?>" />
                <input type="hidden" name="db_name" value="<?php echo $db_name; ?>" />
                <input type="hidden" name="act" value="submit" />
            </form>
            
            <script>
            $('#setup_form_3_submit').submit();
            </script>
            <?php } ?>
            <!-- 数据库设置提交后的处理 @end -->
            
            <form class="form-horizontal" id="setup_form_4" action="./?step=5" method="post">
                <div id="step4" class="step-pane <?php if ($step == 4) {echo 'active';} ?>"">
                    <section class="comment-body panel">
                        <header class="panel-heading bg-white b-b">
                            <span class="h3">数据初始化</span>
                            <span class="text-muted m-l-sm pull-right">
                                <?php if ($res['status'] == 1) { ?>
                                <button id="btn_return_3" class="btn btn-danger btn-s-sm">上一步</button>
                                <?php } else { ?>
                                <button type="submit" class="btn btn-primary btn-s-sm">下一步</button>
                                <input type="hidden" name="db_host" value="<?php echo $db_host; ?>" />
                                <input type="hidden" name="db_username" value="<?php echo $db_username; ?>" />
                                <input type="hidden" name="db_password" value="<?php echo $db_password; ?>" />
                                <input type="hidden" name="db_name" value="<?php echo $db_name; ?>" />
                                <?php } ?>
                            </span>
                        </header>
                        <div class="panel-body">
                            <?php if ($res['status'] == 1) { ?>
                            <p class="text-nm red"><?php echo $res['error']; ?></p>
                            <?php } else { ?>
                            <p class="text-nm">数据初始化成功</p>
                            <?php } ?>
                        </div>
                    </section>
                </div>
            </form>
            
            <form class="form-horizontal" id="setup_form_5" action="./?step=5" method="post">
                <div id="step5" class="step-pane <?php if ($step == 5) {echo 'active';} ?>"">
                    <section class="comment-body panel">
                        <header class="panel-heading bg-white b-b">
                            <span class="h3">超级管理员</span>
                            <?php if ($res['status'] == 1) { ?>
                            <span class="red"><?php echo $res['error']; ?></span>
                            <?php } ?>
                            <span class="text-muted m-l-sm pull-right">
                                <button type="submit" class="btn btn-primary btn-s-sm">下一步</button>
                                <input type="hidden" name="db_host" value="<?php echo $db_host; ?>" />
                                <input type="hidden" name="db_username" value="<?php echo $db_username; ?>" />
                                <input type="hidden" name="db_password" value="<?php echo $db_password; ?>" />
                                <input type="hidden" name="db_name" value="<?php echo $db_name; ?>" />
                                <input type="hidden" name="act" value="submit" />
                            </span>
                        </header>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="username" class="col-sm-2 control-label">超级管理员帐号</label>
                                <div class="col-sm-4">
                                    <p class="form-control-static">admin</p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="admin_password" class="col-sm-2 control-label">超级管理员密码</label>
                                <div class="col-sm-4">
                                    <input type="text" name="admin_password" class="form-control" id="admin_password" placeholder="请输入超级管理员密码" value="<?php echo $admin_password; ?>" />
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </form>
            
            <!-- 超级管理员提交后的处理 -->
            <?php if ($step == 5 && $act == 'submit' && $res['status'] == 0) { ?>
            <form id="setup_form_5_submit" action="./?step=6" method="post">
                <input type="hidden" name="db_host" value="<?php echo $db_host; ?>" />
                <input type="hidden" name="db_username" value="<?php echo $db_username; ?>" />
                <input type="hidden" name="db_password" value="<?php echo $db_password; ?>" />
                <input type="hidden" name="db_name" value="<?php echo $db_name; ?>" />
            </form>
            
            <script>
            $('#setup_form_5_submit').submit();
            </script>
            <?php } ?>
            <!-- 超级管理员提交后的处理 @end -->
            
            <form class="form-horizontal" id="setup_form_6" action="./?step=6" method="post">
                <div id="step6" class="step-pane <?php if ($step == 6) {echo 'active';} ?>"">
                    <section class="comment-body panel">
                        <header class="panel-heading bg-white b-b">
                            <span class="h3">配置初始化</span>
                            <?php if ($res['status'] == 1) { ?>
                            <span class="red"><?php echo $res['error']; ?></span>
                            <?php } ?>
                            <span class="text-muted m-l-sm pull-right">
                                <button type="submit" class="btn btn-primary btn-s-sm">下一步</button>
                                <input type="hidden" name="db_host" value="<?php echo $db_host; ?>" />
                                <input type="hidden" name="db_username" value="<?php echo $db_username; ?>" />
                                <input type="hidden" name="db_password" value="<?php echo $db_password; ?>" />
                                <input type="hidden" name="db_name" value="<?php echo $db_name; ?>" />
                                <input type="hidden" name="act" value="submit" />
                            </span>
                        </header>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="site_name" class="col-sm-2 control-label">站点名称（选填）</label>
                                <div class="col-sm-6">
                                    <input type="text" name="site_name" class="form-control" id="site_name" placeholder="请输入站点名称" value="<?php echo $site_name; ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="config_env_zend" class="col-sm-2 control-label">Zend 框架引用路径</label>
                                <div class="col-sm-6">
                                    <input type="text" name="config_env_zend" class="form-control" id="config_env_zend" placeholder="请输入 Zend 框架引用路径" value="<?php echo $config_env_zend; ?>" />
                                    <p class="help-block m-b-none">windows 环境下的框架引用路径形如（注意目录分隔符，即斜线）：D:/work/workspaces/ZendFramework-1.11.3/</p>
                                    <p class="help-block m-b-none">linux 环境下的框架引用路径形如：/Users/username/work/workspaces/ZendFramework-1.11.3/</p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="config_env_zeed" class="col-sm-2 control-label">Zeed 框架引用路径</label>
                                <div class="col-sm-6">
                                    <input type="text" name="config_env_zeed" class="form-control" id="config_env_zeed" placeholder="请输入 Zeed 框架引用路径" value="<?php echo $config_env_zeed; ?>" />
                                    <p class="help-block m-b-none">windows 环境下的框架引用路径形如（注意目录分隔符，即斜线）：D:/work/workspaces/ZeedFramework/</p>
                                    <p class="help-block m-b-none">linux 环境下的框架引用路径形如：/Users/username/work/workspaces/ZeedFramework/</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </form>
            
            <!-- 配置初始化提交后的处理 -->
            <?php if ($step == 6 && $act == 'submit' && $res['status'] == 0) { ?>
            <form id="setup_form_6_submit" action="./?step=7" method="post">
            </form>
            
            <script>
            $('#setup_form_6_submit').submit();
            </script>
            <?php } ?>
            <!-- 配置初始化提交后的处理 @end -->
            
            <div id="step7" class="step-pane <?php if ($step == 7) {echo 'active';} ?>"">
                <section class="comment-body panel">
                    <header class="panel-heading bg-white b-b">
                        <span class="h3">安装完成</span>
                        <span class="text-muted m-l-sm pull-right">
                            <a href="/admin" class="btn btn-primary btn-s-sm">完成</a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <p>安装完成，尽情享受吧</p>
                    </div>
                </section>
            </div>
            
            <div class="actions m-t text-right hide">
                <button disabled="disabled" data-wizard="previous" data-target="#wizard" data-required="true" class="btn btn-danger btn-s-sm btn-prev hide" type="button">Prev</button>
                <button data-last="完成" data-wizard="next" data-target="#wizard" data-required="true" class="btn btn-primary btn-s-sm btn-next" type="button">下一步</button>
            </div>
        </div>
        <!-- 安装步骤正文 @end -->
    </section>
    <!-- 安装流程正文 @end -->
</section>
</body>
</html>