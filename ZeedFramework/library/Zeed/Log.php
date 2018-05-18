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
 * @package    Zeed_Log
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Log.php 6710 2010-09-08 13:51:53Z xsharp $
 */

/**
 * @todo Zend_Log 的代码又是一陀一陀，需要简化
 * @author Nroe
 */
class Zeed_Log extends Zend_Log
{
    const EMERG   = 0;  // Emergency: system is unusable
    const ALERT   = 1;  // Alert: action must be taken immediately
    const CRIT    = 2;  // Critical: critical conditions
    const ERR     = 3;  // Error: error conditions
    const WARN    = 4;  // Warning: warning conditions
    const NOTICE  = 5;  // Notice: normal but significant condition
    const INFO    = 6;  // Informational: informational messages
    const DEBUG   = 7;  // Debug: debug messages

    public static function instance()
    {
        $logConfig = Zeed_Config::loadGroup('log');
        $log = self::factory($logConfig);
        return $log;
    }

    /**
     * Factory to construct the logger and one or more writers
     * based on the configuration array
     *
     * @param  array|Zend_Config Array or instance of Zend_Config
     * @return Zend_Log
     */
    public static function factory($config = array())
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        }

        if (!is_array($config) || empty($config)) {
            /** @see Zend_Log_Exception */
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception('Configuration must be an array or instance of Zend_Config');
        }

        $log = new Zeed_Log();

        if (!is_array(current($config))) {
            $log->addWriter(current($config));
        } else {
            foreach($config as $writer) {
                $log->addWriter($writer);
            }
        }

        /**
         * 设置 LOG EVENT 变量
         */
        $dateFormat = defined('DATETIME_FORMAT') ? DATETIME_FORMAT : 'Y-m-d H:i:s';
        $log->setEventItem('timestamp', date($dateFormat));

        return $log;
    }

    public function addWriter($writer)
    {
        if (is_array($writer) || $writer instanceof  Zend_Config) {
            $writerInstance = $this->_constructWriterFromConfig($writer);
            /**
             * 设置 Formatting
             */
            if (isset($writer['formatter'])) {
                $formatterParam = isset($writer['formatterParam']) ? $writer['formatterParam'] : null;
                $writerInstance->setFormatter( new $writer['formatter']($formatterParam) );
            }

            $writer = $writerInstance;
        }

        if (!$writer instanceof Zend_Log_Writer_Abstract) {
            /** @see Zend_Log_Exception */
            throw new Zeed_Exception(
                'Writer must be an instance of Zend_Log_Writer_Abstract'
                . ' or you should pass a configuration array'
            );
        }

        $this->_writers[] = $writer;
    }

    /**
     * Get the writer or filter full classname
     *
     * @param array $config
     * @param string $type filter|writer
     * @param string $defaultNamespace
     * @return string full classname
     */
    protected function getClassName($config, $type, $defaultNamespace)
    {
        if (!isset($config[ $type . 'Name' ])) {
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception("Specify {$type}Name in the configuration array");
        }
        $className = $config[ $type . 'Name' ];

        if (strpos($className, 'Zeed_Log_') === 0) {
            return $className;
        }

        $namespace = $defaultNamespace;
        if (isset($config[ $type . 'Namespace' ])) {
            $namespace = $config[ $type . 'Namespace' ];
        }

        $fullClassName = $namespace . '_' . $className;

        return $fullClassName;
    }

    public function getWriters()
    {
        return $this->_writers;
    }

}

// End ^ LF ^ UTF-8
