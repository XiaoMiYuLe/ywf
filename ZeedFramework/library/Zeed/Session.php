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
 * @package    Zeed_Session
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Session.php 10460 2011-06-07 05:35:51Z xsharp $
 */

defined('SESSION_NAME') || define('SESSION_NAME', 'ZEED_SESSION');

class Zeed_Session
{
    private static $_sessionid;

    private static $_sessionStarted = false;
    private static $_writeClosed = false;
    private static $_destroyed = false;
    private static $_sessionCookieDeleted = false;

    /**
     * Whether or not the session id has been regenerated this request.
     *
     * Id regeneration state
     * <0 - regenerate requested when session is started
     * 0  - do nothing
     * >0 - already called session_regenerate_id()
     *
     * @var int
     */
    private static $_regenerateIdState = 0;

    /**
     * Private list of php's ini values for ext/session
     * null values will default to the php.ini value, otherwise
     * the value below will overwrite the default ini value, unless
     * the user has set an option explicity with setOptions()
     *
     * @var array
     */
    private static $_defaultOptions = array(
            'save_path' => null,
            'name' => null, /* this should be set to a unique value for each application */
            'save_handler' => null,
            //'auto_start'                => null, /* intentionally excluded (see manual) */
            'gc_probability' => null,
            'gc_divisor' => null,
            'gc_maxlifetime' => null,
            'serialize_handler' => null,
            'cookie_lifetime' => null,
            'cookie_path' => null,
            'cookie_domain' => null,
            'cookie_secure' => null,
            'cookie_httponly' => null,
            'use_cookies' => null,
            'use_only_cookies' => 'on',
            'referer_check' => null,
            'entropy_file' => null,
            'entropy_length' => null,
            'cache_limiter' => null,
            'cache_expire' => null,
            'use_trans_sid' => null,
            'bug_compat_42' => null,
            'bug_compat_warn' => null,
            'hash_function' => null,
            'hash_bits_per_character' => null);

    /**
     * List of options pertaining to Zend_Session that can be set by developers
     * using Zeed_Session::setOptions(). This list intentionally duplicates
     * the individual declaration of static "class" variables by the same names.
     *
     * @var array
     */
    private static $_localOptions = array(
            'use_uuid_for_sid' => '_useUUIDForSID');

    /**
     * 是否使用 UUID 作为 Session ID
     */
    private static $_useUUIDForSID = true;

    /**
     * 默认设置是否已设置标记
     * Whether the default options listed in Zend_Session::$_localOptions have been set
     *
     * @var bool
     */
    private static $_defaultOptionsSet = false;

    /**
     * @var Zeed_Session_Storage_Interface
     */
    private static $_storager = null;

    /**
     * Session Save Handler assignment
     *
     * @param Zeed_Session_Storage_Interface $interface
     * @return void
     */
    public static function setStorager(Zeed_Session_Storage_Interface $storager)
    {
        self::$_storager = $storager;

        session_set_save_handler(array(
                &$storager,
                'open'), array(
                &$storager,
                'close'), array(
                &$storager,
                'read'), array(
                &$storager,
                'write'), array(
                &$storager,
                'destroy'), array(
                &$storager,
                'gc'));
    }

    public static function getStorager()
    {
        return self::$_storager;
    }

    /**
     * 重新生成 Session ID，这意味了 Session 的数据将初始化
     * 建议在 Session 启动后再调用该函数
     *
     * @throws Zeed_Exception
     * @return void
     */
    public static function regenerateId($deleteOldSession = true)
    {
        if (! self::$_sessionStarted) {
            if (headers_sent($filename, $linenum)) {
                throw new Zeed_Exception("You must call " . __CLASS__ . '::' . __FUNCTION__ . "() before any output has been sent to the browser; output started in {$filename}/{$linenum}");
            }
        }

        if (self::$_sessionStarted && self::$_regenerateIdState <= 0) {

            session_regenerate_id($deleteOldSession);
            self::$_regenerateIdState = 1;
        } else {
            self::$_regenerateIdState = - 1;
        }
    }

    /**
     * 启动 Session
     *
     * @param bool|array $options  OPTIONAL Either user supplied options, or flag indicating if start initiated automatically
     * @throws Zend_Session_Exception
     * @return void
     */
    public static function start($options = false)
    {
        if (self::$_sessionStarted && self::$_destroyed) {
            throw new Zeed_Exception('The session was explicitly destroyed during this request, attempting to re-start is not allowed.');
        }

        if (self::$_sessionStarted) {
            return; // already started
        }

        $filename = $linenum = null;
        if (headers_sent($filename, $linenum)) {
            throw new Zeed_Exception("Session must be started before any output has been sent to the browser;" . " output started in {$filename}/{$linenum}");
        }

        // See http://www.php.net/manual/en/ref.session.php for explanation
        if (defined('SID')) {
            throw new Zeed_Exception('session has already been started by session.auto-start or session_start()');
        }

        $startedCleanly = session_start();

        self::$_sessionStarted = true;

        if (self::$_regenerateIdState === - 1) {
            self::regenerateId();
        }
    }

    /**
     * 获取当前 Session ID
     * session_id() returns the session id for the current session or the empty string ("")
     * if there is no current session (no current session id exists).
     *
     * @return string
     */
    public static function getID()
    {
        return session_id();
    }

    /**
     * Session 设置
     *
     * @param  array $userOptions - pass-by-keyword style array of <option name, option value> pairs
     * @throws Zend_Session_Exception
     * @return void
     */
    public static function setOptions(array $userOptions = array(), $throwException = true)
    {
        // set default options on first run only (before applying user settings)
        if (! self::$_defaultOptionsSet) {
            foreach (self::$_defaultOptions as $defaultOptionName => $defaultOptionValue) {
                if (isset(self::$_defaultOptions[$defaultOptionName])) {
                    ini_set("session.$defaultOptionName", $defaultOptionValue);
                }
            }

            self::$_defaultOptionsSet = true;
        }

        // set the options the user has requested to set
        foreach ($userOptions as $userOptionName => $userOptionValue) {

            $userOptionName = strtolower($userOptionName);

            // set the ini based values
            if (array_key_exists($userOptionName, self::$_defaultOptions)) {
                ini_set("session.$userOptionName", $userOptionValue);
            } elseif (isset(self::$_localOptions[$userOptionName])) {
                self::${self::$_localOptions[$userOptionName]} = $userOptionValue;
            }

            if ($throwException) {
                throw new Zeed_Exception("Unknown option: $userOptionName = $userOptionValue");
            }
        }
    }

    /**
     * 为当前用户指定一个 Session ID
     *
     * @param string $id
     * @return void
     * @throws Zeed_Exception
     */
    public static function setID($id)
    {
        if (defined('SID')) {
            throw new Zeed_Exception('The session has already been started.  The session id must be set first.');
        }

        if (headers_sent($filename, $linenum)) {
            throw new Zeed_Exception("You must call " . __CLASS__ . '::' . __FUNCTION__ . "() before any output has been sent to the browser; output started in {$filename}/{$linenum}");
        }

        if (! is_string($id) || $id === '') {
            throw new Zeed_Exception('You must provide a non-empty string as a session identifier.');
        }

        session_id($id);
    }

    public static function getSessionid()
    {
        if ($_SESSION[SESSION_NAME] != '') {
            self::$_sessionid = $_SESSION[SESSION_NAME];
            unset($_SESSION[SESSION_NAME]);
            $_SESSION[SESSION_NAME] = self::$_sessionid; // Reset
        } elseif ($_COOKIE[SESSION_NAME] != '') {
            self::$_sessionid = $_COOKIE[SESSION_NAME];
            unset($_SESSION[SESSION_NAME]);
            $_SESSION[SESSION_NAME] = self::$_sessionid; // Reset
        } else {
            return 0;
        }
    }

    /**
     * destroy() - This is used to destroy session data, and optionally, the session cookie itself
     *
     * @param bool $remove_cookie - OPTIONAL remove session id cookie, defaults to true (remove cookie)
     * @return void
     */
    public static function destroy($removeCookie = true)
    {
        if (self::$_destroyed) {
            return;
        }

        session_destroy();
        self::$_destroyed = true;

        if ($removeCookie) {
            self::expireSessionCookie();
        }
    }

    /**
     * 清除SESSION
     * 
     * @param string|array $session_name
     */
    public static function unsetSession ($session_name)
    {
        if (is_array($session_name)) {
            foreach ($session_name as $v) {
                unset($_SESSION[$v]);
            }
        } else {
            unset($_SESSION[$session_name]);
        }
    }

    /**
     * expireSessionCookie() - Sends an expired session id cookie, causing the client to delete the session cookie
     *
     * @return void
     */
    public static function expireSessionCookie()
    {
        if (self::$_sessionCookieDeleted) {
            return;
        }

        self::$_sessionCookieDeleted = true;

        if (isset($_COOKIE[session_name()])) {
            $cookie_params = session_get_cookie_params();

            // strtotime('1980-01-01'),
            setcookie(session_name(), false, 315554400, $cookie_params['path'], $cookie_params['domain'], $cookie_params['secure']);
        }
    }
    
    /**
     * writeClose() - Shutdown the sesssion, close writing and detach $_SESSION from the back-end storage mechanism.
     * This will complete the internal data transformation on this request.
     *
     * @return void
     */
    public static function writeClose()
    {
        if (self::$_writeClosed) {
            return;
        }
        
        session_write_close();
        self::$_writeClosed = true;
    }
    
    /**
     * 快速初始化SESSION, 参数可以为字符串或数组, 为字符串时自动加载配置.
     * 
     * @param string|array $config
     * @return bloean
     */
    public static function instance($config = null)
    {
        if (is_null($config)) {
            $sessionConfig = Zeed_Config::loadGroup('session');
        } elseif (is_string($config)) {
            $sessionConfig = Zeed_Config::loadGroup($config);
        } elseif (is_array($config)) {
            $sessionConfig = $config;
        }
        
        if (is_array($sessionConfig)) {
            $storager = $sessionConfig['storager'];
            if ($storager == 'default') {
                $sessionStorager = null;
            } elseif ($storager != '') {
                $class = 'Zeed_Session_Storage_' . ucfirst($storager);
                $sessionStorager = new $class($sessionConfig[$storager]);
            } else {
                throw new Zeed_Exception('No session storager defined.');
            }
            
            if (null !== $sessionStorager) {
                Zeed_Session::setStorager($sessionStorager);
            }
            
            if (isset($sessionConfig[$storager]) && is_array($sessionConfig[$storager]) && count($sessionConfig[$storager])) {
                Zeed_Session::setOptions($sessionConfig[$storager], false);
            }
            
            Zeed_Session::start();
            
            return true;
        }
        
        return false;
    }
}

// End ^ LF ^ UTF-8
