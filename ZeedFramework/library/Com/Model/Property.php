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
 * @since      May 17, 2010
 * @version    SVN: $Id$
 */

class Com_Model_Property extends Com_Model_Configurable
{
    
    /**
     * 
     * @param string $propertyname
     * @return integer
     */
    public static function convertPropertyname2Propertyid($propertyname)
    {
    }
    
    public function cacheIsValid()
    {
        return true;
    }
    
    /**
     * @return Com_Model_Property
     */
    public static function instance()
    {
    }
}

// End ^ LF ^ encoding
