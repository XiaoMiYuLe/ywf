<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 10, 2010
 * @version    SVN: $$Id$$
 */

class Com_Admin_Permission implements Zeed_Permission_Interface
{
    protected static $_instance;
    
    /**
     * @var Zeed_Controller_Request
     */
    protected $_request;
    protected $_action;
    protected $_controller;
    protected $_module;
    protected $_appkey;
    
    public function __construct($request)
    {
        $this->_request = $request;
        $this->_action = strtolower($this->_request->getActionName());
        $this->_controller = strtolower($this->_request->getControllerName());
        $this->_module = strtolower($this->_request->getModuleName());
        $this->_appkey = $this->getAppKey();
        if ($this->_module == 'default') {
            $this->_module = strtolower(Zeed_Config::loadGroup('access.defaultModule'));
        }
        
        self::$_instance = $this;
    }
    
    public function compare()
    {
        $fullAction = $this->_module.'.'.$this->_controller.'.'.$this->_action;
        $fullController = $this->_module.'.'.$this->_controller;
        $fullFrontend = $this->_module.'.frontend';
        //登陆忽略权限配置检查
        $ignored = Zeed_Config::loadGroup('access.pm_ignore');
        if (in_array($fullController, $ignored)) {
            return true;
        } else if (in_array($fullAction, $ignored)) {
            return true;
        } else if (in_array($fullFrontend, $ignored)) {
            return true;
        } else if (in_array($this->_module, $ignored)) {
            return true;
        }
        
        Zeed_Session::instance();
        
        if ( $this->_appkey == 'admin' && 
            ( strtolower($this->_controller) == 'sign' || strtolower($this->_controller) == 'acl' || strtolower($this->_controller) == 'captcha' ) && 
            ( strtolower($this->_module) == 'admin' ) ) {
            return true;
        }

        $user = Com_Admin_Authorization::getLoggedInUser();
        if (!$user) {
            //用户没有登录
            if (Zeed_Controller_Request::instance()->isAJAX()) {
//                 $rd = array('status'=>11001, 'data'=>null, 'error'=>'会话已过期，请重新登录');
//                 exit(json_encode($rd));
            }
            $url = ($_SERVER['SERVER_PORT'] == "443") ? 'https' : 'http';
//             $url .= '://'.$_SERVER['HTTP_HOST'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            $url .= '://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
            $location = Zeed_Config::loadGroup('access.login_url').'continue='.urlencode($url).'&msg='.'会话已过期，请重新登录';
            header('Location: '.$location);
            exit;
        }
        
        //超级管理员
        if ($user['username'] == 'admin') {
            return true;
        }
        
        //登陆忽略权限配置检查
        $ignored = Zeed_Config::loadGroup('access.pm_login_ignore');
        if (in_array($fullController, $ignored)) {
            return true;
        } else if (in_array($fullAction, $ignored)) {
            return true;
        } else if (in_array($this->_module, $ignored)) {
            return true;
        }
        
        $nopermission = false;
        $actionPermission = $this->getAppPermission();
        if (is_null($actionPermission)) {
            $nopermission = true;
        } else {
            $userPermission = $this->getUserPermission();
            if (!in_array($actionPermission, $userPermission)) {
                $nopermission = true;
            }
        }
        
        if ($nopermission) {
            //用户没有权限
            if (Zeed_Controller_Request::instance()->isAJAX()) {
                $rd = array('status'=>11002, 'data'=>null, 'error'=>'对不起，你没有权限执行此操作！所需权限ID:'.$actionPermission);
                exit(json_encode($rd));
            }
            
            exit('Sorry, you have no permission to do the operation! Permission ID:'.$actionPermission);
        }
        
        return true;
    }
    
    /**
     * 检查当前用户是否有相应权限
     * @param integer $permissionid
     * @return boolean
     */
    public function hasPermission($permissionid)
    {
        $user = Com_Admin_Authorization::getLoggedInUser();
        $userPermission = Com_Admin_Model_User::instance()->getAllPermissionsOfUser($user['username']);
        if (empty($userPermission)) {
            return false;
        }
        
        if (in_array($permissionid, $userPermission)) {
            return true;
        }
        return false;
    }
    
    /**
     * 获取当前登录用户的所有权限
     * @return array()
     */
    public function getUserPermission()
    {
        $user = Com_Admin_Authorization::getLoggedInUser();
        $pm = Com_Admin_Model_User::instance()->getAllPermissionsOfUser($user['username']);
        return $pm;
    }
    
    /**
     *  获取当前访问资源需要的权限
     *  @return integer
     */
    public function getAppPermission()
    {
        return Com_Admin_Model_AppPermission::instance()->getActionPermission($this->getAppKey(),$this->_module,$this->_controller,$this->_action);
    }
    
    public function getAppKey()
    {
        if (is_null($this->_appkey)) {
            $this->_appkey = Zeed_Config::loadGroup('access.appkey');
        }
        return $this->_appkey;
    }
    
    /**
     * 获取当前的使用的权限类实例
     * 
     * @return Com_Admin_Permission
     */
    public static function instance()
    {
        return self::$_instance;
    }
}

// End ^ LF ^ encoding
