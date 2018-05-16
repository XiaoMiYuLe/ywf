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
 * @category   Cas
 * @package    Cas_Model
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @author     Nroe ( GTalk: gnroed@gmail.com )
 * @since      Apr 8, 2010
 * @version    SVN: $Id$
 */

class Cas_Model_Code extends Zeed_Db_Model
{
    /*
     * @var string The table name.
     */
    protected $_name = 'code';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'cas_';

    public function isValid (Cas_Entity_Code_Email_ForgotPassword $cas_Entity_Code_Email)
    {
        $cas_code = $this->fetchByFV("code", $cas_Entity_Code_Email->code);
        if ($cas_code) {
            $ctime = $cas_code[0]['ctime'];
            if (empty($ctime)) {
                return false;
            } else if ((strtotime($ctime) + 1800 ) < time()) {
                return false;
            } 
            else {
                $cas_entiy_code = new Cas_Entity_Code();
                $cas_entiy_code->fromArray($cas_code[0]);
                return $cas_entiy_code;
            }
            
        }
        
        return false;
    }

    /**
     *
     * @return Cas_Model_Code
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
