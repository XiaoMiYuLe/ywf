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
 * @version    SVN: $Id: Cooldown.php 7045 2010-09-15 13:56:05Z xsharp $
 */

/**
 * COOLDOWN简单对象
 * 
 * ctime/mtime 格式: MySQL Datetime(Y-md-d H:i:s)
 * 
 * @author xsharp
 */
class Zeed_Object_Cooldown extends Zeed_Object
{
    public $namekey;
    
    /**
     * 创建时间
     * 
     * @var string
     */
    public $ctime;
    
    /**
     * 过期时间
     * 如果使用缓存(Zeed_Cache)做存储的话, 实际上这个等于失效时间.
     * 
     * @var string
     */
    public $etime;
    
    /**
     * 备注
     * 
     * @var string
     */
    public $memo;
}

// End ^ LF ^ encoding
