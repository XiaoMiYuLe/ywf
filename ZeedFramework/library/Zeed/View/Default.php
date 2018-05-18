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
 * @version    SVN: $Id: Default.php 10906 2011-07-26 03:07:16Z xsharp $
 */

class Zeed_View_Default
{
    public function process($result, Zeed_Controller_Action $action)
    {
        if (is_array($result)) {
            headers_sent() || header('Content-type: application/json');
            echo json_encode($result);
        }
    }
}

// End ^ LF ^ UTF-8
