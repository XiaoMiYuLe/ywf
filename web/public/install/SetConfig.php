<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-10-26
 * @version SVN: $Id$
 */
class Install_SetConfig
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    /**
     * 排除，不需要执行的模块
     */
    protected static $_ignore_app = array('Index', 'Upload', 'Test');
    
    /**
     * 生成配置文件
     * 
     * @param string $config_env_zend Zend 框架引用路径
     * @param string $config_env_zeed Zeed 框架引用路径
     * @param string $site_name 站点名称
     * @param string $params 其他的一些参数，比如数据库配置
     * @return boolean
     */
    public static function run($config_env_zend, $config_env_zeed, $site_name, $params)
    {
        /* 获取站点域名 */
        $store_url = 'http://' . $_SERVER['SERVER_NAME'];
        
        /* 生成全局配置文件 */
    	$path_config_global = ZEED_ROOT . 'config';
    	$dir_config_global = opendir($path_config_global);
    	self::buildConfig($dir_config_global, $path_config_global);
    	
    	/* 生成模块配置文件 */
    	$path_config_app = ZEED_ROOT . 'application';
    	$dir_config_app = opendir($path_config_app);
    	
    	while (($file = readdir($dir_config_app)) !== false) { // readdir() 返回打开目录句柄中的一个条目
    		$sub_dir = $path_config_app . DIRECTORY_SEPARATOR . $file; // 构建子目录路径
    		if (is_dir($sub_dir) && $file != '.' && $file != '..' && ! in_array($file, self::$_ignore_app)) { // 如果是有效目录，则继续
    			$dir_current = $sub_dir . DIRECTORY_SEPARATOR . 'configs';
    			if (is_dir($dir_current)) {
    				$dir_current_open = opendir($dir_current);
    				self::buildConfig($dir_current_open, $dir_current);
    			}
    		}
    	}
    	
    	/* 改写配置 - 包括站点名称和访问域名等信息 */
    	if ($store_url || $site_name) {
    		$file_urlmapping = $path_config_global . DIRECTORY_SEPARATOR . 'urlmapping.php';
    		
    		$fp = fopen($file_urlmapping, 'r');
    		
    		$urlmapping_new = array();
    		while (! feof($fp)) {
    			$line = trim(fgets($fp));
    			
    			// 替换站点名称
    			if (strpos($line, "config['site_name']") && $site_name) {
    				$line = "\$config['site_name'] = '{$site_name}';";
    			}
    			
    			// 替换站点域名
    			if (strpos($line, "config['store_url']") && $store_url && strlen($store_url) > 10) {
    				$line = "\$config['store_url'] = '{$store_url}'; // 本地地址";
    			}
    			
    			// 替换登录地址
    			if (strpos($line, "config['store_url_login']") && $store_url && strlen($store_url) > 10) {
    				$line = "\$config['store_url_login'] = '{$store_url}'; // 登录地址";
    			}
    			
    			// 替换图片服务器的域名
    			if (strpos($line, "config['upload_cdn']") && $store_url && strlen($store_url) > 10) {
    				$line = "\$config['upload_cdn'] = '{$store_url}'; // 图片服务器的域名";
    			}
    			
    			$urlmapping_new[] = $line;
    		}
    		
    		fclose($fp);
    		$str_urlmapping = implode("\r\n", $urlmapping_new) . "\r\n";
    		file_put_contents($file_urlmapping, $str_urlmapping);
    		
    		/* 生成一些本地文件，比如 upload、template_c 等 */
    		$local_dirs = array(
    				'upload' => ZEED_ROOT . 'upload',
    				'log' => ZEED_ROOT . 'data' . DIRECTORY_SEPARATOR . 'log',
    				'template_c' => ZEED_ROOT . 'data' . DIRECTORY_SEPARATOR . 'template_c',
    				'tmp' => ZEED_ROOT . 'data' . DIRECTORY_SEPARATOR . 'tmp',
    		);
    		foreach ($local_dirs as $k => $v) {
    			if (! is_dir($v)) {
    				mkdir($v, '0777');
    			}
    		}
    	}
    	
    	/* 改写 env 配置文件 */
    	if ($config_env_zend && $config_env_zeed) {
    	    $file_env = $path_config_global . DIRECTORY_SEPARATOR . 'env.php';
    	    $file_env_content = file_get_contents($file_env);
    	    $file_env_content = str_replace('{#framework_path_zend#}', $config_env_zend, $file_env_content);
    	    $file_env_content = str_replace('{#framework_path_zeed#}', $config_env_zeed, $file_env_content);
    	    file_put_contents($file_env, $file_env_content);
    	}
    	
    	/* 改写数据库配置文件 */
    	if (! empty($params)) {
    	    $file_db = $path_config_global . DIRECTORY_SEPARATOR . 'database.php';
    	    $file_db_content = file_get_contents($file_db);
    	    $file_db_content = str_replace('{#db_host#}', $params['db_host'], $file_db_content);
    	    $file_db_content = str_replace('{#db_username#}', $params['db_username'], $file_db_content);
    	    $file_db_content = str_replace('{#db_password#}', $params['db_password'], $file_db_content);
    	    $file_db_content = str_replace('{#db_name#}', $params['db_name'], $file_db_content);
    	    file_put_contents($file_db, $file_db_content);
    	}
    	
        return self::$_res;
    }
    
    /**
     * 生成配置文件
     */
    private static function buildConfig($dir, $path)
    {
    	while (($file = readdir($dir)) !== false) { // readdir() 返回打开目录句柄中的一个条目
    		$sub_dir = $path . DIRECTORY_SEPARATOR . $file; // 构建子目录路径
    		if ($file == '.' || $file == '..') {
    			continue;
    		} elseif (is_dir($sub_dir)) { // 如果是目录，进行递归
    			self::buildConfig($dir, $path);
    		} else { // 如果是文件，则进行复制
    			self::copyFile($file, $path);
    		}
    	}
    }

    /**
     * 复制文件
     */
    private static function copyFile($file, $path) 
    {
    	/* 判断是否为原生配置文件（即带 .dist 的文件） */
    	if (strpos($file, '.dist') === false) {
    		return true; // 直接返回，不需要复制
    	}
    	
    	/* 生成新文件名 */
    	$file_name_new = substr($file, 0, strpos($file, '.')) . '.php';
    	
    	/* 判断要生成的文件是否已存在 */
    	if (file_exists($path . DIRECTORY_SEPARATOR . $file_name_new) === true) {
    		return true;
    	}
    	
    	/* 复制文件 */
    	copy($path . DIRECTORY_SEPARATOR . $file, $path . DIRECTORY_SEPARATOR . $file_name_new);
    	chmod($path . DIRECTORY_SEPARATOR . $file_name_new, 0777);
    }
}

// End ^ Native EOL ^ UTF-8