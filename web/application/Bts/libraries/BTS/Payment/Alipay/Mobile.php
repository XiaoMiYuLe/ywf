<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
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
class BTS_Payment_Alipay_Mobile extends BTS_Payment_Alipay
{

    var $alipay_config;

    /**
     * 支付宝网关地址（新）
     */
    var $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

    function __construct ($alipay_config)
    {
        $this->alipay_config = $alipay_config;
    }

    function AlipaySubmit ($alipay_config)
    {
        $this->__construct($alipay_config);
    }
    

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    function buildRequestForm($para_temp, $method, $button_name) {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);
    
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->alipay_gateway_new."_input_charset=".trim(strtolower($this->alipay_config['input_charset']))."' method='".$method."'>";
        while (list ($key, $val) = each ($para)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
    
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
    
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
    
        return $sHtml;
    }
    
    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    function buildRequestPara($para_temp) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);
    
        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);
    
        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);
    
        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));
    
        return $para_sort;
    }
    
    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    function buildRequestMysign($para_sort) {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
    
        $mysign = "";
        switch (strtoupper(trim($this->alipay_config['sign_type']))) {
        	case "RSA" :
        	    $mysign = $this->rsaSign($prestr, $this->alipay_config['private_key_path']);
        	    break;
        	default :
        	    $mysign = "";
        }
    
        return $mysign;
    }
    
}

// End ^ Native EOL ^ encoding
