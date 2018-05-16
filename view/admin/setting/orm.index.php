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
 * @since      2010-12-7
 * @version    SVN: $Id$
 */

require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$smarty->assign($data);

$smarty->display('orm.index.html');

// End ^ Native EOL ^ UTF-8