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
 * @package    Zeed_Db
 * @subpackage Zeed_Db_Model
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-7-5
 * @version    SVN: $Id$
 */

class Zeed_Db_Model_Detach extends Zeed_Db_Model
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
    protected $_detachNum = 1;

    /**
     * 强制跳过 crc32 加密
     *
     * @var unknown_type
     */
    protected $_skipCrc32 = false;

    /**
     *
     * @var array|null
     * @see database.table_detach
     */
    private static $_detachConfig = false;

    /**
     *
     * @var $_detachToken string
     */
    protected $_detachToken = null;

    public function __construct($config = array())
    {
        $this->_table_name = $this->_name;

        parent::__construct($config);

        /**
         * load configure
         */
        if ( ! is_string($this->_detachField) || '' == $this->_detachField ) {
            throw new Zeed_Exception('Detach Field was not defined. you must overwirte the variable $_detachField in your model.');
        }

        if ( null !== $this->_detachNum) {
            if ( false === self::$_detachConfig) {
                self::$_detachConfig = Zeed_Config::loadGroup('database.table_detach');
            }

            if ( null === self::$_detachConfig || ! isset(self::$_detachConfig[$this->_table_name]) ) {
                throw new Zeed_Exception($this->_table_name . 'Detach config was not defined. check to see database.php in config.');
            }

            $this->_detachNum = self::$_detachConfig[$this->_table_name];
        }
    }

    public function format($value)
    {
        $value = (int) $value;

        if (function_exists('bcmod')) {
            return bcmod($value, $this->_detachNum);
        } else {
            return $value % $this->_detachNum;
        }
    }

    /**
     * 获取分表依据字段整型值，用于分表运算
     *
     * @param string $field
     * @return integer|null
     * @overwrite
     */
    protected function getDetcahFieldForMod($value)
    {
        $forMod = null;

        if (is_int($value)) {
            $forMod = $value;
        } elseif (is_string($value) && ! $this->_skipCrc32) {
            $checksum = crc32($value);
            $forMod = sprintf("%u", $checksum);
        } else {
            $forMod = $value;
        }

        return $forMod;
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
            $i = $this->getDetcahFieldForMod($data[$this->_detachField]);
        } elseif ($data instanceof Zeed_Object) {
            $d = $data->toArray();
            if (! empty($d[$this->_detachField])) {
                $i = $this->getDetcahFieldForMod($d[$this->_detachField]);
            }
        } elseif (is_string($data) || is_numeric($data) || is_integer($data)) {
            $i = $this->getDetcahFieldForMod($data);
        }

        $formatted = $this->format($i);
        $this->setDetachToken($formatted);
    }

    public function getDetachToken()
    {
        return $this->_detachToken;
    }

    public function setDetachToken($token) {
        $token = (int) $token;

        if ($token >= 0 && $token <= 1000) {
            $num = strlen(strval($this->_detachNum - 1));
            $this->_detachToken = sprintf("%0{$num}u", $token);
        }
    }

    /**
     * @return string
     */
    public function getTable()
    {
        if ( null !== $this->_detachToken) {
            return $this->_name.'_'.$this->_detachToken;
        }

        return $this->_name;
    }

    /**
     *
     */
    public function createDetachTable($tables) {

    }
}

// End ^ Native EOL ^ encoding
