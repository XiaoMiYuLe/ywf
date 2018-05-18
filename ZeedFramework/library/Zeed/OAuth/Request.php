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
 * @version    SVN: $Id: Request.php 8235 2010-11-09 03:10:03Z nroe $
 */

class Zeed_OAuth_Request
{
    private $parameters;
    private $httpMethod;
    private $httpUrl;
    // for debug purposes
    public $baseString;
    public static $version = '1.0';
    public static $POST_INPUT = 'php://input';
    private $_error = null;
    private $_info = null;

    function __construct($httpMethod, $httpUrl, $parameters = NULL)
    {
        @$parameters or $parameters = array();
        $parameters = array_merge(Zeed_OAuth_Util::parseParameters(parse_url($httpUrl, PHP_URL_QUERY)), $parameters);
        $this->parameters = $parameters;
        $this->httpMethod = $httpMethod;
        $this->httpUrl = $httpUrl;
    }

    /**
     * attempt to build up a request from what was passed to the server
     *
     * @return Zeed_OAuth_Request
     */
    public static function fromRequest($httpMethod = NULL, $httpUrl = NULL, $parameters = NULL)
    {
        $scheme = (! isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") ? 'http' : 'https';
        @$httpUrl or $httpUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        @$httpMethod or $httpMethod = $_SERVER['REQUEST_METHOD'];

        // We weren't handed any parameters, so let's find the ones relevant to
        // this request.
        // If you run XML-RPC or similar you should use this to provide your own
        // parsed parameter-list
        if (! $parameters) {
            // Find request headers
            $request_headers = Zeed_OAuth_Util::getHeaders();

            // Parse the query-string to find GET parameters
            $parameters = Zeed_OAuth_Util::parseParameters($_SERVER['QUERY_STRING']);

            // It's a POST request of the proper content-type, so parse POST
            // parameters and add those overriding any duplicates from GET
            if ($httpMethod == "POST" && @strstr($request_headers["Content-Type"], "application/x-www-form-urlencoded")) {
                $post_data = Zeed_OAuth_Util::parseParameters(file_get_contents(self::$POST_INPUT));
                $parameters = array_merge($parameters, $post_data);
            }

            // We have a Authorization-header with OAuth data. Parse the header
            // and add those overriding any duplicates from GET or POST
            if (@substr($request_headers['Authorization'], 0, 6) == "OAuth ") {
                $header_parameters = Zeed_OAuth_Util::splitHeader($request_headers['Authorization']);
                $parameters = array_merge($parameters, $header_parameters);
            }

        }

        return new self($httpMethod, $httpUrl, $parameters);
    }

    /**
     * pretty much a helper function to set up the request
     *
     * @return Zeed_OAuth_Request
     */
    public static function fromConsumerAndToken($consumer, $token, $httpMethod, $httpUrl, $parameters = NULL)
    {
        @$parameters or $parameters = array();
        $defaults = array("oauth_version" => self::$version,"oauth_nonce" => self::generateNonce(),
                "oauth_timestamp" => self::generateTimestamp(),
                "oauth_consumer_key" => $consumer->key);
        if ($token)
            $defaults['oauth_token'] = $token->key;

        $parameters = array_merge($defaults, $parameters);

        return new self($httpMethod, $httpUrl, $parameters);
    }

    /**
     * send request to oauth server
     *
     * @param $method
     */
    public function request($method = null)
    {
        $userAgent = 'OAuth API PHP5 Client 0.1 (curl) ' . phpversion();
        $ch = curl_init();
        //$_h = array('Expect:');
        //$_h[] = $this->toHeader();
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $_h);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $this->httpUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->toPostdata());
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->toUrl());
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $result = curl_exec($ch);
        $this->_info = curl_getinfo($ch);
        $this->_error = curl_error($ch);

        if ($result !== false) {
            curl_close($ch);
            return array('code' => $this->_info['http_code'],'body' => $result);
        }

        return false;
    }

    public function getError()
    {
        return $this->_error;
    }

    public function getInfo()
    {
        return $this->_info;
    }

    public function setParameter($name, $value, $allow_duplicates = true)
    {
        if ($allow_duplicates && isset($this->parameters[$name])) {
            // We have already added parameter(s) with this name, so add to the list
            if (is_scalar($this->parameters[$name])) {
                // This is the first duplicate, so transform scalar (string)
                // into an array so we can add the duplicates
                $this->parameters[$name] = array(
                        $this->parameters[$name]);
            }

            $this->parameters[$name][] = $value;
        } else {
            $this->parameters[$name] = $value;
        }
    }

    public function getParameter($name)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function unsetParameter($name)
    {
        unset($this->parameters[$name]);
    }

    /**
     * The request parameters, sorted and concatenated into a normalized string.
     * @return string
     */
    public function getSignableParameters()
    {
        // Grab all parameters
        $params = $this->parameters;

        // Remove oauth_signature if present
        // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
        if (isset($params['oauth_signature'])) {
            unset($params['oauth_signature']);
        }

        return Zeed_OAuth_Util::buildHttpQuery($params);
    }

    /**
     * Returns the base string of this request
     *
     * The base string defined as the method, the url
     * and the parameters (normalized), each urlencoded
     * and the concated with &.
     */
    public function getSignatureBaseString()
    {
        $parts = array($this->getNormalizedHttpMethod(),$this->getNormalizedHttpUrl(),$this->getSignableParameters());

        $parts = Zeed_OAuth_Util::urlencode_rfc3986($parts);

        return implode('&', $parts);
    }

    /**
     * just uppercases the http method
     */
    public function getNormalizedHttpMethod()
    {
        return strtoupper($this->httpMethod);
    }

    /**
     * parses the url and rebuilds it to be
     * scheme://host/path
     */
    public function getNormalizedHttpUrl()
    {
        $parts = parse_url($this->httpUrl);

        $port = @$parts['port'];
        $scheme = $parts['scheme'];
        $host = $parts['host'];
        $path = @$parts['path'];

        $port or $port = ($scheme == 'https') ? '443' : '80';

        if (($scheme == 'https' && $port != '443') || ($scheme == 'http' && $port != '80')) {
            $host = "$host:$port";
        }
        return "$scheme://$host$path";
    }

    /**
     * builds a url usable for a GET request
     */
    public function toUrl()
    {
        $post_data = $this->toPostdata();
        $out = $this->getNormalizedHttpUrl();
        if ($post_data) {
            $out .= '?' . $post_data;
        }
        return $out;
    }

    /**
     * builds the data one would send in a POST request
     */
    public function toPostdata()
    {
        return Zeed_OAuth_Util::buildHttpQuery($this->parameters);
    }

    /**
     * builds the Authorization: header
     */
    public function toHeader($realm = null)
    {
        $first = true;
        if ($realm) {
            $out = 'Authorization: OAuth realm="' . Zeed_OAuth_Util::urlencode_rfc3986($realm) . '"';
            $first = false;
        } else
            $out = 'Authorization: OAuth';

        $total = array();
        foreach ($this->parameters as $k => $v) {
            if (substr($k, 0, 5) != "oauth")
                continue;
            if (is_array($v)) {
                throw new Zeed_OAuth_Exception('Arrays not supported in headers');
            }
            $out .= ($first) ? ' ' : ',';
            $out .= Zeed_OAuth_Util::urlencode_rfc3986($k) . '="' . Zeed_OAuth_Util::urlencode_rfc3986($v) . '"';
            $first = false;
        }
        return $out;
    }

    public function __toString()
    {
        return $this->to_url();
    }

    public function signRequest($signature_method, $consumer, $token)
    {
        $this->setParameter("oauth_signature_method", $signature_method->getName(), false);
        $signature = $this->buildSignature($signature_method, $consumer, $token);
        $this->setParameter("oauth_signature", $signature, false);
    }

    public function buildSignature($signature_method, $consumer, $token)
    {
        $signature = $signature_method->buildSignature($this, $consumer, $token);
        return $signature;
    }

    /**
     * util function: current timestamp
     */
    private static function generateTimestamp()
    {
        return time();
    }

    /**
     * util function: current nonce
     */
    private static function generateNonce()
    {
        $mt = microtime();
        $rand = mt_rand();

        return md5($mt . $rand); // md5s look nicer than numbers
    }
}

// End ^ Native EOL ^ encoding
