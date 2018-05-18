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
 * @since      2009-8-5
 * @version    SVN: $Id$
 */

class Fit_Controller_Front extends Zend_Controller_Front
{
    
    /**
     * 
     * @return Fit_Controller_Front
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->setDispatcher(new Fit_Controller_Dispatcher());
            self::$_instance->setRouter(new Fit_Controller_Router());
        }

        return self::$_instance;
    }
}

// End ^ LF ^ encoding
