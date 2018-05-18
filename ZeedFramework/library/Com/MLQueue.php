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
 * @since      2010-9-13
 * @version    SVN: $Id$
 */

class Com_MLQueue
{
    private $_regionid2gameid = array(
        201 => array(
            'host' => '172.18.27.101:1433',
            'user' => 'ddinterface_test',
            'password' => 'dd123456',
            'dbname' => 'passport_mqueue',
            'queuetable' => 'informer_queue_ML3099'),
        202 => array(
            'host' => '172.18.27.101:1433',
            'user' => 'ddinterface_test',
            'password' => 'dd123456',
            'dbname' => 'passport_mqueue',
            'queuetable' => 'informer_queue_ML3099'),
        211 => array(
            'host' => '172.18.27.101:1433',
            'user' => 'ddinterface_test',
            'password' => 'dd123456',
            'dbname' => 'passport_mqueue',
            'queuetable' => 'informer_queue_ML3099'),
//        201 => 'ML3011',
//        202 => 'ML3012',
//        211 => 'ML3013',
    );
    
    private $_link = null;
    private static $_instance = null;
    private $_enable = false;
    
    private function _connect($regionid = null)
    {
        if ($this->_enable && is_array($this->_regionid2gameid)) {
            if (null !== $regionid) {
                $regionid = (int) $regionid;
                
                if (! isset($this->_regionid2gameid[$regionid])) {
                    throw new Zeed_Exception("Unknown regionid '{$regionid}', can not query ML SQL Server.");
                } else {
                    $db = $this->_regionid2gameid[$regionid];
                    
                    $link = mssql_connect($db['host'], $db['user'], $db['password']);
                    if (! $link || ! mssql_select_db($db['dbname'], $link)) {
                        $mssqlMessage = 'Unable to connect or select ML SQL Server database, MSSQL error: ' . mssql_get_last_message();
                        Zeed_Log::instance()->log($mssqlMessage, Zeed_Log::WARN);
                        throw new Zeed_Exception($mssqlMessage);
                    }
                    
                    $this->_link[$regionid] = $link;
                }
            } else {
                foreach ($this->_regionid2gameid as $_regionid => $_db) {
                    
                    $link = mssql_connect($_db['host'], $_db['user'], $_db['password']);
                    if (! $link || ! mssql_select_db($_db['dbname'], $link)) {
                        $mssqlMessage = 'Unable to connect or select ML SQL Server database, MSSQL error: ' . mssql_get_last_message();
                        Zeed_Log::instance()->log($mssqlMessage, Zeed_Log::WARN);
                        throw new Zeed_Exception($mssqlMessage);
                    }
                    
                    $this->_link[$_regionid] = $link;
                }
            }
        }
        
        return $this;
    }
    
    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    private function __construct()
    {
        $this->_enable = Zeed_Config::loadGroup('ml.enable_ml_queue');
        $regionid2gameid = Zeed_Config::loadGroup('ml.mlqueuedb');
        if (is_array($regionid2gameid)) {
            $this->_regionid2gameid = $regionid2gameid;
        }
    }
    
    public function __destruct()
    {
        if ($this->_link && is_array($this->_link)) {
            foreach ($this->_link as $link) {
                mssql_close($link);
            }
        }
    }
    
    /**
     * 激活梦幻龙族
     * 激活梦幻龙族电信1、电信2、网通1帐号
     *
     * @param string $username 通行证帐号
     * @param string $userpassword 帐号原文密码
     * @param integer $birthday 用户生日，UNIX 时间戳
     *
     * @return boolean 成功返回 true, 失败返回 false
     */
    public function active($username, $userpassword, $birthday, $regionid = null)
    {
        $password = (string) $userpassword;
        $username = (string) $username;
        
        $birthday = date('Ymd', $birthday);
        
        return $this->_insertQueue("0,{$username},{$password},{$birthday}", $regionid);
    }
    
    /**
     * 新手卡
     *
     * @param string $username 通行证帐号
     * @param string $userpassword 帐号原文密码
     * @param integer $birthday 用户生日，UNIX 时间戳
     *
     * @return boolean 成功返回 true, 失败返回 false
     */
    public function newbieCard($username, $cardno, $regionid = null)
    {
        $username = (string) $username;
        $cardno = (string) $cardno;
        
        return $this->_insertQueue("7,{$username},{$cardno}", $regionid);
    }
    
    /**
     * 冻结梦幻龙族中的帐号
     *
     * @param string $username 通行证帐号
     *
     * @return boolean 成功返回 true, 失败返回 false
     */
    public function freeze($username, $regionid = null)
    {
        $username = (string) $username;
        
        return $this->_insertQueue("2,{$username},0", $regionid);
    }
    
    /**
     * 解冻梦幻龙族中的帐号
     *
     * @param string $username 通行证帐号
     *
     * @return boolean 成功返回 true, 失败返回 false
     */
    public function unfreeze($username, $regionid = null)
    {
        $username = (string) $username;
        
        return $this->_insertQueue("2,{$username},1", $regionid);
    }
    
    /**
     * 修改梦幻龙族中帐号密码
     *
     * @param string $username 通行证帐号
     * @param string $password 新的原文密码
     *
     * @return boolean 成功返回 true, 失败返回 false
     */
    public function modifyPassword($username, $userpassword, $regionid = null)
    {
        $password = (string) $userpassword;
        $username = (string) $username;
        
        return $this->_insertQueue("4,{$username},{$password}", $regionid);
    }
    
    /**
     * 修改梦幻龙族中帐号生日
     *
     * @param string $username 通行证帐号
     * @param integer $birthday 帐号生日，UNIX 时间戳
     */
    public function modifyBirthday($username, $birthday, $regionid = null)
    {
        $birthday = (int) $birthday;
        $username = (string) $username;
        
        $birthday = date('Ymd', $birthday);
        
        return $this->_insertQueue("5,{$username},{$birthday}", $regionid);
    }
    
    /**
     * 梦幻龙族充值
     * 将充值点数插入到梦龙充值队列
     *
     * @param integer $regionid 资产域，梦龙分区资产域
     * @param string $username 通行证帐号
     * @param integer $point 充值点数
     * @param string $billno 充值订单号
     */
    public function charge($regionid, $username, $point, $billno)
    {
        $username = (string) $username;
        $point = (int) $point;
        $billno = (string) $billno;
        
        return $this->_insertQueue("1,{$username},{$point},{$billno}", $regionid);
    }
    
    private function _insertQueue($data, $regionid = null)
    {
        if (! $this->_enable) {
            return true;
        }
        
        $data = (string) $data;
        $timenow = DATENOW;
        
        $sql = '';
        try {
            if (! isset($this->_link[$regionid]) || ! $this->_link[$regionid]) {
                $this->_connect($regionid);
            }
            
            if (null !== $regionid) {
                $link = $this->_link[$regionid];
                $queuetable = $this->_regionid2gameid[$regionid]['queuetable'];
                
                $sql = "INSERT INTO {$queuetable} (create_ts, data) VALUES ('{$timenow}', '{$data}')";
                
                $result = @mssql_query($sql, $link);
            } else {
                foreach ($this->_regionid2gameid as $_regionid => $_db) {
                    $link = $this->_link[$_regionid];
                    $queuetable = $_db['queuetable'];
                    
                    $sql = "INSERT INTO {$queuetable} (create_ts, data) VALUES ('{$timenow}', '{$data}')";
                    
                    $result = @mssql_query($sql, $link);
                }
            }
            
            if (! $result) {
                throw new Zeed_Exception('ML SQL Server query error, MSSQL error: ' . mssql_get_last_message());
            }
            
            return true;
        } catch (Exception $e) {
            Zeed_Log::instance()->log(sprintf('can not insert ml queue, error msg : "%s" sql: "%s" data: "%s" regionid: "%s"', $e->getMessage(), $sql, $data, $regionid), Zeed_Log::WARN);
        }
        
        return false;
    }
}

// End ^ Native EOL ^ encoding
