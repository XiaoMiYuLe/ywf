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
class PageAbstract extends IndexAbstract
{
    /**
     * 构造函数
     * 检查数据库中是否存在该单页
     */
    public function __construct(Zeed_Controller_Request $request)
    {
        parent::__construct($request);
    
        $controllerName = $this->input->getControllerName();
        $actionName = $this->input->getActionName();
        
        $page = $this->_checkPage($controllerName, $actionName);
        if (! $page) {
            echo '该单页不存在';
            exit;
        }
    }
    
    private function _checkPage($controllerName, $actionName)
    {
        return Page_Model_Listing::instance()->fetchByWhere(" folder = '{$controllerName}' AND page_folder = '{$actionName}'");
    }
}

// End ^ Native EOL ^ UTF-8