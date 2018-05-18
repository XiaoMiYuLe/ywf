<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-7-9
 * @version    SVN: $Id$
 */

class Zeed_CC_Pwdcard extends Zeed_CC_Abstract
{
    /**
     * 信用卡序列号名称
     *
     * @var string
     */
    protected $_name = 'pwdcard';

    /**
     * 信用卡序列号前缀
     *
     * @var numeric
     */
    protected $_allowPrefix = array('1001', '1002', '1003', '1004', '1005');

    /**
     * 信用卡序列号长度，可以指定不同的长度
     *
     * array
     */
    protected $_allowLength = array(16);

}

// End ^ Native EOL ^ encoding
