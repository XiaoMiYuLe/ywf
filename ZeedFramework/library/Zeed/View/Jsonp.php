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
 * @version    SVN: $Id: Json.php 4683 2009-12-17 09:52:32Z xsharp $
 */

class Zeed_View_Jsonp extends Zeed_View_Json
{
    const CALLBACK_FUNCTION_NAME = 'callback';
    
    public function process($result, Zeed_Controller_Action $action)
    {
        $jsonData = parent::_process($result, $action);
        
        $callback = $action->getParam(self::CALLBACK_FUNCTION_NAME);
        if ($callback) {
            echo $callback . '(' . $jsonData . ')';
        } else {
            echo $jsonData;
        }
        exit();
    }
}
// End ^ LF ^ UTF-8
