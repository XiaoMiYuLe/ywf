<?php
/**
 * iNewS Project
 *
 * LICENSE
 *
 * http://www.inews.com.cn/license/inews
 *
 * @category   Com
 * @package    Com_Model
 * @subpackage Com_Model_App
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Nroe ( GTalk: gnroed@gmail.com )
 * @since      Apr 8, 2010
 * @version    SVN: $Id: User.php 5368 2010-06-22 02:33:51Z nroe $
 */

class Com_Model_App extends Zeed_Db_Model
{
    /*
     * @var string The table name.
     */
    protected $_name = 'app';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'appid';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'cas_';

    const APP_TYPE_USER = 0;

    /**
     * 根据API KEY获取应用信息
     * @param string $apikey
     * @return array|null
     */
    public function getAppByApikey($apikey)
    {
        $hash = md5('cas-apikey-'.$apikey);
        $cache = Zeed_Cache::instance();

        if ( ($data = $cache->load($hash)) && $data ) {
            return $data;
        }
        $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('apikey') . ' = ?', $apikey);
        $rows = $this->getAdapter()->query($sql)->fetchAll();
        unset($sql);

        if (is_array($rows) && count($rows) > 0) {
            $cache->save($rows[0], $hash, array(), 86400); //缓存一天吧
            return $rows[0];
        }
        return null;
    }

    /**
     * 获取所有用户程序信息
     *
     * @return array|null
     */
    public function getUserApp()
    {
        $db = $this->getAdapter();
        $select = $db->select()->from($this->getTable())->where('xtype = ?', self::APP_TYPE_USER);
        $rows = $db->fetchAll($select);
        $result = null;

        if (is_array($rows)) {
            $result = $rows;
        }

        return $result;
    }

    /**
     * 获取所有用户程序标识
     *
     * @return array|null
     */
    public function getUserAppID()
    {
        $userApp = $this->getUserApp();
        $result = null;

        if ( $userApp ) {
            $result = array();
            foreach ($userApp as $app) {
                $result[] = $app['appid'];
            }
        }

        return $result;
    }

    /**
     * @return Com_Model_App
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
