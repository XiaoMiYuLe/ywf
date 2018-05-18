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

require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$et = trim($this->input->get('et', 'cm'));

$data['et'] = $et;

$smarty->assign($data);

$template = 'index.edit.html';
if ($et == 'cm') {
    $template = 'index.edit.online.html';
}

$smarty->display($template);

// End ^ Native EOL ^ UTF-8