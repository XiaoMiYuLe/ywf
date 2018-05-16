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
 * @since      Apr 16, 2010
 * @version    SVN: $Id$
 */
class Bts_Model_Logistics extends Zeed_Db_Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected $_name = 'logistics';

    /**
     * 主键
     *
     * @var String
     */
    protected $_primary = 'logistics_id';

    /**
     * 前缀
     *
     * @var string Table prefix.
     */
    protected $_prefix = 'bts_';

    /**
     *
     * @return Bts_Model_Order
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding