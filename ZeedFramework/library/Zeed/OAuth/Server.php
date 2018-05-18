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
 * @version    SVN: $Id: Server.php 5517 2010-07-05 08:12:54Z woody $
 */

class Zeed_OAuth_Server
{
    protected $timestampThreshold = 300; // in seconds, five minutes
    protected $version = '1.0'; // hi blaine
    protected $signatureMethods = array();
    
    protected $dataStore;
    
    function __construct($dataStore)
    {
        $this->dataStore = $dataStore;
    }
    
    public function addSignatureMethod($signatureMethod)
    {
        $this->signatureMethods[$signatureMethod->getName()] = $signatureMethod;
    }
    
    // high level functions
    

    /**
     * process a request_token request
     * returns the request token on success
     */
    public function fetchRequestToken($request)
    {
        $this->getVersion($request);
        
        $consumer = $this->getConsumer($request);
        
        // no token required for the initial token request
        $token = NULL;
        
        $this->checkSignature($request, $consumer, $token);
        
        // Rev A change
        $callback = $request->getParameter('oauth_callback');
        $newToken = $this->dataStore->newRequestToken($consumer, $callback);
        
        return $newToken;
    }
    
    /**
     * process an access_token request
     * returns the access token on success
     */
    public function fetchAccessToken($request)
    {
        $this->getVersion($request);
        
        $consumer = $this->getConsumer($request);
        
        // requires authorized request token
        $token = $this->getToken($request, $consumer, "request");
        
        $this->checkSignature($request, $consumer, $token);
        
        // Rev A change
        $verifier = $request->getParameter('oauth_verifier');
        $newToken = $this->dataStore->newAccessToken($token, $consumer, $verifier);
        
        return $newToken;
    }
    
    /**
     * verify an api call, checks all the parameters
     */
    public function verifyRequest($request)
    {
        $this->getVersion($request);
        $consumer = $this->getConsumer($request);
        $token = $this->getToken($request, $consumer, "access");
        $this->checkSignature($request, $consumer, $token);
        return array($consumer,$token);
    }
    
    // Internals from here
    /**
     * version 1
     */
    private function getVersion($request)
    {
        $version = $request->getParameter("oauth_version");
        if (! $version) {
            // Service Providers MUST assume the protocol version to be 1.0 if this parameter is not present. 
            // Chapter 7.0 ("Accessing Protected Ressources")
            $version = '1.0';
        }
        if ($version !== $this->version) {
            throw new Zeed_OAuth_Exception("OAuth version '$version' not supported");
        }
        return $version;
    }
    
    /**
     * figure out the signature with some defaults
     */
    private function getSignatureMethod($request)
    {
        $signatureMethod = @$request->getParameter("oauth_signature_method");
        
        if (! $signatureMethod) {
            // According to chapter 7 ("Accessing Protected Ressources") the signature-method
            // parameter is required, and we can't just fallback to PLAINTEXT
            throw new Zeed_OAuth_Exception('No signature method parameter. This parameter is required');
        }
        
        if (! in_array($signatureMethod, array_keys($this->signatureMethods))) {
            throw new Zeed_OAuth_Exception("Signature method '$signatureMethod' not supported " . "try one of the following: " . implode(", ", array_keys($this->signature_methods)));
        }
        return $this->signatureMethods[$signatureMethod];
    }
    
    /**
     * try to find the consumer for the provided request's consumer key
     */
    private function getConsumer($request)
    {
        $consumerKey = @$request->getParameter("oauth_consumer_key");
        if (! $consumerKey) {
            throw new Zeed_OAuth_Exception("Invalid consumer key");
        }
        
        $consumer = $this->dataStore->lookupConsumer($consumerKey);
        if (! $consumer) {
            throw new Zeed_OAuth_Exception("Invalid consumer");
        }
        
        return $consumer;
    }
    
    /**
     * try to find the token for the provided request's token key
     */
    private function getToken($request, $consumer, $tokenType = "access")
    {
        $tokenField = @$request->getParameter('oauth_token');
        $token = $this->dataStore->lookupToken($consumer, $tokenType, $tokenField);
        if (! $token) {
            throw new Zeed_OAuth_Exception("Invalid $tokenType token: $tokenField");
        }
        return $token;
    }
    
    /**
     * all-in-one function to check the signature on a request
     * should guess the signature method appropriately
     */
    private function checkSignature($request, $consumer, $token)
    {
        // this should probably be in a different method
        $timestamp = @$request->getParameter('oauth_timestamp');
        $nonce = @$request->getParameter('oauth_nonce');
        
        $this->checkTimestamp($timestamp);
        $this->checkNonce($consumer, $token, $nonce, $timestamp);
        
        $signatureMethod = $this->getSignatureMethod($request);
        
        $signature = $request->getParameter('oauth_signature');
        $valid_sig = $signatureMethod->checkSignature($request, $consumer, $token, $signature);
        
        if (! $valid_sig) {
            throw new Zeed_OAuth_Exception("Invalid signature");
        }
    }
    
    /**
     * check that the timestamp is new enough
     */
    private function checkTimestamp($timestamp)
    {
        if (! $timestamp)
            throw new Zeed_OAuth_Exception('Missing timestamp parameter. The parameter is required');
            
        // verify that timestamp is recentish
        $now = time();
        if (abs($now - $timestamp) > $this->timestampThreshold) {
            throw new Zeed_OAuth_Exception("Expired timestamp, yours $timestamp, ours $now");
        }
    }
    
    /**
     * check that the nonce is not repeated
     */
    private function checkNonce($consumer, $token, $nonce, $timestamp)
    {
        if (! $nonce)
            throw new Zeed_OAuth_Exception('Missing nonce parameter. The parameter is required');
            
        // verify that the nonce is uniqueish
        $found = $this->dataStore->lookupNonce($consumer, $token, $nonce, $timestamp);
        if ($found) {
            throw new Zeed_OAuth_Exception("Nonce already used: $nonce");
        }
    }

}

// End ^ Native EOL ^ encoding
