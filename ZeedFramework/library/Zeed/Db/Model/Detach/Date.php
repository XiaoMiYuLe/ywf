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
 * @since      2010-8-11
 * @version    SVN: $Id$
 */

class Zeed_Db_Model_Detach_Date extends Zeed_Db_Model_Detach
{
    /**
     * table name
     *
     * @var string
     */
    protected $_table_name = null;

    /**
     * 定义分表依据字段
     *
     * @var $_detachField string
     * @overwrite
     */
    protected $_detachField = '';

    /**
     * 数据分表数
     * 指定的值是多少，分出的表就有多少。
     *
     * @var $_detachNum integer
     */
    protected $_detachNum = null;

    /**
     *
     * @var $_detachToken string
     */
    protected $_detachToken = null;

    public function __construct($config = array())
    {
        $this->_table_name = $this->_name;
        parent::__construct($config);
    }

    public function getDetcahFieldForDate($value)
    {
        if (is_numeric($value) || is_integer($value)) {
            return $value;
        }

        $forDate = strtotime($value);
        if (!$forDate) {
            $forDate = 0;
        }

        return $forDate;
    }

    public function format($value)
    {
        if (is_integer($value) || is_numeric($value)) {
            return date('ym', $value);
        }

        return '0000';
    }

    public function setDetachToken($token) {
        $this->_detachToken = str_pad($token, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 获取分表表名因子
     *
     * @param mixed 查询数据库条件数据、更新数据
     * @return void
     */
    public function detachToken($data)
    {
        if ('' == $this->_detachField) {
            throw new Zeed_Exception('must specify a valid field for detcah table');
        }

        $i = 0;

        if (is_array($data) && ! empty($data[$this->_detachField])) {
            $i = $this->getDetcahFieldForDate($data[$this->_detachField]);
        } elseif ($data instanceof Zeed_Object) {
            $d = $data->toArray();
            if (! empty($d[$this->_detachField])) {
                $i = $this->getDetcahFieldForMod($d[$this->_detachField]);
            }
        } elseif (is_numeric($data) || is_integer($data)) {
            $i = $data;
        } else {
            $i = $this->getDetcahFieldForDate($data);
        }

        $this->_detachToken = $this->format($i);
    }
}

// End ^ Native EOL ^ encoding