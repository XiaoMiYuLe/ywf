<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-3-21
 * @version    SVN: $Id$
 */

class Advert_Model_Board extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'board';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'board_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'advert_';

    
    /**
     * @return Advert_Model_Board
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ Native EOL ^ UTF-8