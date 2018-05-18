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
 * @since      Aug 7, 2015
 * @version    SVN: $Id: SendHook.php 2015-08-07 14:41:38 Cyrano $
 */

/**
 * 发送邮件
 */
class Hooks_Mail_SendHook
{
    public static function run()
    {
        $mailConfig = Zeed_Config::loadGroup('mail');
        
        $data = Zeed_Hook::$data['data'];
        
        $charset = ! empty($data['charset']) ? $data['charset'] : 'UTF-8';
        $mail = new Zend_Mail($charset);
        $mail->addTo($data['to']);
        if (! empty($data['bcc'])) {
            $mail->addBcc($data['bcc']);
        }
    
        /**
         * 确保文本格式的邮件回车全部是 \n
         */
        $data['bodytext'] = str_replace(array("\r\n", "\r", "\n"), "<br />", $data['bodytext']);
        $data['bodytext'] = str_replace('<br />', "\n", $data['bodytext']);
    
        $mail->setSubject($data['subject']);
        $mail->setBodyText($data['bodytext']);
    
        if ($data['bodyhtml'] && strlen($data['bodyhtml'])) {
            $mail->setBodyHtml($data['bodyhtml']);
        }
    
        $_from_address = ! empty($mailConfig['from_address']) ? $mailConfig['from_address'] : null;
        $_from_username = ! empty($mailConfig['from_username']) ? $mailConfig['from_username'] : null;
        $mail->setFrom($_from_address, $_from_username);
    
        $mailTransport = self::_getMailTransport();
        $mail->send($mailTransport);
        unset($mailTransport);
    }
    
    protected static function _getMailTransport()
    {
        $mailConfig = Zeed_Config::loadGroup('mail');
    
        $conf = array();
        $mailTransport = null;
    
        if (! empty($mailConfig['smtp_host'])) {
            $conf['host'] = $conf['name'] = $mailConfig['smtp_host'];
    
            if (! empty($mailConfig['smtp_port'])) {
                $conf['port'] = $mailConfig['smtp_port'];
            }
            if (! empty($mailConfig['smtp_auth'])) {
                $conf['auth'] = $mailConfig['smtp_auth'];
            }
            if (! empty($mailConfig['smtp_user'])) {
                $conf['username'] = $mailConfig['smtp_user'];
            }
            if (! empty($mailConfig['smtp_pass'])) {
                $conf['password'] = $mailConfig['smtp_pass'];
            }
        }
    
        if (! empty($mailConfig['ssl'])) {
            $conf['ssl'] = $mailConfig['ssl'];
        }
    
        if ($conf['host']) {
            $mailTransport = new Zend_Mail_Transport_Smtp($conf['host'], $conf);
        } else {
            $mailTransport = new Zend_Mail_Transport_Sendmail();
        }
    
        return $mailTransport;
    }
}

// End ^ Native EOL ^ encoding
