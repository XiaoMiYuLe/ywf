<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-10-26
 * @version SVN: $Id$
 */
class Install_SetAdminPassword
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    /**
     * 连接状态
     */
    protected static $_connect = false;
    
    /**
     * 设置超级管理员密码
     * 
     * @param string $db_user 数据库连接用户名
     * @param string $db_password 数据库连接用户密码
     * @param string $db_name 数据库名
     * @param string $db_host 数据库连接地址
     * @param string $password 超级管理员的密码
     * @return boolean
     */
    public static function run($db_username, $db_password, $db_name, $db_host, $password)
    {
        try{
            /* 连接数据库 */
            self::$_connect =  @mysql_connect($db_host, $db_username, $db_password);
            if (! self::$_connect) {
                throw new Zeed_Exception('数据库连接失败');
            }
            
            /* 获取超级管理员编译后的密码 */
            $salt = self::genRandomString(10);
            $password_str = self::getPassword($password, $salt);
            
            /* 更新超级管理员的密码 */
            if (mysql_select_db($db_name, self::$_connect)) {
                $sql = "UPDATE `admin_user` SET `password` = '{$password_str}', `salt` = '{$salt}' WHERE `username` = 'admin'";
                @mysql_query($sql);
            }
        } catch (Zeed_Exception $e) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '设置超级管理员密码失败。错误信息：' . $e->getMessage();
        }
        
        return self::$_res;
    }
    
    /**
     * 获取超级管理员编译后的密码
     * 
     * @param string $password 超级管理员的密码源字符串
     * @param string $salt 干扰码
     * @return string
     */
    private static function getPassword($password, $salt)
    {
        return md5(md5($password) . $salt);
    }
    
    /**
     * 创建随机字串
     *
     * @param integer $len 字串长度
     * @return string
     */
    private static function genRandomString($len = 16)
    {
        $chars = array(
                "a",
                "b",
                "c",
                "d",
                "e",
                "f",
                "g",
                "h",
                "i",
                "j",
                "k",
                "l",
                "m",
                "n",
                "o",
                "p",
                "q",
                "r",
                "s",
                "t",
                "u",
                "v",
                "w",
                "x",
                "y",
                "z",
                "A",
                "B",
                "C",
                "D",
                "E",
                "F",
                "G",
                "H",
                "I",
                "J",
                "K",
                "L",
                "M",
                "N",
                "O",
                "P",
                "Q",
                "R",
                "S",
                "T",
                "U",
                "V",
                "W",
                "X",
                "Y",
                "Z",
                "0",
                "1",
                "2",
                "3",
                "4",
                "5",
                "6",
                "7",
                "8",
                "9");
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = '';
        for ($i = 0; $i < $len; $i ++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        
        return $output;
    }
}

// End ^ Native EOL ^ UTF-8