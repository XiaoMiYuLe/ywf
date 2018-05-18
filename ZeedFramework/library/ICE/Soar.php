<?php
/**
 *
 * platform programe
 * @category   Trendible
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @author     shaun.song ( GTalk/Email: songsj125@gmail.com | MSN: ssj125@hotmail.com )
 * @since      2010-7-1
 * @version    SVN: $Id$
 */
class ICE_Soar
{
    const SID = 'sid';
    
    //1.2版本配置
    const DOMAIN = ".playcool.com";
    private static $errlog_location = '//vmdev-passport:10000';
    private static $svcenv = array(
            'passport' => array(
                    'addr' => 'vmdev-passport:11000',
                    'json' => true));
    
    /**
     * SOAR 
     * 支持1.0、1.2版本
     * 
     * @param boolen $isJump  当未登录时是否跳转 默认否
     * @param array $fileds   获取数据的字段名
     * @param string $version 版本，当前支持1.0、1.2版本
     * 
     * @return boolen|array
     */
    public static function checkauth($isJump = false, $fileds = array('uuid','account'), $version = '1.0')
    {
        if ($version == '1.0') {
            return self::checkAuth_1_0($isJump, $fileds);
        } elseif ($version == '1.2') {
            return self::checkAuth_1_2($isJump, $fileds);
        } else {
            return 'version error';
        }
    }
    
     /**
     * logout
     * 支持1.0、1.2版本
     * 
     * @param string $version 版本，当前支持1.0、1.2版本
     * @return boolen
     */
    public static function logout($version = '1.0')
    {
        if ($version == '1.0') {
            return self::logout_1_0();
        } elseif ($version == '1.2') {
            return self::logout_1_2();
        } else {
            return 'version error';
        }
    }
    
    /**
     * soar 1.0版本
     * 
     * @param boolen $isJump
     * @param array $fileds
     * @return boolen|array
     */
    private static function checkAuth_1_0($isJump = false, $fileds = array('uuid','account'))
    {
        // Check cookie
        if (! isset($_COOKIE[self::SID])) {
            //Kohana::log("error", __METHOD__ . ": cookie (" . self::SID . ") not set on line " . __LINE__);
            if ($isJump) {
                self::login();
            }
            return false;
        }
        $sid = $_COOKIE[self::SID];
        
        // Initialized session manager. 
        require_once dirname(__FILE__) . '/../3rd/soar/1.0/soar.inc';
        
        $soar = new soar();
        
        $session = $soar->session();
        
        $soar->close();
        // Fetch session values.
        $session->setsid($sid);
            
        $values = $session->getkey($fileds);

        // Handle error.
        if (is_null($values)) {
            //Kohana::log("error", __METHOD__ . ": cookie(" . self::SID . ")=$sid not valid, err:" . $session->last_error . "on line " . __LINE__);
            if ($isJump) {
                self::login();
            }
            return false;
        
        }
        
        $param = self::getParam($fileds, $values);
        $param[self::SID] = $_COOKIE[self::SID];
        
        return $param;
    }
    
    /**
     * soar 1.2版本
     * 
     * @param boolen $isJump
     * @param array $fileds
     * @return boolen|array
     */
    private static function checkAuth_1_2($isJump = false, $fileds = array('uuid','account'))
    {
        
        // Soar
        require_once dirname(__FILE__) . '/../3rd/soar/1.2/web.inc';
        errlog::init("httpd", self::$errlog_location);
        
        if (! isset($_COOKIE[self::SID])) {
            errlog::add("%s|%s: cookie(%s) not set", basename(__FILE__), __METHOD__, self::SID);
            if ($isJump) {
                self::login();
            }
            return false;
        }
        
        $sid = $_COOKIE[self::SID];
        
        errlog::add("%s|%s: cookie(%s)=%s", basename(__FILE__), __METHOD__, self::SID, $sid);
        $svcenv = new svcenv(self::$svcenv['passport']);
        $session = $svcenv->session();
        $session->setsid($sid);
        
        $res = $session->validate();
        
        if ($res != true) {
            if ($isJump) {
                self::login();
            }
            return false;
        }
        $user_info = array(self::SID=>$sid);
        foreach ($fileds as $key) {
            $vals = $session->getkey(array(
                    $key));
            if (is_null($vals)) {
                if ($session->last_error != SOAR_ERR_SESSION_KEY_NOT_FOUND) {
                    errlog::add("%s|%s: cookie(%s)=%s not valid, ", "err:%s", basename(__FILE__), __METHOD__, self::SID, $sid, $session->last_error);
                    return false;
                }
                $user_info[$key] = '';
            } else
                $user_info[$key] = $vals[0];
        }
        return $user_info;
    
    }
    
    /**
     * logout 1.0版本
     * 
     * @return boolen|array
     */
    private static function logout_1_0()
    {
        // Check cookie
        if (! isset($_COOKIE[self::SID])) {
            //Kohana::log("error", __METHOD__ . ": cookie (" . self::SID . ") not set on line " . __LINE__);
            return false;
        }
        $sid = $_COOKIE[self::SID];
        
        // Initialized session manager. 
        require_once dirname(__FILE__) . '/../3rd/soar/1.0/soar.inc';
        
        $soar = new soar();
        
        $session = $soar->session();
        
        $soar->close();
        // Fetch session values.
        $session->setsid($sid);
        
        if ($session->destroy() != true) {
            //Kohana::log("error", __METHOD__ . ": cookie(" . self::SID . ")=$sid not valid, err:" . $session->last_error . "on line " . __LINE__);
            return false;
        }
        
        setcookie(self::SID, "", 0, "/", self::DOMAIN);
        
        return true;
    }
    
    /**
     * logout 1.2版本
     * @return boolen
     */
    private static function logout_1_2()
    {
        // Soar
        require_once dirname(__FILE__) . '/../3rd/soar/1.2/web.inc';
        errlog::init("httpd", self::$errlog_location);
        
        if (! isset($_COOKIE[self::SID])) {
            errlog::add("%s.%s: cookie(%s) empty", basename(__FILE__), __METHOD__, self::SID);
            return false;
        }
        
        $sid = $_COOKIE[self::SID];
        $svcenv = new svcenv(self::$svcenv['passport']);
        $session = $svcenv->session();
        $session->setsid($sid);
        if ($session->destroy() != true) {
            errlog::add("%s.%s: fail, err:%s", basename(__FILE__), __METHOD__, $session->last_error);
        }
        
        setcookie(self::SID, "", 0, "/", self::DOMAIN);
        
        return true;
    }
    
    /**
     * 修正数据
     * 
     * @param array $fileds
     * @param array $data
     * @return array
     */
    private static function getParam($fileds, $data)
    {
        if (empty($fileds)) {
            return array();
        }
        
        $row = array();
        foreach ($fileds as $k => $v) {
            $row[$v] = isset($data[$k]) ? $data[$k] : null;
        }
        
        return $row;
    }
    
    /**
     * 登录页面
     */
    private static function login()
    {
        $returl = urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF']);
        
        // Redirect passport logout.
        header('Location:http://member.playcool.com/sign_in.aspx?ReturnUrl=' . $returl);
        exit();
    }

}

// End ^ LF ^ UTF-8