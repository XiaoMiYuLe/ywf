<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-10-26
 * @version SVN: $Id$
 */
class Install_CheckInstall
{
    /**
     * 校验是否已安装
     */
    public static function run()
    {
        $file_lock = ZEED_BOOT . 'install/install.lock';
        if (file_exists($file_lock)) {
            return true;
        }
        return false;
    }
}

// End ^ Native EOL ^ UTF-8