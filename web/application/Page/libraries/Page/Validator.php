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
 * @since      2011-3-21
 * @version    SVN: $Id$
 */

/**
 * 统一的验证
 */
class Page_Validator
{
    /**
     * 校验创建单页分组时的分组文件名，即控制器名称
     * 
     * @param string $folder
     * @return true|array 如果失败返回 array 错误信息
     */
    public static function folder($folder)
    {
        $pattern = "/^[a-zA-Z]+$/";
        $regex = new Zend_Validate_Regex($pattern);
        $regex->setMessage("您输入的'%value%'文件名，系统无法识别，请检查规则后再试。", Zend_Validate_Regex::NOT_MATCH);
        
        if (! $regex->isValid($folder)) {
            return $regex->getMessages();
        }
    
        return true;
    }
}


// End ^ Native EOL ^ encoding
