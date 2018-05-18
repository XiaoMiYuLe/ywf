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
 * 内部API入口抽象
 */
abstract class Com_Controller_InternalApi extends Zeed_Controller_Action
{
    /**
     * OAuth 请求
     * @var Zeed_OAuth_Request
     */
    protected $_oauthRequest;
    
    /**
     * OAuth 请求参数
     * @var array
     */
    protected $_oauthParameters;
    
    /**
     * API入口名称
     * @var string
     */
    protected $_apiNameSpace = 'InternalApi';
    
    /**
     * API是否需要使用CONSUMER作为TOKEN，否则调用时TOKEN为NULL
     * @var boolean
     */
    protected $_useKeyAsToken = false;
    
    /**
     * api配置文件
     * @var unknown_type
     */
	private $apimap = 'internalapimap';
    
    public function index()
    {
        //检查方法是否存在
        $method = $this->getParam('__REQUEST_METHOD__');
        if (empty($method)) {
            $this->_MethodNotFound();
            exit();
        }
        try {
        	$parameters = $this->validatePermission($method);
        } catch (Exception $e) {
        	$errorCode = $e->getCode() ? $e->getCode() : 20001;
            $this->_UnauthorizedCall($e->getMessage(), $errorCode);
            exit();
        }
        $config = Zeed_Config::loadGroup($this->apimap);
        $api = $config[$method];
		$className = $api['class'];
		$methodName = $api['function'];
        //$result = call_user_func(array($className,$methodName) ,$parameters);
        $c = new $className($parameters);
        $result = $c->$methodName();
        
        $return = array('status'=>0, 'data'=>$result, 'error'=>null);
    	echo json_encode($return);
    }
    /**
     * 检查当前调用者的KEY是否有权限访问指定方法
     * @param string $method
     * @throws Exception
     */
    protected function validatePermission($method)
    {
        $request = Zeed_OAuth_Request::fromRequest();
        $consumerKey = @$request->getParameter("oauth_consumer_key");
        $secret = Com_KeyManager_Client::getInstance()->getKeySecret($consumerKey);
        if ($secret === false) {
            throw new Exception('Invalid consumer key', 20002);
        }
        $consumers = array($consumerKey => $secret);
        $token = ($this->_useKeyAsToken) ? new Zeed_OAuth_Token($consumerKey, $secret) : null;
        $parameters = Zeed_OAuth_InternalValidator::validate($consumers, $token, $request);
        
        $permissions = Com_KeyManager_Client::getInstance()->getKeyPermissions($consumerKey);
        if (! in_array($this->_apiNameSpace, $permissions) && ! in_array($this->_apiNameSpace . '/' . $method, $permissions)
        	&& ! in_array($method, $permissions)) {  /*the last condition is added by sxy*/
            throw new Exception('EW_OAUTH_NO_PERMISSION', 20006);
        }
        
        return $parameters;
    }
    
    protected function _MethodNotFound()
    {
        exit(json_encode(array('status' => - 1, 'data' => null, 'error' => 'Method Not Found')));
    }
    
    protected function _UnauthorizedCall($msg = 'Unauthorized Call', $code = 20001)
    {
        exit(json_encode(array('status' => $code, 'data' => null, 'error' => $msg)));
    }
}