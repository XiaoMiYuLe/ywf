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
 * @since      2010-11-12
 * @version    SVN: $Id$
 */

class Zeed_Log_Writer_Mail extends Zend_Log_Writer_Mail
{
    /**
     * 使用 Extras 作为标志来发送邮件
     * 
     * @var $_useExtrasId boolean
     */
    protected $_useExtrasId = true;

    /**
     * 当设置 $_useExtrasId 为 true 时，设置发送邮件间隔
     * 
     * @var $_sendmailDistanceTime integer
     */
    protected $_sendmailDistanceTime = 300;

    /**
     * Create a new instance of Zend_Log_Writer_Mail
     *
     * @param  array|Zend_Config $config
     * @return Zend_Log_Writer_Mail
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $mail = self::_constructMailFromConfig($config);
        $writer = new self($mail);

        if (isset($config['layout']) || isset($config['layoutOptions'])) {
            $writer->setLayout($config);
        }
        if (isset($config['layoutFormatter'])) {
            $layoutFormatter = new $config['layoutFormatter'];
            $writer->setLayoutFormatter($layoutFormatter);
        }
        if (isset($config['subjectPrependText'])) {
            $writer->setSubjectPrependText($config['subjectPrependText']);
        }

        if (isset($config['useExtrasId'])) {
            $writer->_useExtrasId = (bool) $config['useExtrasId'];
        }

        if (isset($config['sendmailDistanceTime'])) {
            $writer->_sendmailDistanceTime = (int) $config['sendmailDistanceTime'];
        }

        return $writer;
    }


    /**
     * Places event line into array of lines to be used as message body.
     *
     * Handles the formatting of both plaintext entries, as well as those
     * rendered with Zend_Layout.
     *
     * @param  array $event Event data
     * @return void
     */
    protected function _write($event)
    {
        if ($this->_useExtrasId) {
            if (empty($event['info'])) {
                throw new Zeed_Exception('must defined $extras message for log when set mail use extras for id.');
            }

            $id = __CLASS__.md5($event['info']);

            $cache = Zeed_Cache::instance();
            if (($data = $cache->load($id)) && $data) {
                return;
            }

            $cache->save(time(), $id, array(), $this->_sendmailDistanceTime);
        }

        return parent::_write($event);
    }
}


// End ^ Native EOL ^ encoding
