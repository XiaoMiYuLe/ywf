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
 * @since      Sep 16, 2010
 * @version    SVN: $Id: Exception.php 7427 2010-09-27 07:33:47Z xsharp $
 */

class Com_Admin_Permission_Node extends Com_Admin_Permission
{
    protected $_userPermissions;
    protected $_appPermission;
    /**
     * 检查当前用户是否有相应权限
     * @param integer $permissionid
     * @return boolean
     */
    public function hasPermission($permissionid)
    {
        if (is_null($this->_userPermissions)) {
            $rs = $this->_oauthRequest('user');
            if (!$rs) {
                return false;
            }
            $this->_userPermissions = $rs['data'];
        }
        return in_array($permissionid, $this->_userPermissions);
    }
    
    /**
     * 获取当前用户的权限
     */
    public function getUserPermission()
    {
        if (is_null($this->_userPermissions)) {
            $rs = $this->_oauthRequest('user');
            if (!$rs) {
                return array();
            }
            $this->_userPermissions = $rs['data'];
        }
        return $this->_userPermissions;
    }
    
    /**
     * 获取访问ACTINO需要的权限
     */
    public function getAppPermission()
    {
        $parameters = array('x_action'=>$this->_action, 'x_controller'=>$this->_controller, 'x_module'=>$this->_module);
        $rs = $this->_oauthRequest('index', $parameters);
        if (!$rs) {
            return null;
        }
        $this->_userPermissions = $rs['data']['user_permissions'];
        return $this->_appPermissions = $rs['data']['app_permission'];
    }
    
    protected $_oauthConsumer;
    protected $_oauthApiSignMethod;
    protected $_aclUrl;
    protected function _oauthRequest($action, $parameters = null)
    {
        $accessToken = Zeed_OAuth_Util::parseParameters($_SESSION['admin.access_token']);
        $accessToken = new Zeed_OAuth_Token( $accessToken['oauth_token'], $accessToken['oauth_token_secret']);
        if (is_null($this->_oauthConsumer)) {
            $config = Zeed_Config::loadGroup('access');
            $this->_oauthConsumer = new Zeed_OAuth_Consumer($config['appkey'], $config['appsecret']);
            $this->_oauthApiSignMethod = new Zeed_OAuth_Signature_HMACSHA1();
            $this->_aclUrl = $config['acl_url'];
        }

        $request = Zeed_OAuth_Request::fromConsumerAndToken($this->_oauthConsumer, $accessToken, "GET", $this->_aclUrl.$action, $parameters);
        $request->signRequest($this->_oauthApiSignMethod, $this->_oauthConsumer, $accessToken);

        $response = $request->request();
        if ($response === false || $response['code'] != 200) {
            return false;
        }
        $result = $response['body'];
        $result = json_decode($result, true);

        if (!is_array($result)) {
            return false;
        }
        
        return $result;
    }
}
