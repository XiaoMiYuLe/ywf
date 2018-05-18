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
 * @since      2010-8-9
 * @version    SVN: $Id$
 */

/**
 * @author Nroe
 */
class Zeed_Log_Writer_Db extends Zend_Log_Writer_Abstract
{
    /**
     * Database adapter instance
     * @var Zend_Db_Adapter
     */
    private $_db;

    /**
     * Name of the log table in the database
     * @var string
     */
    private $_table;

    /**
     * Relates database columns names to log data field keys.
     *
     * @var null|array
     */
    private $_columnMap;

    /**
     * Class constructor
     *
     * @param Zend_Db_Adapter $db   Database adapter instance
     * @param string $table         Log table in database
     * @param array $columnMap
     */
    public function __construct($db, $table, $columnMap = null)
    {
        $this->_db    = $db;
        $this->_table = $table;
        $this->_columnMap = $columnMap;
    }

    /**
     * Create a new instance of Zend_Log_Writer_Db
     *
     * @param  array|Zend_Config $config
     * @return Zend_Log_Writer_Db
     * @throws Zend_Log_Exception
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $config = array_merge(array(
            'db'        => null,
            'table'     => null,
            'columnMap' => null,
        ), $config);

        if (isset($config['columnmap'])) {
            $config['columnMap'] = $config['columnmap'];
        }

        return new self(
            $config['db'],
            $config['table'],
            $config['columnMap']
        );
    }

    /**
     * Formatting is not possible on this writer
     */
    public function setFormatter(Zend_Log_Formatter_Interface $formatter)
    {
        throw new Zeed_Exception(get_class() . ' does not support formatting');
    }

    /**
     * Remove reference to database adapter
     *
     * @return void
     */
    public function shutdown()
    {
        $this->_db = null;
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        if ($this->_db === null) {
            throw new Zeed_Exception('Database adapter is null');
        }

        if ($this->_columnMap === null) {
            $dataToInsert = $event;
        } else {
            $dataToInsert = array();
            foreach ($this->_columnMap as $columnName => $fieldKey) {
                if ($fieldKey == 'message' && ! is_string($event[$fieldKey])) {
                    $event[$fieldKey] = serialize($event[$fieldKey]);
                }
                $dataToInsert[$columnName] = $event[$fieldKey];
            }
        }

        $this->_db->insert($this->_table, $dataToInsert);
    }
}

// End ^ Native EOL ^ encoding
