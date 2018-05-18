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
 * @since      2009-8-25
 * @version    SVN: $Id: Interface.php 4642 2009-12-08 01:33:14Z xsharp $
 */

interface Zeed_Controller_Action_Interface
{
    public function addResult($result,$resultType = null,$resource = null);
    public function getConfig();
    public function index();
}

// End ^ LF ^ UTF-8
