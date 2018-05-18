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
 * @since      2010-12-6
 * @version    SVN: $Id$
 */

class IndexController extends AdminAbstract
{
    
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8