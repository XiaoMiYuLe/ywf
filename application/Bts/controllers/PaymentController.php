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
 * @since      2010-7-23
 * @version    SVN: $Id$
 */
class PaymentController extends BtsAbstract
{

    /**
     * 网关变换表配置
     *
     * @var array $_mappingOptions
     */
    protected $_mappingOptions = array();

    /**
     * 网关变换表配置-返回
     *
     * @var array $_mappingOptionsReturn
     */
    protected $_mappingOptionsReturn = array();
    

    /**
     * 支付类型为支付宝
     */
    const PAYMENT_ID = 1;

    
    /**
     * 手机网站支付接口
     */
    public function wapDirect ()
    {
        header("Content-type:text/html;charset=utf-8");
        $payment_alias = 'Alipay_Mobile';

        try {
            /* 读取网关配置项 */
            $alipay_config = Zeed_Config::loadGroup('payment.payment_config.' . $payment_alias);
            if (! is_array($alipay_config) || $alipay_config === null) {
                throw new Zeed_Exception('配置文件加载失败');
            }
            
            /* 参数需开发人员自行构造  */
            
            
            /**************************请求参数(测试数据)**************************/
            
            /* 以下是必填信息 */
            
            //支付类型 - 不能修改
            $payment_type = self::PAYMENT_ID;
             
            //服务器异步通知页面路径 - 需http://格式的完整路径，不能加?id=123这类自定义参数
            $notify_url = $alipay_config['notify_url'];
            
            //页面跳转同步通知页面路径 - 需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
            $return_url = $alipay_config['return_url'];
            
            //商户订单号 - 商户网站订单系统中唯一订单号，必填
            $out_trade_no = '70501111111S001111110';
            
            //订单名称
            $subject = '测试订单';
            
            //付款金额
            $total_fee = '0.01';
            
            //商品展示地址 - 需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
            $urlmapping = Zeed_Config::loadGroup('urlmapping');
            $show_url = $urlmapping['store_url'];
            
            /* 以下是选填信息 */
    
            //订单描述
            $body = '测试数据';
            
            //超时时间  ： $it_b_pay
            
            //钱包token ： $extern_token;
            
            /************************************************************/
            
            //构造要请求的参数数组  - 无需改动
            $parameter = array(
                    "service" => "alipay.wap.create.direct.pay.by.user",
                    "partner" => trim($alipay_config['partner']),
                    "seller_id" => trim($alipay_config['seller_id']),
                    "payment_type"	=> $payment_type,
                    "notify_url"	=> $notify_url,
                    "return_url"	=> $return_url,
                    "out_trade_no"	=> $out_trade_no,
                    "subject"	=> $subject,
                    "total_fee"	=> $total_fee,
                    "show_url"	=> $show_url,
                    "body"	=> $body,
                    "it_b_pay"	=> $it_b_pay,
                    "extern_token"	=> $extern_token,
                    "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
            );
            
            
            /* 提交方式 */
            $method = 'get';
            
            /* 调用请求接口公共方法  */
            if ($errMsg = BTS_Payment::createAlipay($parameter, $method, $payment_alias, $alipay_config)) {
                throw new Zeed_Exception($errMsg);
            }
        } catch (Zeed_Exception $e) {
            /* 错误处理机制 */
            return $this->_commonPrompt(
                    array(
                            'title' => '友情提示',
                            'msg' => '请求支付宝失败  - '. $e->getMessage(),
                            'url' => '/'
                    ));
        }
    }


    /**
     * 同步通知
     */
    public function callback ()
    {
        $payment_alias = $this->input->get('pma');
        
        /* 读取网关配置项 */
        $alipay_config = Zeed_Config::loadGroup('payment.payment_config.' . $payment_alias);
        if (! is_array($alipay_config) || $alipay_config === null) {
            throw new Zeed_Exception('配置文件加载失败');
        }
        
        /* 实例化异步通知对象 */
        $alipayNotify = BTS_Payment::payment('Alipay_Notify');
        /* 验证信息 */
        $verify_result = $alipayNotify->verifyNotify();
        
        /* 请根据业务需求编写代码，以下为范例  */
        if($verify_result) { //验证成功
            
			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];
			//支付宝交易号
			$trade_no = $_GET['trade_no'];
			//交易状态
			$trade_status = $_GET['trade_status'];
        
            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
				 echo "订单号为：{$out_trade_no}，交易号为{$trade_no}的订单已交易成功<br />";
            }
            else {
                echo "trade_status = ".$trade_status;
            }
        }
        else { //验证失败
            echo "验证失败";
        }
    }


    /**
     * 通用异步通知
     */
    public function notify ()
    {   
       $payment_alias = $this->input->get('pma');
       
       /* 读取网关配置项 */
       $alipay_config = Zeed_Config::loadGroup('payment.payment_config.' . $payment_alias);
       if (! is_array($alipay_config) || $alipay_config === null) {
           throw new Zeed_Exception('配置文件加载失败');
       }
       
        /* 实例化异步通知对象 */
       $alipayNotify = BTS_Payment::payment('Alipay_Notify');
       /* 验证信息 */
       $verify_result = $alipayNotify->verifyNotify();
       
       /* 请根据业务需求编写代码，以下为范例  */
       if ($verify_result) { //验证成功
           
           //商户订单号 
           $out_trade_no = $_POST['out_trade_no'];
           //支付宝交易号
           $trade_no = $_POST['trade_no'];
           //交易状态
           $trade_status = $_POST['trade_status'];
       
           if($trade_status == 'TRADE_FINISHED') {
               //判断该笔订单是否在商户网站中已经做过处理
               //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
               //如果有做过处理，不执行商户的业务程序
               
               //注意：
               //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

			   var_dump("订单号为：{$out_trade_no}，交易号为{$trade_no}的订单已交易完成<br />");
           } else if ($trade_status == 'TRADE_SUCCESS') {
               //判断该笔订单是否在商户网站中已经做过处理
               //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
               //如果有做过处理，不执行商户的业务程序
               
               //注意：
               //付款完成后，支付宝系统发送该交易状态通知
               
               //调试用，写文本函数记录程序运行情况是否正常
			   var_dump("订单号为：{$out_trade_no}，交易号为{$trade_no}的订单已交易成功<br />");
           }
       
           /* 不要修改或删除 */
           echo "success";		
       
       } else { //验证失败
           echo "fail"; 
       
           //此为调试用方法，写文本函数记录程序运行情况是否正常
           logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
       }
       
    }

}
// End ^ Native EOL ^ encoding
