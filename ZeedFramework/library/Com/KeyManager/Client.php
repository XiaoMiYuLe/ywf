<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-5-11
 * @version    SVN: $Id$
 */

/**
 * Key manager 客户端（即PROVIDER）
 *
 */
class Com_KeyManager_Client
{
    /**
     * 调用本类的应用名称
     * @var string
     */
    protected $_app = 'default';
    
    /**
     * Constructor
     * @param string $app
     */
    public function __construct($app = 'default')
    {
        if (strlen($app) > 0) {
            $this->_app = $app;
        }
    }
    /**
     * 获取指定KEY的SECRET
     * @param string $key
     * @throws Exception
     */
    public function getKeySecret($key)
    {
        $filename = ZEED_PATH_DATA . 'cache/kmclient.' . $this->_app . '.keys.php';
        if (! file_exists($filename)) {
            self::updateCache();
        }
        if (! file_exists($filename)) {
            throw new Exception('kmclient error', - 1);
        }
        
        $keys = @include $filename;
        if (is_array($keys) && isset($keys[$key])) {
            return $keys[$key];
        }
        return false;
    }
    
    /**
     * 获取指定KEY拥有的所有权限
     * @param string $key
     * @throws Exception
     */
    public function getKeyPermissions($key)
    {
        $filename = ZEED_PATH_DATA . 'cache/kmclient.' . $this->_app . '.permissions.php';
        if (! file_exists($filename)) {
            self::updateCache();
        }
        if (! file_exists($filename)) {
            throw new Exception('kmclient error', - 1);
        }
        
        $permissions = @include $filename;
        if (is_array($permissions) && isset($permissions[$key])) {
            return $permissions[$key];
        }
        return array();
    }
    
    /**
     * 更新PROVIDER拥有的KEY及权限本地缓存
     * @throws Exception
     */
    public function updateCache()
    {
        $config = Zeed_Config::loadGroup('kmclient');
        $consumer = new Zeed_OAuth_Consumer($config['key'], $config['secret']);
        $hmac_method = new Zeed_OAuth_Signature_HMACSHA1();
        $api_url = $config['kmapi'];
        $token = null;
        $parameters = array();
        $req = Zeed_OAuth_Request::fromConsumerAndToken($consumer, $token, "GET", $api_url, $parameters);
        $req->signRequest($hmac_method, $consumer, $token);
        
        $response = $req->request();
        
        if ($response === false || $response['code'] != 200) {
            throw new Exception('kmclient encountered error while reading permissions', - 1);
        }
        $rs = json_decode($response['body'], true);
        if ($rs['status'] != 0) {
            throw new Exception('kmclient error: ' . $rs['error'], - 1);
        }
        
        $filename = ZEED_PATH_DATA . 'cache/kmclient.' . $this->_app . '.keys.php';
        $data = "<?php\n\$data=" . var_export($rs['data']['keys'], true) . ";\nreturn \$data;\n";
        file_put_contents($filename, $data);
        $filename = ZEED_PATH_DATA . 'cache/kmclient.' . $this->_app . '.permissions.php';
        $data = "<?php\n\$data=" . var_export($rs['data']['permissions'], true) . ";\nreturn \$data;\n";
        file_put_contents($filename, $data);
        return true;
    }
    
    /**
     * 实例存放
     * @var array
     */
    protected static $_instances;
    
    /**
     * 调用实例
     * @param Com_KeyManager_Client $app
     */
    public static function getInstance($app = null)
    {
        if (is_null($app) || strlen($app) < 1) {
            $app = $config = Zeed_Config::loadGroup('kmclient.app');
        }
        if (isset(self::$_instances[$app])) {
            return self::$_instances[$app];
        }
        return self::$_instances[$app] = new self($app);
    }
}