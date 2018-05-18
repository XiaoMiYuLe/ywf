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
 * @since      2010-9-2
 * @version    SVN: $Id$
 */


class Zeed_Util_Benchmark {
    /**
     * 解释器信息
     *
     * @var string
    */
    protected $_message = '';

    protected $_prevMessages = '';

    /*
     * 消息堆栈，用来存储嵌套的消息
    */
    protected $_content = array();

    /**
     * 解释标题
     *
     * @var string
    */
    protected $_messageTitle = array();

    protected $_memoryBefore = array();

    protected $_timeBefore = array();

    protected $_timeTotal = 0;

    protected $_explain = true;

    /**
     * 计时器对象容器
     *
     * @var object
    */
    protected $_timer = null;

    private $_timeStart = null;

    /**
     * 解释器对象容器
     *
     * @var object
    */
    private static $_instance = null;

    /**
     * 禁止加载器实例化
    */
    private function __construct() {
        $this->_timer = new Zeed_Util_Timer(false);

        $this->_timeStart = microtime();

        $head = '
                <style type="text/css">
                <!--
                body {
                        background:#FFFFFF;
                        color: #000000;
                        font-family:Verdana, Arial, Helvetica, sans-serif, "宋体";
                        font-size:12px;
                        line-height:1.8em;
                        text-align:left;
                        margin:30px;
                        padding:0;
                }
                div.explain { border: 1px solid #000000; margin-bottom: 16px; }
                div.explaintitle { color: black; background-color: white; padding: 4px; border-bottom: 1px solid #000000; }
                div.explainbody { padding: 8px; color: black; background-color: white; }
                -->
                </style>
        ';
        /*
         * 初始化时先把头部压入栈中
        */
        array_push($this->_content, $head);
    }

    /**
     * 克隆解释器
    */
    private function __clone() {
    }

    protected function output($str) {
            $this->_message .= $str;
    }

    /**
     * 获取注册表实例对象
     *
     * <code>
     *      $__instance = getInstance();
     * </code>
     *
     * @return      object  core_registry
    */
    public static function getInstance() {
        if (null === self::$_instance) {
                self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function start($message = '') {
//                 $colors = array('#E54646','#EE7C6B','#F5A89A','#FCDAD5');
//                 static $i = 150, $j = 0, $k = 0;
//                 $i += 10;
//                 $j += 1;
//                 $k += 30;
        /*
         * 消息开始时把消息头压入栈中
        */
        array_push($this->_content,
            $this->_prevMessages .
            '<div class="explain"><div class="explaintitle">' . $message . '</div><div class="explainbody" style="background:' . sprintf('#%02x%02x%02x', mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)) . '">');
//                 array_push($this->_content,
//                         $this->_prevMessages .
//                         '<div class="explain"><div class="explaintitle">' . $message . '</div><div class="explainbody" style="background:' . $colors[mt_rand(0,3)] . '">');

        $this->_prevMessages = '';

        if (function_exists('memory_get_usage')) {
                $this->_memoryBefore[] = memory_get_usage();
        }

        /*
         * 计数器重设
        */
        $this->_timer->reset();
        $this->_timeBefore[] = $this->_timer->start();
    }

    public function stop($add_total = true) {
            $time_after = $this->_timer->stop();

            $timestart = (!defined('TIMESTART')) ? $this->_timeStart : TIMESTART;
            $pagestart = explode(' ', $timestart);
            $pagestart = $pagestart[0] + $pagestart[1];

            $time_before = array_pop($this->_timeBefore);
            $time_before = $time_before - $pagestart;

            $time_after = $time_after - $pagestart;
            $time_taken = $time_after - $time_before;

            if ($add_total) {
                    $this->_timeTotal += $time_taken;
            }

            $this->output("<p>Time Before: " . number_format($time_before, 5) . " seconds<br />");
            $this->output("Time After: " . number_format($time_after, 5) . " seconds<br />");
            $this->output("<strong>Time Taken: " . number_format($time_taken, 5) . " seconds</strong></p>");

            if (function_exists('memory_get_usage')) {
                    $memory_before = array_pop($this->_memoryBefore);
                    $memory_after = memory_get_usage();

                    $this->output("<p>Memory Before: " . number_format($memory_before / 1024, 3) . " KB<br />");
                    $this->output("Memory After: " . number_format($memory_after / 1024, 3) . " KB<br />");
                    $this->output("<strong>Memory Used: " . number_format(($memory_after - $memory_before) / 1024, 3) . " KB</strong></p>");
            }

            /*
             * 结束时把消息出栈，存入到_prevMessages中
            */
            $this->_prevMessages =
                    array_pop($this->_content) .
                    $this->_prevMessages . $this->_message . '</div></div>';

            $this->_message = '';

            /**
             * todo 这个判断有些问题，如果有两个系统需要benchmark,会出错
            */
            if (count($this->_content) == 1) {
                    echo array_pop($this->_content) . $this->_prevMessages;
            }
    }
}

// End ^ Native EOL ^ encoding
