<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-3-21
 * @version    SVN: $Id$
 */
class CacheController extends AdminAbstract
{
    public $perpage = 20;

    /**
     * 缓存管理
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $data = array();

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'cache.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 单项清理
     */
    public function cleanSingle()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请求方式错误');
            return self::RS_SUCCESS;
        }
    
        $target = $this->input->query('target', null);
    	if (! $target) {
    		$this->setStatus(1);
    		$this->setError('处理目标不明确，小 Z 迷糊中...');
    		return self::RS_SUCCESS;
    	}
        
    	switch ($target) {
    		case 'router': $this->_cleanRouter(); break;
    		case 'smarty': $this->_cleanSmarty(); break;
    		case 'file': $this->_cleanFile(); break;
    		case 'cookie': $this->_cleanCookie(); break;
    		case 'session': $this->_cleanSession(); break;
    		case 'redis': $this->_cleanRedis(); break;
    		case 'memcache': $this->_cleanMemcache(); break;
    		default: $this->_cleanRouter(); break;
    	}
    
        return self::RS_SUCCESS;
    }
    
    /**
     * 全部清理
     */
    public function cleanAll()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请求方式错误');
            return self::RS_SUCCESS;
        }
        
    	$this->_cleanRouter();
    	$this->_cleanSmarty();
    	$this->_cleanFile();
    	$this->_cleanCookie();
    	$this->_cleanSession();
    	$this->_cleanRedis();
    	$this->_cleanMemcache();
    
        return self::RS_SUCCESS;
    }

    /**
     * 单项清理 - 清理路由缓存
     */
    private function _cleanRouter()
    {
        $targetFile = ZEED_PATH_DATA . 'cache/__CLASSINDEXING.php';
        if (! file_exists($targetFile)) {
        	$this->setStatus(1);
        	$this->setError('路由缓存文件尚未建立，无需清理。省力气啦...');
        	return false;
        }
        
        if (! is_writable($targetFile)) {
        	$this->setStatus(1);
        	$this->setError('悲催的，居然没有权限...');
        	return false;
        }
        
        file_put_contents($targetFile, '');
        
        return true;
    }
    
    /**
     * 单项清理 - 清理 Smarty 缓存
     */
    private function _cleanSmarty()
    {
    	$targetDir = ZEED_PATH_DATA . 'template_c';
    	
    	$dir = opendir($targetDir);
    	while (($file = readdir($dir)) !== false) {
    		if ($file == '.' || $file == '..') {
    			continue;
    		} else {
    			@unlink($targetDir . '/' . $file);
    		}
    	}
    	closedir($dir);
    	
    	return true;
    }
    
    /**
     * 单项清理 - 清理文件缓存
     */
    private function _cleanFile()
    {
    	$targetDir = ZEED_PATH_DATA . 'tmp';
    	 
    	$dir = opendir($targetDir);
    	while (($file = readdir($dir)) !== false) {
    		if ($file == '.' || $file == '..') {
    			continue;
    		} else {
    			@unlink($targetDir . '/' . $file);
    		}
    	}
    	closedir($dir);
    	 
    	return true;
    }
    
    /**
     * 单项清理 - 清理 Cookie
     */
    private function _cleanCookie()
    {
    	$ignore = array('PHPSESSID');
    	$c = $_COOKIE;
    	if (! empty($c)) {
    		foreach ($c as $k => $v) {
    			if (in_array($k, $ignore)) {
    				continue;
    			}
    			setcookie($k, null, time() - 1, '/');
    		}
    	}
    	
    	return true;
    }
    
    /**
     * 单项清理 - 清理 Session
     */
    private function _cleanSession()
    {
    	Zeed_Session::destroy(true);
    	return true;
    }
    
    /**
     * 单项清理 - 清理 Redis
     */
    private function _cleanRedis()
    {
    	if (extension_loaded('redis')) {
    		Trend_Model_Redis::instance()->flushDB();
    	}
    	return true;
    }
    
    /**
     * 单项清理 - 清理 Memcache/Memcached
     */
    private function _cleanMemcache()
    {
    	if (extension_loaded('memcached')) {
    		Zeed_Session_Storage_Memcached::destroy();
    	} elseif (extension_loaded('memcache')) {
    		Zeed_Session_Storage_Memcache::destroy();
    	}
    	
    	return true;
    }
}

// End ^ Native EOL ^ UTF-8