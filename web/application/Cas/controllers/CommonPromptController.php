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

class CommonPromptController extends CasAbstract
{
    /**
     * 默认错误提示信息
     *
     * @var array $_commonPrompt
     */
    protected $_defaultErrorNotice = array(
            'title' => '友情提示',
            'msg' => '操作失败，系统将在10秒后返回登录页面',
            'url' => './');
    
    /**
     * 各种错误提示页面
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $p = $this->input->get();
        if (is_array($p) && count($p) > 0) {
            $arr = array(
                    'title' => urldecode($p['title']),
                    'msg' => urldecode($p['msg']),
                    'url' => urldecode($p['url'])
            );
            $this->commonPrompt = $arr;
        } else {
            $this->commonPrompt = $this->_defaultErrorNotice;
        }
        
        $this->addResult(self::RS_SUCCESS, 'php', 'commonprompt');
        return parent::multipleResult(self::RS_SUCCESS);
    }
}

// End ^ Native EOL ^ UTF-8