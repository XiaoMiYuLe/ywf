<?php

/**
 * 退款控制
 *
 * Class PaymentController
 */
class RefundController extends BtsAbstract
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
     * 退款接口
     * @return array
     */
    public function refund()
    {
        header("Content-type:text/html;charset=utf-8");
        $payment_alias = 'Alipay_Refund';
        
        try {
            /* 读取网关配置项 */
            $alipay_config = Zeed_Config::loadGroup('payment.payment_config.' . $payment_alias);
            if (! is_array($alipay_config) || $alipay_config === null) {
                throw new Zeed_Exception('配置文件加载失败');
            }
            
            /* 参数需开发人员自行构造  */
        
            /**************************请求参数(测试数据)**************************/
            
            //服务器异步通知页面路径
            $notify_url = $alipay_config['notify_url'];
            //需http://格式的完整路径，不允许加?id=123这类自定义参数
            
            //卖家支付宝帐户
            $seller_email = $alipay_config['seller_email'];
            //必填
            
            //退款当天日期
            $refund_date = date('Y-m-d H:i:s',time());
            //必填，格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13
            
            //批次号
            $batch_no = date('YmdHis',time()).mt_rand(10000,99999);
            //必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
            
            //退款笔数
            $batch_num = 1;
            //必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
            
            //退款详细数据
            $detail_data = '2015042305396036^0.02^'.'测试';
            //必填，具体格式请参见文档
            
            /************************************************************/
            
            //构造要请求的参数数组，无需改动
            $parameter = array(
                    "service" => "refund_fastpay_by_platform_pwd",
                    "partner" => trim($alipay_config['partner']),
                    "notify_url"	=> $notify_url,
                    "seller_email"	=> $seller_email,
                    "refund_date"	=> $refund_date,
                    "batch_no"	=> $batch_no,
                    "batch_num"	=> $batch_num,
                    "detail_data"	=> $detail_data,
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
        
        /* 请根据业务需求编写代码，以下为范例 */
        if ($verify_result) { // 验证成功
            // 请在这里加上开发业务逻辑程序代
            // ——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            
			// 获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表                 
            // 批次号
            $batch_no = $_POST['batch_no'];
            // 批量退款数据中转账成功的笔数
            $success_num = $_POST['success_num'];
            // 批量退款数据中的详细信息
            $result_details = $_POST['result_details'];
            
            // 判断是否在商户网站中已经做过了这次通知返回的处理
            // 如果没有做过处理，那么执行商户的业务程序
            // 如果有做过处理，那么不执行商户的业务程序
            
			// 请根据业务逻辑自行编写代码，此处仅为示范
			var_dump("批次号为{$batch_no}的退款退款成功，退款笔数:{$success_num}，详情：{$result_details}");
			
            echo "success"; // 请不要修改或删除
                              
        } else {
            // 验证失败
            echo "fail";  //请不要修改或删除
            
            // 此为调试用方法，写文本函数记录程序运行情况是否正常
            logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }
}

// End ^ Native EOL ^ encoding
