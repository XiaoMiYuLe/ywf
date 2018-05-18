<?php
/**
 * Playcool Project
 * 
 * LICENSE
 * 
 * http://www.playcool.com/license/ice
 * 
 * @category   ICE
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      2009-11-9
 * @version    SVN: $Id: Property.php 4987 2010-04-12 06:45:00Z xsharp $
 */

class Zeed_Object_Property extends Zeed_Object
{
    public $propertyid;
    
    public $propertyname;
    
    /**
     * @return Zeed_Object_Property
     */
    public static function getNewInstance()
    {
        return new Zeed_Object_Property();
    }
}

// End ^ LF ^ UTF-8
