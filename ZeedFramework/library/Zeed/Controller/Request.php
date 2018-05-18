<?php
/**
 * Playcool Project
 *
 * LICENSE
 *
 * http://www.playcool.com/license/ice
 *
 * @category ICE
 * @package ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author xSharp ( GTalk: xSharp@gmail.com )
 * @since Jun 4, 2009
 * @version SVN: $Id: Request.php 12885 2012-05-04 03:39:09Z xsharp $
 */

class Zeed_Controller_Request
{
    protected $_module;
    protected $_controller;
    protected $_action;
    
    protected $_moduleKey = 'module';
    protected $_controllerKey = 'controller';
    protected $_actionKey = 'action';
    
    protected $_params = array();
    protected $_dispatched;
    
    /**
     *
     * @param string $module
     * @return Zeed_Controller_Request
     */
    public function setModuleName($module)
    {
        $this->_module = $module;
        return $this;
    }
    
    public function setControllerName($controller)
    {
        $this->_controller = $controller;
        return $this;
    }
    
    /**
     *
     * @param string $action
     * @return Zeed_Controller_Request
     */
    public function setActionName($action)
    {
        $this->_action = $action;
        return $this;
    }
    
    public function setParam($key, $value)
    {
        $key = (string) $key;
        
        if ((null === $value) && isset($this->_params[$key])) {
            unset($this->_params[$key]);
        } elseif (null !== $value) {
            $this->_params[$key] = $value;
        }
        
        return $this;
    }
    
    public function getParams()
    {
        return $this->_params;
    }
    
    public function setDispatched($flag = true)
    {
        $this->_dispatched = $flag ? true : false;
        return $this;
    }
    
    public function getModuleName()
    {
        return $this->_module;
    }
    
    public function getActionName()
    {
        return $this->_action;
    }
    
    public function getControllerName()
    {
        return $this->_controller;
    }
    
    public function getPathInfo()
    {
        return $this->baseUri();
    }
    
    public function getModuleKey()
    {
        return $this->_moduleKey;
    }
    
    public function getControllerKey()
    {
        return $this->_controllerKey;
    }
    
    public function getActionKey()
    {
        return $this->_actionKey;
    }
    
    /**
     * 下面是从原 Zeed_Request_Http 中移植过来的
     */
    
    /**
     * 当前请求URL
     *
     * @var string
     */
    private $_request_uri;
    
    /**
     * 当前请求URL不包含查询参数的部分
     *
     * @var string
     */
    private $_base_uri;
    
    /**
     * 当前请求URL的目录部分
     *
     * @var string
     */
    private $_base_dir;
    
    /**
     * 当前请求的PATHINFO信息
     *
     * @var string
     */
    private $_pathinfo;
    
    private static $_path_parsed_params;
    
    /**
     * 请求协议
     *
     * @var string
     */
    private $_protocol;
    
    /**
     * 当前请求的端口号
     *
     * @var string
     */
    private $_server_port;
    
    private $_rewrite_base;
    
    public static $token_name = 'ZRH.Tokename';
    
    /**
     * 访问请求参数
     * 查找请求参数的顺行是 Zeed_Request_Http对象附加参数、$_GET 和 $_POST。
     *
     * @param string $parameter 要访问的请求参数
     * @param mixed $default 参数不存在时要返回的默认值
     *       
     * @return mixed 参数值
     */
    public static function query($parameter, $default = null)
    {
        if (isset($_GET[$parameter]))
            return $_GET[$parameter];
        elseif (isset($_POST[$parameter]))
            return $_POST[$parameter];
        elseif (isset(self::$_path_parsed_params[$parameter])) {
            return self::$_path_parsed_params[$parameter];
        }
        
        return $default;
    }
    
    /**
     * 获得GET数据
     * 从 $_GET中获得指定参数，如果参数不存在则返回 $default指定的默认值。
     * 如果 $parameter参数为 null，则返回整个$_GET的内容。
     *
     * @param string $parameter 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     * @return mixed 参数值
     */
    public static function get($parameter = null, $default = null)
    {
        if (is_null($parameter))
            return $_GET;
        if (isset($_GET[$parameter])) {
            return $_GET[$parameter];
        } elseif (isset(self::$_path_parsed_params[$parameter])) {
            return self::$_path_parsed_params[$parameter];
        }
        
        return $default;
    }
    
    /**
     * 从多个可能的参数中获取参数值，按顺序检查。 $parameterMapping = arrat('cid', 'categoryid')
     *
     * @param array $parameterMapping 参数映射
     * @return mixed
     */
    public static function smartQuery($parameterMapping)
    {
        if (! is_array($parameterMapping)) {
            return null;
        }
        
        $return = null;
        foreach ($parameterMapping as $key) {
            if (! is_null($return = self::query($key, null))) {
                return $return;
            }
        }
        
        return null;
    }
    
    /**
     * 自动处理批量ID参数, 比如:
     * contentid=1, contentid[]=1&contentid[]=2, contentid=1,2
     *
     * @param string $parameter 请求参数
     * @param string $spliter 分割符
     * @return array 统一返回数组
     */
    public static function smartIds($idParameter, $spliter = ',')
    {
        $id = self::query($idParameter);
        
        $return = array();
        if (is_array($id)) {
            $return = $id;
        } elseif (is_numeric($id)) {
            $return[] = $id;
        } elseif (is_string($id) && strpos($id, $spliter)) {
            $return = explode($spliter, $id);
        }
        
        return $return;
    }
    
    /**
     *
     * @param array $params
     */
    public static function setParsedParams($params)
    {
        self::$_path_parsed_params = $params;
    }
    
    /**
     * 获得POST数据
     * 从 $_POST中获得指定参数，如果参数不存在则返回 $default指定的默认值。
     *
     * 如果$parameter参数为 null，则返回整个$_POST的内容。
     *
     * @param string $parameter 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     * @return mixed 参数值
     */
    public static function post($parameter = null, $default = null)
    {
        if (is_null($parameter))
            return $_POST;
        return isset($_POST[$parameter]) ? $_POST[$parameter] : $default;
    }
    
    /**
     * 获得Cookie数据
     * 从 $_COOKIE中获得指定参数，如果参数不存在则返回 $default指定的默认值。
     *
     * 如果$parameter参数为 null，则返回整个$_COOKIE的内容。
     *
     * @param string $parameter 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     * @return mixed 参数值
     */
    public static function cookie($parameter, $default = null)
    {
        if (is_null($parameter))
            return $_COOKIE;
        return isset($_COOKIE[$parameter]) ? $_COOKIE[$parameter] : $default;
    }
    
    /**
     * 取得当前请求使用的协议
     * 返回值不包含协议的版本。常见的返回值是 HTTP。
     *
     * @return string 当前请求使用的协议
     */
    public function protocol()
    {
        if (is_null($this->_protocol)) {
            list($this->_protocol) = explode('/', $_SERVER['SERVER_PROTOCOL']);
        }
        return $this->_protocol;
    }
    
    /**
     * 设置 REQUEST_URI
     * 修改后 requestUri()将返回新值，同时还影响 baseUri()和 baseDir()的返回结果。
     *
     * @param string $request_uri 新的 REQUEST_URI 值
     * @return Zeed_Request_Http
     */
    public function changeRequestUri($request_uri)
    {
        $this->_request_uri = $request_uri;
        $this->_base_uri = $this->_base_dir = $this->_pathinfo = null;
        return $this;
    }
    
    /**
     * 确定请求的完整 URL
     *
     * 几个示例：
     * <ul>
     * <li>请求 http://www.example.com/index.php?controller=posts&action=create</li>
     * <li>返回 /index.php?controller=posts&action=create</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/news/index.php?controller=posts&action=create</li>
     * <li>返回 /news/index.php?controller=posts&action=create</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/index.php/posts/create</li>
     * <li>返回 /index.php/posts/create</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/news/show/id/1</li>
     * <li>返回 /news/show/id/1</li>
     * </ul>
     *
     * @return string 请求的完整 URL
     */
    public function requestUri()
    {
        if (! is_null($this->_request_uri))
            return $this->_request_uri;
        
        if (isset($_SERVER['REDIRECT_QUERY_STRING'])) { // SSI, MEDIATEMPLE
            $uri = $_SERVER['REDIRECT_URL'] . '?' . $_SERVER['REDIRECT_QUERY_STRING'];
        } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
            $uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['HTTP_X_REWRITE_URL']; // IIS
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
            $uri = $_SERVER['ORIG_PATH_INFO'];
            if (! empty($_SERVER['QUERY_STRING'])) {
                $uri .= '?' . $_SERVER['QUERY_STRING'];
            }
        } else {
            $uri = '';
        }
        
        $this->_request_uri = $uri;
        return $uri;
    }
    
    /**
     * 返回不包含任何查询参数的 URI（但包含脚本名称）
     * 几个示例：
     *
     * <ul>
     * <li>请求 http://www.example.com/index.php?controller=posts&action=create</li>
     * <li>返回 /index.php</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/news/index.php?controller=posts&action=create</li>
     * <li>返回 /news/index.php</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/index.php/posts/create</li>
     * <li>返回 /index.php</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/news/show/id/1</li>
     * <li>返回 /news/show/id/1</li>
     * <li>假设使用了 URL 重写，并且 index.php 位于根目录</li>
     * </ul>
     *
     * 此方法参考Zend Framework实现。
     *
     * @return string 请求URL中不包含查询参数的部分
     */
    public function baseUri($requestUri = null)
    {
        if (is_null($this->_base_uri)) {
            if (! $requestUri) {
                $requestUri = $this->requestUri();
            }
            
            if (strstr($requestUri, '?')) {
                $requestUri = substr($requestUri, 0, strpos($requestUri, '?'));
            }
            $requestUri = preg_replace('#/+#', '/', $requestUri);
            
            /**
             * if (strpos($requestUri, $this->_rewrite_base) === 0) {
             * $requestUri = substr($requestUri, strlen($this->_rewrite_base));
             * }
             */
            
            $this->_base_uri = preg_match('#^/#', $requestUri) ? $requestUri : '/' . $requestUri;
        }
        
        return $this->_base_uri;
    }
    
    /**
     * 解析URL中不包含参数部分
     *
     * @param string $requestUri
     * @return array
     */
    public function parseBaseUri($requestUri = null)
    {
        if (is_null($requestUri)) {
            $requestUri = $this->baseUri();
        }
        if (strstr($requestUri, '/')) {
        }
    }
    
    /**
     * 返回请求 URL中的基础路径（不包含脚本名称）
     *
     * 几个示例：
     *
     * <ul>
     * <li>请求 http://www.example.com/index.php?controller=posts&action=create</li>
     * <li>返回 /</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/news/index.php?controller=posts&action=create</li>
     * <li>返回 /news/</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/index.php/posts/create</li>
     * <li>返回 /</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/news/show/id/1</li>
     * <li>返回 /</li>
     * </ul>
     *
     * @return string 请求 URL中的基础路径
     */
    public function baseDir()
    {
        if (! is_null($this->_base_dir))
            return $this->_base_dir;
        
        $base_uri = $this->baseUri();
        if (substr($base_uri, - 1, 1) == '/') {
            $base_dir = $base_uri;
        } else {
            $base_dir = dirname($base_uri);
        }
        
        $this->_base_dir = rtrim($base_dir, '/\\') . '/';
        return $this->_base_dir;
    }
    
    /**
     * 返回服务器响应请求使用的端口
     *
     * 通常服务器使用 80端口与客户端通信，该方法可以获得服务器所使用的端口号。
     *
     * @return string 服务器响应请求使用的端口
     */
    public function serverPort()
    {
        if (! is_null($this->_server_port))
            return $this->_server_port;
        
        if (isset($_SERVER['SERVER_PORT'])) {
            $server_port = intval($_SERVER['SERVER_PORT']);
        } else {
            $server_port = 80;
        }
        
        if (isset($_SERVER['HTTP_HOST'])) {
            $arr = explode(':', $_SERVER['HTTP_HOST']);
            $count = count($arr);
            if ($count > 1) {
                $port = intval($arr[$count - 1]);
                if ($port != $server_port) {
                    $server_port = $port;
                }
            }
        }
        
        $this->_server_port = $server_port;
        return $server_port;
    }
    
    /**
     * 返回 PATHINFO信息
     *
     * <ul>
     * <li>请求 http://www.example.com/index.php?controller=posts&action=create</li>
     * <li>返回 /</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/news/index.php?controller=posts&action=create</li>
     * <li>返回 /</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/index.php/posts/create</li>
     * <li>返回 /</li>
     * </ul>
     * <ul>
     * <li>请求 http://www.example.com/news/show/id/1</li>
     * <li>返回 /news/show/id/1</li>
     * <li>假设使用了 URL 重写，并且 index.php 位于根目录</li>
     * </ul>
     *
     * 此方法参考Zend Framework实现。
     *
     * @return string
     */
    public function pathinfo()
    {
        if (! is_null($this->_pathinfo))
            return $this->_pathinfo;
        
        if (! empty($_SERVER['PATH_INFO'])) {
            $this->_pathinfo = $_SERVER['PATH_INFO'];
            return $this->_pathinfo;
        }
        
        $base_url = $this->baseUri();
        
        if (null === ($request_uri = $this->requestUri())) {
            $this->_pathinfo = '';
            return '';
        }
        
        // Remove the query string from REQUEST_URI
        if (false !== ($pos = strpos($request_uri, '?'))) {
            $request_uri = substr($request_uri, 0, $pos);
        }
        
        if ((null !== $base_url) && (false === ($pathinfo = substr($request_uri, strlen($base_url))))) {
            // If substr() returns false then PATH_INFO is set to an empty string
            $pathinfo = '';
        } elseif (null === $base_url) {
            $pathinfo = $request_uri;
        }
        
        $this->_pathinfo = $pathinfo;
        return $pathinfo;
    }
    
    /**
     * 返回请求使用的方法
     *
     * @return string
     */
    public function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * 是否是 GET请求
     *
     * @return boolean
     */
    public function isGET()
    {
        return $this->requestMethod() == 'GET';
    }
    
    /**
     * 是否是 POST请求
     *
     * @return boolean
     */
    public function isPOST()
    {
        return $this->requestMethod() == 'POST';
    }
    
    /**
     * 是否是 PUT请求
     *
     * @return boolean
     */
    public function isPUT()
    {
        return $this->requestMethod() == 'PUT';
    }
    
    /**
     * 是否是 DELETE 请求
     *
     * @return boolean
     */
    public function isDELETE()
    {
        return $this->requestMethod() == 'DELETE';
    }
    
    /**
     * 是否是 HEAD请求
     *
     * @return boolean
     */
    public function isHEAD()
    {
        return $this->requestMethod() == 'HEAD';
    }
    
    /**
     * 是否是 OPTIONS 请求
     *
     * @return boolean
     */
    public function isOPTIONS()
    {
        return $this->requestMethod() == 'OPTIONS';
    }
    
    /**
     * 判断 HTTP请求是否是通过 XMLHttp 发起的
     *
     * @return boolean
     */
    public function isAJAX()
    {
        return strtolower($this->header('X_REQUESTED_WITH')) == 'xmlhttprequest';
    }
    
    /**
     * 判断 HTTP请求是否是通过 Flash 发起的
     *
     * @return boolean
     */
    public function isFlash()
    {
        return strtolower($this->header('USER_AGENT')) == 'shockwave flash';
    }
    
    /**
     * 返回请求的原始内容
     *
     * @return string
     */
    public function requestRawBody()
    {
        $body = file_get_contents('php://input');
        return (strlen(trim($body)) > 0) ? $body : false;
    }
    
    /**
     * 返回 HTTP请求头中的指定信息，如果没有指定参数则返回 false
     *
     * @param string $header 要查询的请求头参数
     * @return string 参数值
     */
    public function header($header)
    {
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (! empty($_SERVER[$temp]))
            return $_SERVER[$temp];
        
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (! empty($headers[$header]))
                return $headers[$header];
        }
        
        return false;
    }
    
    /**
     * Generate and store a unique token which can be used to help prevent
     * [CSRF](http://wikipedia.org/wiki/Cross_Site_Request_Forgery) attacks.
     *
     * 获取一个 Token，通常把该值写入到HTML FROM 中，用于下次传递验证
     * <code>
     * $token = $this->input->token();
     * </code>
     *
     * This provides a basic, but effective, method of preventing CSRF attacks.
     *
     * @param boolean force a new token to be generated?
     * @return string
     * @uses Session::instance
     *      
     * @param $new
     */
    public function token($new = false)
    {
        if (true === $new || ! isset($_SESSION[self::$token_name]) || null === $_SESSION[self::$token_name]) {
            $_SESSION[self::$token_name] = base64_encode(Zeed_Encrypt::generateSalt(8));
        }
        
        $token = $_SESSION[self::$token_name];
        return $token;
    }
    
    /**
     * 检查 Token
     *
     * @see self::token()
     * @param string $token
     * @return boolean
     */
    public function checkToken($token, $forceInvaild = false)
    {
        $result = false;
        $token = base64_encode(base64_decode($token));
        
        if (isset($_SESSION[self::$token_name]) && strcmp($_SESSION[self::$token_name], $token) === 0) {
            $result = true;
        }
        
        if ($forceInvaild) {
            $_SESSION[self::$token_name] = null;
        }
        return $result;
    }
    
    /**
     * 清除一些垃圾数据，防止 XSS
     * Remove XSS from user input.
     *
     * <code>
     * $str = $this->input->xssClean($str);
     * </code>
     *
     * @param mixed string or array to sanitize
     * @return string
     */
    public function xssClean($str)
    {
        // http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php
        // Kohana Modifications:
        // * Changed double quotes to single quotes, changed indenting and spacing
        // * Removed magic_quotes stuff
        // * Increased regex readability:
        // * Used delimeters that aren't found in the pattern
        // * Removed all unneeded escapes
        // * Deleted U modifiers and swapped greediness where needed
        // * Increased regex speed:
        // * Made capturing parentheses non-capturing where possible
        // * Removed parentheses where possible
        // * Split up alternation alternatives
        // * Made some quantifiers possessive
        // * Handle arrays recursively
        
        if (is_array($str)) {
            foreach ($str as $k => $s) {
                $str[$k] = $this->xssClean($s);
            }
            
            return $str;
        } /**
         * 我们不支持对象的过滤
         */
        elseif (is_object($str)) {
            return '';
        }
        
        // Remove all NULL bytes
        $str = str_replace("\0", '', $str);
        
        // Fix &entity\n;
        $str = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $str);
        $str = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $str);
        $str = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $str);
        $str = html_entity_decode($str, ENT_COMPAT, 'utf-8');
        
        // Remove any attribute starting with "on" or xmlns
        $str = preg_replace('#(?:on[a-z]+|xmlns)\s*=\s*[\'"\x00-\x20]?[^\'>"]*[\'"\x00-\x20]?\s?#iu', '', $str);
        
        // Remove javascript: and vbscript: protocols
        $str = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $str);
        $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $str);
        $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $str);
        
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#is', '$1>', $str);
        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#is', '$1>', $str);
        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#ius', '$1>', $str);
        
        // Remove namespaced elements (we do not need them)
        $str = preg_replace('#</*\w+:\w[^>]*+>#i', '', $str);
        
        do {
            // Remove really unwanted tags
            $old = $str;
            $str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);
        } while ($old !== $str);
        
        return $str;
    }
    
    /**
     * 创建Zeed_Request_Http对象(单列)
     *
     * @return Zeed_Controller_Request
     */
    public static function instance()
    {
        static $_instance = null;
        
        if (! $_instance instanceof Zeed_Controller_Request) {
            $_instance = new Zeed_Controller_Request();
        }
        
        return $_instance;
    }
}

// End ^ LF ^ UTF-8
