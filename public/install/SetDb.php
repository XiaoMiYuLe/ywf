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
class Install_SetDb
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
     * 初始化数据
     * 
     * @param string $db_user 数据库连接用户名
     * @param string $db_password 数据库连接用户密码
     * @param string $db_name 数据库名
     * @param string $db_host 数据库连接地址
     * @return boolean
     */
    public static function run($db_username, $db_password, $db_name, $db_host)
    {
        try{
            /* 连接数据库 */
            self::$_connect =  @mysql_connect($db_host, $db_username, $db_password);
            if (! self::$_connect) {
                throw new Zeed_Exception('数据库连接失败');
            }
            
            /* 组织要导入的 sql 文件地址 */
            $path_db = ZEED_BOOT . 'install/db';
            $dir_db = opendir($path_db);
            
            /* 导入基础包数据 */
            $path_db_base = $path_db . '/bm_basic.sql';
            self::import($path_db_base, $db_name);
            
            /* 遍历数据库 sql 文件，进行数据导入 */
            while (($file = readdir($dir_db)) !== false) { // readdir() 返回打开目录句柄中的一个条目
                $condistion = $file != '.' && $file != '..' && $file != 'bm_basic.sql' && $file != 'db_extend.sql' && ! is_dir($file);
                if ($condistion) {
                    $path_db_sub = $path_db . '/' . $file;
        			self::import($path_db_sub, $db_name);
        		}
            }
            
            /* 导入扩展 sql 文件 */
            $path_db_extend = $path_db . '/db_extend.sql';
            self::import($path_db_extend, $db_name);
        } catch (Zeed_Exception $e) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '数据初始化失败。错误信息：' . $e->getMessage();
        }
        
        return self::$_res;
    }
    
    /**
     * 执行 sql 文件的导入
     * 
     * @param string $sql_file sql 文件地址
     * @param string $db_name  需要导入的目标数据库名称
     * @return boolean
     */
    private static function import($sql_file, $db_name)
    {
        if (! file_exists($sql_file)) {
            return false;
        }
        
        $sql_query = fread(fopen($sql_file, 'r'), filesize($sql_file));
        $sql_query = trim(stripslashes($sql_query));
        
        if ($sql_query) {
            $sql_query = self::removeRemarks($sql_query);
            $pieces = self::splitSqlFile($sql_query, ';');
            $pieces_count = count($pieces);
            if (mysql_select_db($db_name, self::$_connect)) {
                for ($i = 0; $i < $pieces_count; $i ++) {
                    $a_sql_query = trim($pieces[$i]);
                    if (! empty($a_sql_query) && $a_sql_query[0] != '#') {
                        @mysql_query($a_sql_query);
                    }
                }
            }
        }
        
        return true;
    }
    
    /**
     * 移除 sql 语句中的无实际作用的标签
     * 
     * @param string $sql sql 语句
     * @return string
     */
    private static function removeRemarks($sql)
    {
        $i = 0;
        while ($i < strlen($sql)) {
            if ($sql[$i] == '#' && ($i == 0 || $sql[$i-1] == "\n")) {
                $j = 1;
                while ($sql[$i + $j] != "\n") {
                    $j++;
                    if ($j + $i > strlen($sql)) {
                        break;
                    }
                }
                $sql = substr($sql, 0, $i) . substr($sql, $i + $j);
            }
            $i++;
        }
        return $sql;
    }
    
    /**
     * 切割 sql 语句
     * 
     * @param string $sql sql 语句
     * @param string $delimiter 分隔符
     * @return array
     */
    private static function splitSqlFile($sql, $delimiter)
    {
        $sql = trim($sql);
        $char = '';
        $last_char = '';
        $ret = array();
        $string_start = '';
        $in_string = false;
        $escaped_backslash = false;
    
        for ($i = 0; $i < strlen($sql); ++ $i) {
            $char = $sql[$i];
            
            // if delimiter found, add the parsed part to the returned array
            if ($char == $delimiter && ! $in_string) {
                $ret[] = substr($sql, 0, $i);
                $sql = substr($sql, $i + 1);
                $i = 0;
                $last_char = '';
            }
            
            if ($in_string) {
                // We are in a string, first check for escaped backslashes
                if ($char == '\\') {
                    if ($last_char != '\\') {
                        $escaped_backslash = false;
                    } else {
                        $escaped_backslash = ! $escaped_backslash;
                    }
                }
                // then check for not escaped end of strings except for
                // backquotes than cannot be escaped
                if (($char == $string_start) && ($char == '`' || ! (($last_char == '\\') && ! $escaped_backslash))) {
                    $in_string = false;
                    $string_start = '';
                }
            } else {
                // we are not in a string, check for start of strings
                if (($char == '"') || ($char == '\'') || ($char == '`')) {
                    $in_string = true;
                    $string_start = $char;
                }
            }
            $last_char = $char;
        }
          
        // add any rest to the returned array
        if (! empty($sql)) {
            $ret[] = $sql;
        }
        
        return $ret;
    }
}

// End ^ Native EOL ^ UTF-8