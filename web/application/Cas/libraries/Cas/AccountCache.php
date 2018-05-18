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
 * @since      Jul 28, 2010
 * @version    SVN: $Id: Cas_AccountCache.php 10532 2011-06-21 04:55:12Z woody $
 */

class Cas_AccountCache
{
    private static $_fields = array('username','password','freeze','encrypt','salt','idcard','realname','gender','nickname','email','ctime',
            'status','mibaoka','gmpassword');
    
    /**
     * 获取用户信息缓存
     * 
     * @param string $username
     */
    public static function getByUsername($username)
    {
        $username = strtolower($username);
        $config = Zeed_Config::loadGroup('accountcache');
        if (empty($config) || empty($config['db']) || empty($config['table'])) {
            throw new Exception('用户信息缓存未配置', -1);
        }
        $connection = new Mongo($config['server'], $config['server_options']);
        $connection->connect();
        $cachedb = $config['db'];
        $cachetable = $config['table'];
        $db = $connection->$cachedb;
        $collection = $db->$cachetable;
        $cachekey = strval(md5('uinfo-' . $username));
        
        $where = array('_id' => $cachekey);
        $info = $collection->findOne($where);
        if (empty($info)) {
            return self::createCache($username);
        }
        
        return $info;
    }
    
    /**
     * 更新指定userid的用户缓存
     * 
     * @param $userid
     * @param $update
     */
    public static function updateCacheByUserid($userid, $update='*')
    {
        $user = Cas_Model_User::instance()->getUserByUserid($userid);
        if (empty($user)) {
            throw new Exception('用户不存在', 10001);
        }
        $username = $user['username'];
        return self::updateCache($username, $update);
    }
    
    /**
     * 更新用户信息缓存
     * 
     * @param string $username 缓存用户的用户名, 如果有更改用户名, 需要是更改前的用户名
     * @param array $update
     */
    public static function updateCache($username, $update = '*')
    {
        $username = strtolower($username);
        if ('*' == $update) {
            return self::createCache($username, true);
        }
        $config = Zeed_Config::loadGroup('accountcache');
        if (empty($config) || empty($config['db']) || empty($config['table'])) {
            throw new Exception('用户信息缓存未配置');
        }
        $connection = new Mongo($config['server'], $config['server_options']);
        $connection->connect();
        $cachedb = $config['db'];
        $cachetable = $config['table'];
        $db = $connection->$cachedb;
        $collection = $db->$cachetable;
        $cachekey = strval(md5('uinfo-' . $username));
        
        //如果更改用户名
        if (isset($update['username']) && $update['username'] != $username) {
            $collection->remove(array('_id' => $cachekey), array('safe' => true));
            return self::createCache($update['username']);
        }
        
        $where = array('_id' => $cachekey);
        $info = $collection->findOne($where);
        if (empty($info)) {
            return self::createCache($username);
        }
        
        //更新
        foreach ($update as $k => $v) {
            if (! in_array($k, self::$_fields)) {
                unset($update[$k]);
            }
        }
        
        if (isset($update['mibaoka'])) {
            $detail = Cas_Model_User_Detail::instance()->getUserDetailByUserid($info['userid'], array('pwdcard'));
            if (! empty($detail['pwdcard'])) $mbk = unserialize($detail['pwdcard']);
            if (! empty($mbk)) {
                $update['mibaoka'] = serialize(array(0=>array('pwdcard'=>$mbk)));
            }
        }
        
        if (! empty($update)) {
            $collection->update(array('_id' => $cachekey), array('$set' => $update));
        }
        
        return true;
    }
    
    public static function createCache($username, $checkExist = false)
    {
        $username = strtolower($username);
        $config = Zeed_Config::loadGroup('accountcache');
        if (empty($config) || empty($config['db']) || empty($config['table'])) {
            throw new Exception('用户信息缓存未配置');
        }
        $connection = new Mongo($config['server'], $config['server_options']);
        $connection->connect();
        $cachedb = $config['db'];
        $cachetable = $config['table'];
        $db = $connection->$cachedb;
        $collection = $db->$cachetable;
        $cachekey = strval(md5('uinfo-' . $username));
        
        if ($checkExist) {
            $info = $collection->findOne(array('_id' => $cachekey));
            if (!empty($info)) {
                $collection->remove(array('_id' => $cachekey), array('safe' => true));
            }
        }
        
        $basic = Cas_Model_User::instance()->getUserByUsername($username);
        if (empty($basic)) {
            return false;
        }
        $data = array('userid' => strval($basic['userid']),'username' => $basic['username'],
                'password' => $basic['password'],'thirdid' => $basic['thirdid'],
                'salt' => $basic['salt'],'encrypt' => $basic['encrypt'],
                'email' => $basic['email'],'nickname' => $basic['nickname'],
                'gender' => $basic['gender'],'ctime' => $basic['ctime'],
                'ban_etime' => $basic['ban_etime'], 'status' => $basic['status']);
        $data['idcard'] = $basic['idcard'];
        $data['realname'] = $basic['realname'];
        
        $detail = Cas_Model_User_Detail::instance()->getUserDetailByUserid($basic['userid'], array('pwdcard'));
        if (! empty($detail['pwdcard'])) $mbk = unserialize($detail['pwdcard']);
        if (! empty($mbk)) {
            $data['mibaoka'] = serialize(array(0=>array('pwdcard'=>$mbk)));
        } else {
            $data['mibaoka'] = '';
        }
        
        $gmpass = Cas_Model_User_GmPassword::instance()->getGmPassword($basic['userid']);
        if ($gmpass && $gmpass['etime'] > time()) {
            $data['gmpassword'] = $gmpass['gmpassword'].'|'.$gmpass['etime'];
        } else {
            $data['gmpassword'] = '';
        }
        
        $data['_id'] = $cachekey;
        
        $collection->insert($data);
        $collection->ensureIndex(array('thirdid'=>1));
        
        return $data;
    }
    
    /**
     * 直接从数据数组中创建缓存
     * @param array $data
     */
    public static function createCacheFromData($basic)
    {
        $username = strtolower($basic['username']);
        $config = Zeed_Config::loadGroup('accountcache');
        if (empty($config) || empty($config['db']) || empty($config['table'])) {
            throw new Exception('用户信息缓存未配置');
        }
        $connection = new Mongo($config['server'], $config['server_options']);
        $connection->connect();
        $cachedb = $config['db'];
        $cachetable = $config['table'];
        $db = $connection->$cachedb;
        $collection = $db->$cachetable;
        $cachekey = strval(md5('uinfo-' . $username));
        
        $data = array('userid' => strval($basic['userid']),'username' => $basic['username'],
                'password' => $basic['password'],'thirdid' => ($basic['thirdid'] ? $basic['thirdid'] : null) ,
                'salt' => $basic['salt'],'encrypt' => $basic['encrypt'],
                'email' => $basic['email'],'nickname' => (isset($basic['nickname']) ? $basic['nickname'] : null),
                'gender' => (isset($basic['gender']) ? $basic['gender'] : 0),'ctime' => $basic['ctime'],
                'ban_etime' => (isset($basic['ban_etime']) ? $basic['ban_etime'] : null), 'status' => $basic['status']);
        $data['idcard'] = $basic['idcard'];
        $data['realname'] = $basic['realname'];
        $data['mibaoka'] = '';
        $data['gmpassword'] = '';
        $data['_id'] = $cachekey;
        
        $collection->insert($data);
    }
    
    /**
     * 删除用户缓存信息
     * @param string $username
     */
    public static function removeCache($username)
    {
        $username = strtolower($username);
        $config = Zeed_Config::loadGroup('accountcache');
        if (empty($config) || empty($config['db']) || empty($config['table'])) {
            throw new Exception('用户信息缓存未配置');
        }
        $connection = new Mongo($config['server'], $config['server_options']);
        $connection->connect();
        $cachedb = $config['db'];
        $cachetable = $config['table'];
        $db = $connection->$cachedb;
        $collection = $db->$cachetable;
        $cachekey = strval(md5('uinfo-' . $username));
        
        $info = $collection->findOne(array('_id' => $cachekey));
        if (!empty($info)) {
            $rs = $collection->remove(array('_id' => $cachekey), array('fsync' => true));
            if($rs['ok']) {
            	return true;
            }
            return false;
        }
        return true;
    }
}

// End ^ Native EOL ^ encoding
