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
 * @since      Jun 23, 2010
 * @version    SVN: $Id: CommonPrompt.php 5380 2010-06-23 05:48:03Z Cyrano $
 */

/**
 * 通用提示信息界面
 * 设置$this->commonPrompt = array(
 *      'title'=>'六个字以内标题',
 *      'msg'=>'提示信息内容',
 *      'url'=>'要跳转到的URL地址，如果需要跳转的话，不需要跳转设置为空',
 *      'autoredirect'=>是否自动跳转，默认TRUE,
 *      'countdown'=>'多少秒后跳转，默认10秒'
 * );
 */

require_once dirname(__FILE__) . '/view.init.php';

$prompt = $this->commonPrompt;

if (!isset($prompt['autoredirect'])) {
    $prompt['autoredirect'] = true;
}
if (!isset($prompt['countdown'])) {
    $prompt['countdown'] = 10;
} else {
    $prompt['countdown'] = (int) $prompt['countdown'];
    if ($prompt['countdown'] < 1) {
        $prompt['countdown'] = 10;
    }
}

$smarty->assign('prompt', $prompt);

$smarty->display('commonprompt.html');

// End ^ Native EOL ^ encoding
