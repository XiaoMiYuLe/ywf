<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Mar 29, 2010
 * @version    SVN: $Id$
 */

interface Zeed_Permission_Interface
{
    public function compare();
    public function getUserPermission();
    public function getAppPermission();
}

// End ^ LF ^ encoding
