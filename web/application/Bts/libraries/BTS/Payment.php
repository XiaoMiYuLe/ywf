<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * BTS - Billing Transaction Service
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-7-22
 * @version    SVN: $Id$
 */

class BTS_Payment
{
    const HTTP_NAME_KEY = 'pn';

    /**
     * 网关接口名称
     * 必须定义
     *
     * @overwirte
     */
    public $name = '';

    public $version = 'unknown';
    
    /**
     * 网关基本配置
     *
     * @var username 网关帐号
     * @var password 网关密码
     * @var certificate 证书
     * @var gateway 支付接口地址
     * @var merchant_url 支付回调地址
     * @var fail_url 支付失败回调地址
     * @var error_url 支付出错回调地址
     */
    protected $_options = array(
            'username' => '',
            'passsword' => '',
            'certificate' => '',
            'gateway' => '',
            'merchant_url' => '',
            'fail_url' => '',
            'error_url' => '');

    /**
     * 网关配置
     *
     * @var array $_specificOptions
     */
    protected $_specificOptions = array();

    /**
     * 网关回调地址
     *
     * @var array $_callbackUrl
     */
    protected $_callbackUrl = array();

    /**
     *
     * @var Zeed_Request_Http
     */
    protected $_http = null;

    /**
     * Constructor
     *
     * @param  array $options 支付网关配置
     * @throws Zeed_Exception
     * @return void
     */
    public function __construct($options = array())
    {
        if (! is_array($options)) {
            throw new Zeed_Exception("参数必须为数组");
        }

        while (@list($name, $value) = each($options)) {
            $this->setOption($name, $value);
        }
       
    }
    
    /**
     * 根据$payment_alias请求支付接口
     * @param array $parameter
     * @param string $method
     * @param string $payment_alias
     * @param array $alipay_config
     * @return array $errMsg
     */
    public static function createAlipay($parameter, $method, $payment_alias, $alipay_config)
    {
        $errMsg = '建立请求失败';
        
        try {
            /* 容错处理 */
            if ( ! is_array($parameter) || ! is_array($alipay_config) || ! is_string($method) || ! is_string($payment_alias)){
                throw new Zeed_Exception('请求参数错误');
            }
            
            /* 实例化相应接口的对象 */
            $alipaySubmit = self::payment($payment_alias, $alipay_config);
                
                /* 建立请求 */
            $html_text = $alipaySubmit->buildRequestForm($parameter, $method, "确认");
            echo $html_text;
            exit;
        } catch (Zeed_Exception $e) {
            $errMsg = $e->getMessage();
        }
        return $errMsg;
    }
    
    /**
     * 设置网关配置
     *
     * @param  string $name  Name of the option
     * @param  mixed  $value Value of the option
     * @throws Zeed_Exception
     * @return void
     */
    public function setOption($name, $value)
    {
        $paymentName = strtolower($name);

        if (array_key_exists($paymentName, $this->_options)) {
            $this->_options[$paymentName] = $value;
            return;
        }

        /**
         * 网关配置
         */
        if (array_key_exists($name, $this->_specificOptions)) {
            // This a specic option of this frontend
            $this->_specificOptions[$name] = $value;
            return;
        }
        
        throw new Zeed_Exception("Incorrect option name : {$name} at " . __METHOD__);
    }

    /**
     * 获取指定网关
     *
     * @throws Zeed_Exception
     * @param  string $name
     * @return BTS_Payment
     */
    public static function payment($name, $options = array())
    {
        $paymentClass = 'BTS_Payment_' . self::_normalizeName($name);
        if (! class_exists($paymentClass)) {
            throw new Zeed_Exception('payment "' . $name . '" can not found');
        }
        return new $paymentClass($options);
    }

    /**
     * 格式化方法名
     * @param  string $name  Name to normalize
     * @return string
     */
    protected static function _normalizeName($name)
    {
        $name = ucfirst($name);
        return $name;
    }
    
    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key_path 商户私钥文件路径
     * return 签名结果
     */
    function rsaSign($data, $private_key_path) {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }
    
    /**
     * RSA验签
     * @param $data 待签名数据
     * @param $ali_public_key_path 支付宝的公钥文件路径
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    function rsaVerify($data, $ali_public_key_path, $sign)  {
        $pubKey = file_get_contents($ali_public_key_path);
        $res = openssl_get_publickey($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }
    
    /**
     * RSA解密
     * @param $content 需要解密的内容，密文
     * @param $private_key_path 商户私钥文件路径
     * return 解密后内容，明文
     */
    function rsaDecrypt($content, $private_key_path) {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        //用base64将内容还原成二进制
        $content = base64_decode($content);
        //把需要解密的内容，按128位拆开解密
        $result  = '';
        for($i = 0; $i < strlen($content)/128; $i++  ) {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }
    
    /**
     * MD5签名字符串
     * @param $prestr 需要签名的字符串
     * @param $key 私钥
     * return 签名结果
     */
    function md5Sign($prestr, $key) {
        $prestr = $prestr . $key;
        return md5($prestr);
    }
    
    /**
     * MD5验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    function md5Verify($prestr, $sign, $key) {
        $prestr = $prestr . $key;
        $mysgin = md5($prestr);
    
        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 远程获取数据，GET模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * return 远程输出的数据
     */
    function getHttpResponseGET($url,$cacert_url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
    
        return $responseText;
    }
    

    /**
     * 远程获取数据，POST模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * @param $para 请求的数据
     * @param $input_charset 编码格式。默认值：空值
     * return 远程输出的数据
     */
    function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '')
    {
        if (trim($input_charset) != '') {
            $url = $url."_input_charset=".$input_charset;
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl,CURLOPT_POST,true); // post传输数据
        curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
    
        return $responseText;
    }
    
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    function createLinkstring($para)
    {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
            
            // 如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }
    
        return $arg;
    }
    
    /**
     * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
     * 注意：服务器需要开通fopen配置
     * @param $word 要写入日志里的文本内容 默认值：空值
     */
    function logResult($word='')
    {
        $fp = fopen("log.txt","a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /**
     * 除去数组中的空值和签名参数
     * 
     * @param $para 签名参数组
     *            return 去掉空值与签名参数后的新签名参数组
     */
    function paraFilter ($para)
    {
        $para_filter = array();
        while (list ($key, $val) = each($para)) {
            if ($key == "sign" || $key == "sign_type" || $val == "") {
                continue;
            } else {
                $para_filter[$key] = $para[$key];
            }
        }
        return $para_filter;
    }
    
    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }

}

// End ^ Native EOL ^ encoding
