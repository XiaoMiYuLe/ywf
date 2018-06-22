<?php

/**
 * 借款标的表
 */
class P2P_Model_BorrowInfo extends P2P_Model_Public
{

    /**
     * @var string The table name.
     */
    protected $_name = 'borrow_info';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'borrow_info_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'p_';

    /**
     * @return P2P_Model_BorrowInfo
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
