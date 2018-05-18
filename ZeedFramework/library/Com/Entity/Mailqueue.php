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
 * @since      May 26, 2010
 * @version    SVN: $Id$
 */

class Com_Entity_Mailqueue extends Zeed_Object
{
    public $id;
    public $to;
    public $cc;
    public $bcc;
    public $subject;
    public $bodytext; // 纯文本邮件内容
    public $bodyhtml; // HTML格式邮件内容
    public $from;
    public $reply;
    public $failcount;
    public $attachment;

    /**
     * 队列状态, 参见Zeed_Task
     *
     * @see Zeed_Task
     * @var integer
     */
    public $status;
    public $charset;

}

// End ^ Native EOL ^ encoding
