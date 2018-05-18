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
 * @since      2010-3-23
 * @version    SVN: $Id$
 */

interface Zeed_Cooldown_Interface
{
    public function save(Zeed_Object_Cooldown $set);
    public function getByNamekey($key);
}

// End ^ LF ^ encoding
