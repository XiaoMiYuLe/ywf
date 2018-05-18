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
 * 客户端被KeyManager通知更新KEYS&PERMISSIONS接口
 */
abstract class Com_KeyManager_ClientApi extends Zeed_Controller_Action
{
	private $apimap = 'internalapimap';
	
    public function index()
    {
        //验证请求是否合法
        try {
    		$parameters = $this->_validate();
        } catch (Exception $e) {
            $errorCode = $e->getCode() ? $e->getCode() : 20001;
            $this->_UnauthorizedCall($e->getMessage(), $errorCode);
            exit();
        }
        
        //更新KEYS&PERMISSIONS
        Com_KeyManager_Client::getInstance()->updateCache();
        echo json_encode(array('status' => 0, 'data' => null, 'error' => null));
        exit();
    }
    
    public function getMap()
    {
    	try{
    		$parameters = $this->_validate();
    	}catch(Exception $e){
            $errorCode = $e->getCode() ? $e->getCode() : 20001;
            $this->_UnauthorizedCall($e->getMessage(), $errorCode);
            exit();
    	}
    	$apis = Zeed_Config::loadGroup($this->apimap);
        echo json_encode(array('status' => 0, 'data' => $apis, 'error' => null));
        exit();
    	
    }
    
//    private function getParameter()
//    {
//    }
    
    private function _validate()
    {
		$request = Zeed_OAuth_Request::fromRequest();
		$consumerKey = @$request->getParameter("oauth_consumer_key");
		$apikey = Zeed_Config::loadGroup('kmclient');
		if ($consumerKey != $apikey['key']) {
			throw new Exception('Invalid consumer key', 20002);
		}
		$consumers = array($consumerKey => $apikey['secret']);
		$token = NULL;
		$parameters = Zeed_OAuth_InternalValidator::validate($consumers, $token, $request);
		return $parameters;
    }
    
    protected function _UnauthorizedCall($msg = 'Unauthorized Call', $code = 20001)
    {
        exit(json_encode(array('status' => $code, 'data' => null, 'error' => $msg)));
    }
}