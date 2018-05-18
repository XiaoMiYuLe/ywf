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
 * @since      Jul 5, 2010
 * @version    SVN: $Id: DataStore.php 8493 2010-11-17 09:19:09Z xsharp $
 */

/**
 * Data store for Platform
 */
class Com_OAuth_DataStore extends Zeed_OAuth_DataStore_Abstract {
    private $consumer;
    private $requestToken;
    private $accessToken;
    private $nonce;
    
    const TOKEN_STATUS_GRANTED = 2;
    const TOKEN_STATUS_DENIED = 1;
    const TOKEN_STATUS_NEW = 0;
    const TOKEN_STATUS_EXPIRED = -1;

    public function __construct() {
        $this->consumer = new Zeed_OAuth_Consumer("key", "secret");
        $this->requestToken = new Zeed_OAuth_Token("requestkey", "requestsecret");
        $this->accessToken = new Zeed_OAuth_Token("accesskey", "accesssecret");
        $this->nonce = "nonce";
    }

    /**
     * 获取应用信息及密钥
     * 
     * @param string $consumer_key
     * @return OAuthConsumer
     */
    public function lookupConsumer($consumer_key) {
        $app = Com_Model_App::instance()->getAppByApikey($consumer_key);
        if ($app) return new Zeed_OAuth_Consumer($app['apikey'],$app['apisecret']);
        return NULL;
    }

    /**
     * 
     * @param OAuthConsumer $consumer
     * @param string $token_type
     * @param string $token
     * @return Zeed_OAuth_Token|NULL
     */
    public function lookupToken($consumer, $token_type, $token_key) {
        if ($token_type == 'request') {
            $tokenInDb = Com_Model_Token::instance()->getTokenByRequestToken($token_key);
            if ($tokenInDb && $tokenInDb['apikey'] == $consumer->key) {
                return new Zeed_OAuth_Token($tokenInDb['request_token'],$tokenInDb['request_secret']);
            }
        } else if ($token_type == 'access') {
            $tokenInDb = Com_Model_Token::instance()->getTokenByAccessToken($token_key);
            if ($tokenInDb && $tokenInDb['apikey'] == $consumer->key && $tokenInDb['status'] == self::TOKEN_STATUS_GRANTED) {
                return new Zeed_OAuth_Token($tokenInDb['access_token'],$tokenInDb['access_secret']);
            } else {
                throw new Zeed_OAuth_Exception("User Denied  Access");
            }
        }
        
        return NULL;
    }

    public function lookupNonce($consumer, $token, $nonce, $timestamp) {
        if ($consumer->key == $this->consumer->key
            && (($token && $token->key == $this->request_token->key)
                || ($token && $token->key == $this->access_token->key))
            && $nonce == $this->nonce) {
            return $this->nonce;
        }
        return NULL;
    }

    /**
     * 产生新的Request Token
     * @param OAuthConsumer $consumer
     */
    public function newRequestToken($consumer, $callback_url = NULL) {
        $app = Com_Model_App::instance()->getAppByApikey($consumer->key);
        if ($app) {
            $token = $this->getRandomString();
            $secret = $this->getRandomString();
            $accessToken = $this->getRandomString();
            $accessSecret = $this->getRandomString();
            $tokenVerifier = $this->getRandomString();
            $tokenSet = array('apikey'=>$consumer->key,'request_token'=>$token,'request_secret'=>$secret,'callback_url'=>$callback_url,
                                'access_token'=>$accessToken,'access_secret'=>$accessSecret,'verifier'=>$tokenVerifier);
            Com_Model_Token::instance()->addToken($tokenSet);
            return new Zeed_OAuth_Token($token, $secret);
        }
        return NULL;
    }

    /**
     * 产生新的ACCESS TOKNE
     * @param Zeed_OAuth_Token $request_token
     * @param OAuthConsumer $consumer
     * @param string $verifier
     */
    public function newAccessToken($request_token, $consumer, $verifier = null) {
        $tokenInDb = Com_Model_Token::instance()->getTokenByRequestToken($request_token->key);
        if ($tokenInDb && $tokenInDb['apikey'] == $consumer->key && $tokenInDb['status'] == self::TOKEN_STATUS_GRANTED && $tokenInDb['verifier'] == $verifier) {
            return new Zeed_OAuth_Token($tokenInDb['access_token'],$tokenInDb['access_secret']);
        } else if ($tokenInDb['verifier'] != $verifier) {
            throw new Zeed_OAuth_Exception("Invalid Verifier");
        } else if ($tokenInDb['status'] != self::TOKEN_STATUS_GRANTED) {
            throw new Zeed_OAuth_Exception("User Denied Access");
        }
        
        return NULL;
    }
    
    /**
     * 生成随机不重复字串
     * 
     * @return string
     */
    public static function getRandomString()
    {
        $computer = $_SERVER["SERVER_NAME"].'/'.$_SERVER["SERVER_ADDR"];
        $long = (rand(0,1)?'-':'').rand(1000, 9999).rand(1000, 9999).rand(1000, 9999).rand(100, 999).rand(100, 999);
        $microtime = microtime(true);
        return rand(0,1) ? 'm-'.sha1($computer.$long.$microtime) : 's-'.md5($computer.$long.$microtime);
    }
}

// End ^ Native EOL ^ encoding
