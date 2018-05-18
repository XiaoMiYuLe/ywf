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
 * @since      2010-7-6
 * @version    SVN: $Id: Model.php 11362 2011-08-25 06:36:38Z nroe $
 */

class Com_Helper_Model {
    
    /**
     * 获取数据表记录对象的主键最大值
     * 
     * @return integer
     */
    public function getMaxId()
    {
        $id = 0;
        
        if (strlen($this->_primary) > 0) {
            $stmt = $this->_db->query(sprintf('SELECT MAX(%s) FROM `%s` WHERE 1', $this->_primary, $this->getTable()));
            $result = $stmt->fetchColumn();
            
            if (is_numeric($result)) {
                $id = (int) $result;
            }
        }
        
        return $id;
    }
}


// End ^ Native EOL ^ encoding
