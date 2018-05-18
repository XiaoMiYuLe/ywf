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
class Install_CheckSystem
{
    /**
     * 定义 PHP 允许的最低版本
     */
    const PHP_VERSION_MIN = '5.3';
    
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    /**
     * 检查系统
     */
    public static function run()
    {
        $str_checksystem = '';
        
        /* 检查 PHP 版本 */
        $result_check_php_version = self::checkPHPVersion();
        self::$_res['status'] = $result_check_php_version['status'];
        $str_checksystem .= $result_check_php_version['data'];
        
        /* 检查 PDO 相关扩展 */
        $result_check_pdo = self::checkPdo();
        self::$_res['status'] = $result_check_pdo['status'] ^ self::$_res['status'];
        $str_checksystem .= $result_check_pdo['data'];
        
        /* 检查其他一些扩展 */
        $result_check_other_extension = self::checkOtherExtension();
        self::$_res['status'] = $result_check_other_extension['status'] ^ self::$_res['status'];
        $str_checksystem .= $result_check_other_extension['data'];
        
        /* 检查必需的函数 */
        $result_check_functions = self::checkFunctions();
        self::$_res['status'] = $result_check_functions['status'] ^ self::$_res['status'];
        $str_checksystem .= $result_check_functions['data'];
        
        /* 检查目录写入权限 */
        $result_check_folder_permission = self::checkFolderPermission();
        self::$_res['status'] = $result_check_folder_permission['status'] ^ self::$_res['status'];
        $str_checksystem .= $result_check_folder_permission['data'];
        
        self::$_res['data'] = $str_checksystem;
        return self::$_res;
    }
    
    /**
     * 检查 PHP 版本
     */
    private static function checkPHPVersion()
    {
        $res = array('status' => 0, 'data' => '');
        
        $php_version_now = PHP_VERSION;
        $check_status_php_version = array('color' => 'text-success', 'status' => 'fa-check');
        if ($php_version_now < self::PHP_VERSION_MIN) {
            $res['status'] = 1;
            $check_status_php_version = array('color' => 'red', 'status' => 'fa-times');
        }
        $res['data'] .= '<tr>' . 
                '<td>PHP 版本 >= 5.3.3</td>' . 
                '<td>当前版本 ' . $php_version_now . '</td>' . 
                '<td class="' . $check_status_php_version['color'] . '">' . 
                '<i class="fa ' . $check_status_php_version['status'] . '"></i>' . 
                '</td>' . 
                '</tr>';
        
        return $res;
    }
    
    /**
     * 检查 PDO 相关扩展
     */
    private static function checkPdo()
    {
        $res = array('status' => 0, 'data' => '');
        
        /* 检查 PDO */
        $result_check_pdo = self::isPhpExtensionLoaded('PDO', 'PDO 扩展');
        $res['status'] = $result_check_pdo['status'];
        $res['data'] .= $result_check_pdo['data'];
        
        /* 检查 pdo_mysql */
        $result_check_pdo_mysql = self::isPhpExtensionLoaded('pdo_mysql', 'PDO\MYSQL 扩展');
        $res['status'] = $result_check_pdo_mysql['status'];
        $res['data'] .= $result_check_pdo_mysql['data'];
        
        /* 检查 mysqli */
        $result_check_mysqli = self::isPhpExtensionLoaded('mysqli', 'MYSQLI 扩展');
        $res['status'] = $result_check_mysqli['status'];
        $res['data'] .= $result_check_mysqli['data'];
        
        return $res;
    }
    
    /**
     * 检查其他一些扩展
     */
    private static function checkOtherExtension()
    {
        $res = array('status' => 0, 'data' => '');
        
        $extensions = array('zlib', 'SPL', 'iconv', 'json', 'mbstring', 'Reflection');
        
        $result_check_mysqli = self::isPhpExtensionLoaded($extensions, '其他需要的扩展组件');
        $res['status'] = $result_check_mysqli['status'];
        $res['data'] .= $result_check_mysqli['data'];
        
        return $res;
    }
    
    /**
     * 检查必需的函数
     */
    private static function checkFunctions()
    {
        $res = array('status' => 0, 'data' => '');
        return $res;
    }
    
    /**
     * 检查目录写入权限
     */
    private static function checkFolderPermission()
    {
        $res = array('status' => 0, 'data' => '');
        
        $folders = array(
                ZEED_ROOT . 'application/Page/controllers', 
                ZEED_ROOT . 'config', 
                ZEED_ROOT . 'data', 
                ZEED_ROOT . 'data/cache', 
                ZEED_ROOT . 'data/log', 
                ZEED_ROOT . 'data/template_c', 
                ZEED_ROOT . 'data/tmp', 
                ZEED_BOOT . 'install', 
                ZEED_ROOT . 'upload'
        );
        
        $res['data'] .= '<tr>' .
                '<td rowspan="' . count($folders) . '">具写入权限的目录</td>';
        
        foreach ($folders as $v) {
            if (! is_dir($v)) {
                continue;
            }
            
            $check_status = array('color' => 'text-success', 'status' => 'fa-check');
            if (substr(sprintf("%o", fileperms($v)), -4) != '0777') {
                $res['status'] = 1;
                $check_status = array('color' => 'red', 'status' => 'fa-times');
            }
    
            $res['data'] .= '<td>' . $v . '</td>' .
                    '<td class="' . $check_status['color'] . '">' .
                    '<i class="fa ' . $check_status['status'] . '"></i>' .
                    '</td>' .
                    '</tr>';
        }
        
        return $res;
    }
    
    /**
     * 校验扩展是否已加载
     * 
     * @param string|array $extension 扩展名
     * @param string $extension_name 扩展标签名
     * @return array
     */
    private static function isPhpExtensionLoaded($extension, $extension_name)
    {
        $res = array('status' => 0, 'data' => '');
        
        if (is_string($extension)) {
            $extension = explode(',', $extension);
        }
        
        $res['data'] .= '<tr>' . 
                '<td rowspan="' . count($extension) . '">' . $extension_name . '</td>';
        
        if (! empty($extension)) {
            foreach ($extension as $v) {
                $check_status = array('color' => 'text-success', 'status' => 'fa-check');
                if (! extension_loaded($v)) {
                    $res['status'] = 1;
                    $check_status = array('color' => 'red', 'status' => 'fa-times');
                }
                
                $res['data'] .= '<td>' . $v . '</td>' . 
                        '<td class="' . $check_status['color'] . '">' . 
                        '<i class="fa ' . $check_status['status'] . '"></i>' . 
                        '</td>' . 
                        '</tr>';
            }
        }
        
        return $res;
    }
}

// End ^ Native EOL ^ UTF-8