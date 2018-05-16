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
 * @since 2010-12-6
 * @version SVN: $Id$
 */
class CasAbstract extends IndexAbstract
{
    public $_userid = null;
    
    /**
     * 构造函数
     * 全部 ACTION 强制需要登录后执行
     */
    public function __construct(Zeed_Controller_Request $request)
    {
        parent::__construct($request);
    
        $moduleName = $this->input->getModuleName();
        $controllerName = $this->input->getControllerName();
        $actionName = $this->input->getActionName();
    
        /* 强制用户登录 */
        $msg = null;
        Cas_Authorization::forceLogin($msg);
    
        /* 获取用户信息 */
        $user_info = Cas_Authorization::getLoggedInUserInfo();
        
        $this->_userid = $user_info['userid'];
    }
    
    /**
     * 返回错误提示信息
     *
     * @param array
     */
    public function _commonPrompt($arr = array())
    {
        $p = http_build_query($arr);
        header("Location: /cas/commonPrompt?{$p}");
        exit;
    }
}

// End ^ Native EOL ^ UTF-8