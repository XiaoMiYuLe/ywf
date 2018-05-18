<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      Aug 10, 2010
 * @version    SVN: $Id$
 */

class Legacy_Action extends Zeed_Controller_Action
{
    const RESULT_INPUT = 'input';
    const RESULT_SUCCESS = 'success';
    
	const RS_I   = 'input';
	const RS_S   = 'success';
	const RS_F   = 'failure';
    
    protected $_allowedResultType = array(
            'default' => 'Zeed_View_Default',
            'php' => 'Zeed_View_Php',
            'zendview' => 'Zeed_View_Php',
            'redirector' => 'Zeed_View_Redirector',
            'json' => 'Zeed_View_Json',
            'xml' => 'Zeed_View_Xml',
            'jsonp' => 'Zeed_View_Jsonp');
}

// End ^ Native EOL ^ encoding
