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

class Support_Sms_Abstract
{
    /**
     * 生成验证码
     */
    public static function buildCode()
    {
        /* 读取配置文件 */
        $sms_config = Zeed_Config::loadGroup('sms');
        
        /* 生成验证码 */
        $tokenlen = $sms_config['code_longth'] >= 4 ? $sms_config['code_longth'] : 4;
        $mrl = str_pad('1', $tokenlen - 3, '0');
        $mrr = str_pad('9', $tokenlen - 3, '9');
        $code_info['code'] = str_pad(((microtime(true) * 1000) % 1000) . mt_rand($mrl, $mrr), $tokenLen, '0', STR_PAD_LEFT);
        
        /* 处理过期时间 */
        if (isset($sms_config['code_lifetime'])) {
            $code_info['exptime'] = date(DATETIME_FORMAT, TIMENOW + $sms_config['code_lifetime']);
        }
        
        return $code_info;
    }
}

// End ^ Native EOL ^ encoding