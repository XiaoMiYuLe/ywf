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
 * @since      Jun 4, 2009
 * @version    SVN: $Id$
 */

class Fit_View_Php extends Zeed_View
{
    public function process($result, Fit_Controller_Action $action)
    {
        $action_config = $action->getConfig();
        $resource = $action_config[$result]['resource'];
        
        echo parent::render($resource);
    }
}

// End ^ LF ^ UTF-8
