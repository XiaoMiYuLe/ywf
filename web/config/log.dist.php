<?php
/**
 * iNewS Project
 *
 * LICENSE
 *
 * http://www.inews.com.cn/license/inews
 *
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      2010-1-19
 * @version    SVN: $Id$
 */

$mailSubject = "Warning: Email Occur Warning";

return array(
        array(
                'writerName' => 'Zeed_Log_Writer_Mail',
                'writerParams' => array(
                        'useExtrasId'           => true,
                        'sendmailDistanceTime'  => 300,
                        'mail'                  => 'Zend_Mail',
                        'charset'               => 'UTF-8',
                        'from'                  => array('email' => 'mail@bluemobi.cn', 'name' => '蓝色互动 - 通用邮件系统'),
                        'to'                    => 'wangl@bluemobi.cn',
                        'cc'                    => array('wulx@bluemobi.sh.cn', 'weiyl@bluemobi.sh.cn'), // 抄送给
                        'subject'               => $mailSubject,
                ),
                'filterName'   => 'Priority',
                'filterParams' => array(
                        'priority' => Zeed_Log::EMERG,
                        'operator' => '==',
                ),
                'formatter' => 'Zend_Log_Formatter_Simple',
                'formatterParam' => '%timestamp% %priorityName% (%priority%): %message%. extras: %info%.'.PHP_EOL,
        ),
        array(
                'writerName' => 'Zeed_Log_Writer_Stream',
                'writerParams' => array(
                        'stream' => ZEED_PATH_DATA . '/log/info.log',
                ),
                'filterName' => 'Priority',
                'filterParams' => array(
                        'priority' => Zeed_Log::INFO,
                        'operator' => '=='
                )
        )
);

// End ^ LF ^ UTF-8
