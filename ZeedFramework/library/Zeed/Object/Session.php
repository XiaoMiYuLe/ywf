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
 * @since      2010-3-8
 * @version    SVN: $Id: Session.php 7045 2010-09-15 13:56:05Z xsharp $
 */

/**
 * SESSION简单对象
 * 
 * ctime/mtime 格式: MySQL Datetime(Y-md-d H:i:s)
 * 
 * @author xsharp
 */
class Zeed_Object_Session extends Zeed_Object
{
    public $sessionid;
    public $username;
    public $ip;
    public $useragent;
    public $ticket;
    
    /**
     * @var String
     */
    public $ctime;
    
    /**
     * @var String
     */
    public $mtime;

}

// End ^ LF ^ encoding
