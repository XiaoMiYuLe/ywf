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

class Promotion_Model_Content extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'content';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'promotion_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'promotion_';
    
    
    /**
     * @return Promotion_Model_Content
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8