<?php
class Mail_Config
{
    const RESET_TYPE = 1;
    
    protected static $config;
    
    public static function loadConfig()
    {
        if (! isset(self::$config)) {
            self::$config = Zeed_Config::loadGroup('mail');
        }
    }
    
    public static function loadSmtpConfig()
    {
    	self::loadConfig();
        $mailconfig = array();
        if (isset(self::$config['smtp_auth'])) {
            $mailconfig['auth'] = self::$config['smtp_auth'];
        }
        if (isset(self::$config['smtp_port'])) {
            $mailconfig['port'] = self::$config['smtp_port'];
        }
        if (isset(self::$config['smtp_pass'])) {
            $mailconfig['password'] = self::$config['smtp_pass'];
        }
        if (isset(self::$config['smtp_user'])) {
            $mailconfig['username'] = self::$config['smtp_user'];
        }
        return $mailconfig;
    }
    
    public static function loadResetPasswordText($code = "")
    {
    	self::loadConfig();
        $host = $_SERVER['SERVER_NAME'];
        $activeLink = 'http://' . $host . '/FrontDoor?code=' . $code;
        $mailLifetime = floor(self::$config['mail_code_lifetime']/3600);
        $siteLink = 'http://' . $host.'/FrontDoor';
        $mailTemplate = new Mail_Template();
        $mailTemplate->assign(array(
                'activelink' => $activeLink, 
                'lifetime' => $mailLifetime, 
                'siteurl' => $siteLink));
        $mailContentText = Zeed_Config::loadGroup('mailtemplate.resetpassword.text');
        $mailContentText = $mailTemplate->parse($mailContentText);
        $mailContentHtml = Zeed_Config::loadGroup('mailtemplate.resetpassword.html');
        if (! empty($mailContentHtml)) {
            $mailContentHtml = $mailTemplate->parse($mailContentHtml);
        } else {
            $mailContentHtml = nl2br($mailContentText);
        }
        
        return $mailContentHtml;
    }
    
    public static function loadFromAddr()
    {
    	self::loadConfig();
        return isset(self::$config['from_address']) ? self::$config['from_address'] : null;
    }
    public static function loadFromName()
    {
    	self::loadConfig();
        return isset(self::$config['from_username']) ? self::$config['from_username'] : null;
    }
    public static function loadHost()
    {
    	self::loadConfig();
        return isset(self::$config['smtp_host']) ? self::$config['smtp_host'] : null;
    }
    public static function loadLifeTime()
    {
    	self::loadConfig();
        return isset(self::$config['mail_code_lifetime']) ? self::$config['mail_code_lifetime'] : null;
    }
    public static function loadSubject($type)
    {
        switch ($type) {
            case self::RESET_TYPE :
                $subject = Zeed_Config::loadGroup('mailtemplate.resetpassword.subject');
                break;
            default :
                $subject = '您好';
                break;
        }
        return $subject;
    }
    public static function loadHtml($type, $params)
    {
    	if(!is_array($params)){
    		$params = array($params);
    	}
        switch ($type) {
            case self::RESET_TYPE :
                $html = call_user_func_array('self::loadResetPasswordText', $params);
                exit($html);
                break;
            default :
                $html = '您好';
                break;
        }
        return $html;
    }
}