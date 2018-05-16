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

abstract class Support_Sms_Check
{
    /**
     * 校验短信验证码
     * 
     * @param string $phone 手机号，即用户标识
     * @param string $code 短信验证码
     * @return boolean
     */
    public static function run($phone, $code)
    {
        $code_cache = Trend_Model_Redis::instance()->hmget('sms_code', array($phone));
        if ($code_cache && $code_cache[$phone] == $code) {
            return true;
        }
        return false;
    }
}


// End ^ Native EOL ^ encoding
