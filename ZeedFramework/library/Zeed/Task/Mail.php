<?php
/**
 * Playcool Project
 *
 * LICENSE
 *
 * http://www.playcool.com/license/ice
 *
 * @category   ICE
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      2009-11-20
 * @version    SVN: $Id$
 */

class Zeed_Task_Mail extends Zeed_Task
{

    /**
     * 最大允许执行时间(单位:秒)
     */
    const QUEUE_RUNTIMEMAX = 300;

    public function daemon()
    {
        while (true) {
            $i = 0;
            while ($this->fetchNextMailqueue()) {
                $this->send();
                $i ++;

                sleep(1);
            }

            // 清理队列
            $this->cleanupTask();

            Zeed_Benchmark::println('finished all sleep 5 secs :)');
            sleep(5);
        }
    }

    protected function fetchNextMailqueue()
    {
        $this->_task = Com_Model_Mailqueue::instance()->getOneMailqueue();

        return is_array($this->_task) ? true : false;
    }

    protected function send()
    {
        // 设置正在执行状态
        $data = $this->_task;

        $charset = ! empty($data['charset']) ? $data['charset'] : 'UTF-8';
        $_mail = new Zend_Mail($charset);
        $_mail->addTo($data['to']);
        if (! empty($data['bcc'])) {
            $_mail->addBcc($data['bcc']);
        }

        if (empty($data['bodyhtml'])) {
            $data['bodyhtml'] = strip_tags($data['bodytext']);
        }

        $_mail->setSubject($data['subject']);
        $_mail->setBodyText($data['bodytext']);
        $_mail->setBodyHtml($data['bodyhtml']);

        /**
         * 查看是否设置了附件
         */
        if ( ! empty($data['attachment'])) {
            $attachments = unserialize($data['attachment']);
            if ( is_array($attachments) ) {
                foreach ($attachments as $at) {
                    if ( isset($at['data']) ) {
                        $atType = isset($at['type']) ? $at['type'] : Zend_Mime::TYPE_OCTETSTREAM;
                        $atFilename = isset($at['filename']) ? $at['filename'] : null;
                        $_mail->createAttachment(base64_decode($at['data']), $atType, Zend_Mime::DISPOSITION_ATTACHMENT, Zend_Mime::ENCODING_BASE64, $atFilename);
                    }
                }
            }
        }

        $_from_address = ! empty($this->_mailConfig['from_address']) ? $this->_mailConfig['from_address'] : null;
        $_from_username = ! empty($this->_mailConfig['from_username']) ? $this->_mailConfig['from_username'] : null;
        $_mail->setFrom($_from_address, $_from_username);

        try {
            echo "Sending to " . $data['to'] . '...';
            $_mail->send($this->getMailTransport());
            Zeed_Benchmark::println(" OK.");
            // 设置执行成功状态
            $this->updateTaskStatus($data['id'], parent::QUEUE_STATUS_SUCCESS);
        } catch (Zend_Mail_Protocol_Exception $e) {
            Zeed_Benchmark::print_r($e->getMessage());
            // 设置执行失败状态
            $this->updateTaskStatus($data['id'], parent::QUEUE_STATUS_FAIL);
            Com_Model_Mailqueue::instance()->sendFailed($data['id']);
        } catch (Exception $e) {
            Zeed_Benchmark::print_r($e->getMessage());
            // 设置执行失败状态
            $this->updateTaskStatus($data['id'], parent::QUEUE_STATUS_FAIL);
            Com_Model_Mailqueue::instance()->sendFailed($data['id']);
        }
    }

    protected $_mailTransport;
    protected function getMailTransport()
    {
        if ($this->_mailTransport instanceof Zend_Mail_Transport_Abstract) {
            return $this->_mailTransport;
        }

        $conf = array();
        if (! empty($this->_mailConfig['smtp_host'])) {
            $conf['host'] = $conf['name'] = $this->_mailConfig['smtp_host'];

            if (! empty($this->_mailConfig['smtp_port'])) {
                $conf['port'] = $this->_mailConfig['smtp_port'];
            }
            if (! empty($this->_mailConfig['smtp_auth'])) {
                $conf['auth'] = $this->_mailConfig['smtp_auth'];
            }
            if (! empty($this->_mailConfig['smtp_user'])) {
                $conf['username'] = $this->_mailConfig['smtp_user'];
            }
            if (! empty($this->_mailConfig['smtp_pass'])) {
                $conf['password'] = $this->_mailConfig['smtp_pass'];
            }
        }

        if (! empty($this->_mailConfig['ssl'])) {
            $conf['ssl'] = $this->_mailConfig['ssl'];
        }

        if ($conf['host']) {
            $this->_mailTransport = new Zend_Mail_Transport_Smtp($conf['host'], $conf);
        } else {
            $this->_mailTransport = new Zend_Mail_Transport_Sendmail();
        }

        return $this->_mailTransport;
    }

    protected $_mailConfig;
    public function setMailServer($config)
    {
        $this->_mailConfig = $config;
    }

    protected function updateTaskStatus($id, $status)
    {
        return Com_Model_Mailqueue::instance()->updateStatusById($id, $status);
    }

    protected function deleteTask($id)
    {
        return true;
    }

    protected function cleanupTask()
    {
        return true;
    }

}

// End ^ LF ^ UTF-8
