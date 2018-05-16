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
 * @since      Jun 24, 2010
 * @version    SVN: $Id$
 */

class Trend_Helper_SendMail
{
    protected static $_emailAddress = array();
    
    protected static $_mailText = '';
    protected static $_mailHtml = '';
    protected static $_mailSubject = '';
    
    /**
     * @param string $emailTitle 邮件标题
     * @param string $emailAddress 目标邮件地址
     * @param string $mailText 邮件模板内容
     * @param string $mailHtml 邮件模板 HTML 内容
     */
    public function __construct($mailSubject, $emailAddress, $mailText, $mailHtml = null)
    {
        self::$_emailAddress = trim($emailAddress);
        self::$_mailSubject = trim($mailSubject);
        self::$_mailText = trim($mailText);
        self::$_mailHtml = trim($mailHtml);
    }
    
    /**
     * @param array $vars
     */
    public function assignTemplateVars($vars)
    {
        if (! empty(self::$_mailText)) {
            foreach ($vars as $key => $value) {
                self::$_mailText = str_replace("#\${$key}#", $value, self::$_mailText);
            }
        }
        if (! empty(self::$_mailHtml)) {
            foreach ($vars as $key => $value) {
                self::$_mailHtml = str_replace("#\${$key}#", $value, self::$_mailHtml);
            }
        }
    }
    
    /**
     * 发送邮件
     * 
     * @return boolean|array 返回错误信息
     */
    public function send()
    {
        $validator = new Zend_Validate_EmailAddress();
        if (! $validator->isValid(self::$_emailAddress)) {
            $this->error[] = '邮箱地址错误，请检查确定后重试.';
            return $this->error;
        }
        
        $data = array(
                'to' => self::$_emailAddress, 
                'subject' => self::$_mailSubject, 
                'bodytext' => self::$_mailText, 
                'bodyhtml' => self::$_mailHtml
        );
        
        Zeed_Hook::exec('Hooks.Mail.Send', array('data' => $data));
        
        return true;
    }
}