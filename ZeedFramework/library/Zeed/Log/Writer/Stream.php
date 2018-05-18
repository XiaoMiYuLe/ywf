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
 * @since      2010-11-9
 * @version    SVN: $Id$
 */

/** Zend_Log_Writer_Abstract */
require_once 'Zend/Log/Writer/Abstract.php';

/** Zend_Log_Formatter_Simple */
require_once 'Zend/Log/Formatter/Simple.php';

/**
 * 每日生成一个日志文件
 * 
 * @author Nroe
 *
 */
class Zeed_Log_Writer_Stream extends Zend_Log_Writer_Abstract
{
    /**
     * Holds the PHP stream to log to.
     * @var null|stream
     */
    protected $_stream = null;

    /**
     * Class Constructor
     *
     * @param  streamOrUrl     Stream or URL to open as a stream
     * @param  mode            Mode, only applicable if a URL is given
     */
    public function __construct($streamOrUrl, $mode = NULL)
    {
        // Setting the default
        if ($mode === NULL) {
            $mode = 'a';
        }

        if (is_resource($streamOrUrl)) {
            if (get_resource_type($streamOrUrl) != 'stream') {
                require_once 'Zend/Log/Exception.php';
                throw new Zend_Log_Exception('Resource is not a stream');
            }

            if ($mode != 'a') {
                require_once 'Zend/Log/Exception.php';
                throw new Zend_Log_Exception('Mode cannot be changed on existing streams');
            }

            $this->_stream = $streamOrUrl;
        } else {
            if (is_array($streamOrUrl) && isset($streamOrUrl['stream'])) {
                $streamOrUrl = $streamOrUrl['stream'];
            }

            /**
             * 添加日期
             */
            $extension = Zeed_Util::fileExtension($streamOrUrl);
            if (strlen($extension) > 0) {
                $pos = strrpos($streamOrUrl, $extension);
                $streamOrUrl = $streamOrUrlAddDate = substr($streamOrUrl, 0, $pos) . date('Y-m-d') . '.' . $extension;
            } else {
                $streamOrUrl = $streamOrUrlAddDate = $streamOrUrl . date('Y-m-d');
            }

            if (! $this->_stream = @fopen($streamOrUrl, $mode, false)) {
                require_once 'Zend/Log/Exception.php';
                $msg = "\"$streamOrUrl\" cannot be opened with mode \"$mode\"";
                throw new Zend_Log_Exception($msg);
            }

            /**
             * 修正当 CRONTAB 下执行用户为 ROOT 等超级用户时，创建日志文件
             * 导致 WEB 服务器的执行用户 WWW NOBODY 无权限写日志
             */
            @chmod($streamOrUrl, 0664);
        }

        $this->_formatter = new Zend_Log_Formatter_Simple();
    }
    
    /**
     * Create a new instance of Zend_Log_Writer_Mock
     * 
     * @param  array|Zend_Config $config
     * @return Zend_Log_Writer_Mock
     * @throws Zend_Log_Exception
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $config = array_merge(array(
            'stream' => null, 
            'mode'   => null,
        ), $config);

        $streamOrUrl = isset($config['url']) ? $config['url'] : $config['stream']; 
        
        return new self(
            $streamOrUrl, 
            $config['mode']
        );
    }
    
    /**
     * Close the stream resource.
     *
     * @return void
     */
    public function shutdown()
    {
        if (is_resource($this->_stream)) {
            fclose($this->_stream);
        }
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        $line = $this->_formatter->format($event);

        if (false === @fwrite($this->_stream, $line)) {
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception("Unable to write to stream");
        }
    }
}

// End ^ Native EOL ^ encoding
