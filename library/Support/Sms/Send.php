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
 * @since      2010-7-22
 * @version    SVN: $Id$
 */

class Support_Sms_Send
{
    /**
     * 发送短信
     */
    public static function run($phone, $msg)
    {
        $sms_config = Zeed_Config::loadGroup('sms');
        $sms_default = $sms_config['default'];
        $cto = "Support_Sms_{$sms_default}_Send";
        return $cto::run($phone, $msg);
    }
}


// End ^ Native EOL ^ encoding
