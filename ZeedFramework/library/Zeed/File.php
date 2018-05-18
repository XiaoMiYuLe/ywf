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
 * @package    Zeed_File
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: View.php 6709 2010-09-08 13:47:02Z xsharp $
 */

/**
 * Zeed_File
 *
 * @todo       增加检测HTTP/FTP文件是否存在
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @subpackage Util
 * @author     xSharp ( GTalk/Email: xSharp@gmail.com | MSN: xSharp@msn.com )
 */
class Zeed_File
{
    /**
     * 大小写不敏感
     *
     * @param String $filename
     * @return Array 返回文件列表数组
     */
    public static function file_exists($filename)
    {
        return glob(dirname($filename) . '/' . sql_regcase(basename($filename)), GLOB_NOSORT);
    }
    
    /**
     * 自动更名
     */
    public static function autoRenameFile($filename, $i = 1)
    {
        if (file_exists($filename)) {
            $pos = strrpos($filename, '.');
            $fileExt = substr($filename, $pos);
            $fileNameAbs = substr($filename, 0, $pos);
            if (false != $pos = strrpos($fileNameAbs, '_')) {
                $fileNameAbs = substr($fileNameAbs, 0, $pos);
            }
            $filename = $fileNameAbs . '_' . $i . $fileExt;
            return self::autoRenameFile($filename, ++ $i);
        } else {
            return $filename;
        }
    
    }
    
    /**
     * 获取文件的内容, 支HTTP文件
     */
    public static function file_get_contents($filename)
    {
        if (preg_match('/(http|https|ftp|file){1}(:\/\/)/i', $filename)) {
            return self::getUrlFile($filename);
        }
        
        return file_get_contents($filename);
    }
    
    /**
     * 获取HTTP文件数据
     * 
     * @param String $filename
     * @param String $retureType
     * @return Zend_Http_Response|String|Null
     */
    public static function getUrlFile($filename, $retureType = null)
    {
        try {
            $client = new Zend_Http_Client($filename);
            $response = $client->request();
            return is_null($retureType) ? $response->getBody() : $response;
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_NOTICE);
            return null;
        }
    }
    
    /**
     * 获取URL Headers
     *
     * @param String $url
     * @return Array|Null
     */
    public static function getUrlHeader($url)
    {
        $fp = @fopen($url, 'r');
        if ($fp) {
            $meta = stream_get_meta_data($fp);
            fclose($fp);
            
            $headers = array();
            while (list(, $v) = each($meta['wrapper_data'])) {
                $_tmp = explode(':', $v);
                $key = trim($_tmp[0]);
                $val = trim(substr($v, strlen($_tmp[0]) + 1));
                
                $headers[$key] = $val;
            }
            
            return $headers;
        }
        
        return null;
    }
    
    /**
     * 检查URL是否可用(200)
     *
     * @param String $url_file
     * @return Boolean
     */
    public static function url_exists($url_file)
    {
        //检测输入
        $url_file = trim($url_file);
        if (empty($url_file)) {
            return false;
        }
        $url_arr = parse_url($url_file);
        if (! is_array($url_arr) || empty($url_arr)) {
            return false;
        }
        
        //获取请求数据
        $host = $url_arr['host'];
        $path = $url_arr['path'] . "?" . $url_arr['query'];
        $port = isset($url_arr['port']) ? $url_arr['port'] : "80";
        
        //连接服务器
        @$fp = fsockopen($host, $port, $err_no, $err_str, 10);
        if (! $fp) {
            return false;
        }
        
        //构造请求协议
        $request_str = "GET " . $path . " HTTP/1.1\r\n";
        $request_str .= "Host: " . $host . "\r\n";
        $request_str .= "Connection: Close\r\n\r\n";
        
        //发送请求
        fwrite($fp, $request_str);
        $first_header = fgets($fp, 1024);
        fclose($fp);
        
        //判断文件是否存在
        if (trim($first_header) == "") {
            return false;
        }
        if (! preg_match("/200/", $first_header)) {
            return false;
        }
        return true;
    }
    
    /**
     * 缓存网上的图片
     *
     * @param String $fileUrl
     * @return String|Boolean 磁盘路径
     */
    public static function snatchFile($fileUrl, $saveToDir)
    {
        // 处理空格
        $fileUrl = str_replace(array(
                ' '), array(
                '%20'), $fileUrl);
        
        $saveToDir = (substr($saveToDir, - 1) == '/') ? $saveToDir : $saveToDir . '/';
        $outputDir = $saveToDir;
        $filename = md5($fileUrl);
        
        $outputFile = $outputDir . substr($filename, 0, 2) . '/' . substr($filename, 2, 2) . '/' . $filename;
        $failMeta = $outputFile . '-failmeta';
        $failMetaData = array();
        
        /**
         * 文件已经存在,下列情况有效:
         * 1.大于0
         * 2.等于0,但是无 $failMeta
         */
        if (file_exists($outputFile) or file_exists($failMeta)) {
            if (file_exists($failMeta)) {
                $failMetaData = unserialize(file_get_contents($failMeta));
                // 失败超过3次不再抓取
                if (count($failMetaData) >= 3) {
                    return false;
                }
            }
            if (file_exists($outputFile)) {
                return $outputFile;
            }
        }
        
        /**
         * 当Header Status不等于200时记录
         */
        $requestObj = self::getUrlFile($fileUrl, 'obj');
        if (! $requestObj instanceof Zend_Http_Response) {
            $body = '';
            $headers = array();
            $code = '';
        } else {
            $body = $requestObj->getBody();
            $headers = $requestObj->getHeaders();
            $code = $requestObj->getStatus();
        }
        if ($code == '200') {
            // 成功
            if (file_exists($failMeta)) {
                @unlink($failMeta);
            }
            
            return (self::file_put_contents($outputFile, $body)) ? $outputFile : false;
        } else {
            // 失败
            $failMetaData[] = array(
                    'time' => date('Y-m-d H:i:s'),
                    'url' => $fileUrl,
                    'code' => $code,
                    'headers' => $headers);
            self::file_put_contents($failMeta, serialize($failMetaData));
            
            return $outputFile;
        }
    }
    
    /**
     * PHP内置 file_put_contents 函数的增强
     * 增加了自动创建不存在的目录(路径)
     *
     * @see file_put_contents
     * @param String $filename
     * @param String $data
     * @param String $mode
     * @return Integer
     */
    public static function file_put_contents($filename, $data, $mode = null)
    {
        $filename = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $filename);
        $filename_dir = dirname($filename);
        if (! is_dir($filename_dir)) {
            Zeed_Util::mkpath($filename_dir);
        }

        if (is_null($mode)) {
            $mode = 'w';
        }
        
        if (! file_exists($filename) || is_writable($filename)) {
            if (! $handle = fopen($filename, $mode)) {
                return false;
            }
            
            if (fwrite($handle, $data) === FALSE) {
                return false;
            }
            
            fclose($handle);
            @chmod($filename, 0666);
        } else {
            return false;
        }
        
        return true;
    }
    
    public static function writeSync($filename, $data)
    {
        self::file_put_contents($filename, $data);
    }
    
    /**
     * Remove a file or dir,empty or nonempty
     *
     * @param String $f
     * @return Boolean
     */
    public static function unlink($f)
    {
        if (substr($f, - 1) == '/')
            $folder = substr($f, 0, - 1);
        
        if (is_dir($f) && ! is_link($f)) {
            foreach (scandir($f) as $item) {
                if (! strcmp($item, '.') || ! strcmp($item, '..'))
                    continue;
                if (! Zeed_File::unlink($f . "/" . $item))
                    return false;
            }
            if (! @rmdir($f))
                return false;
        } else {
            if (! @unlink($f))
                return false;
        }
        return true;
    }
    
    /**
     * Filename Security
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public static function filename_security($str)
    {
        $bad = array(
                "+",
                "（",
                "）",
                "../",
                "./",
                "<!--",
                "-->",
                "<",
                ">",
                "'",
                '"',
                '&',
                '$',
                '#',
                '{',
                '}',
                '[',
                ']',
                '=',
                ';',
                '?',
                "%20",
                "%22",
                "%3c", // <
                "%253c", // <
                "%3e", // >
                "%0e", // >
                "%28", // (  
                "%29", // ) 
                "%2528", // (
                "%26", // &
                "%24", // $
                "%3f", // ?
                "%3b", // ;
                "%3d"); // =
        
        $repalce = array('_');
        
        $str = stripslashes(str_replace($bad, $repalce, $str));
        
        $c = Zeed_Util_Zh::isUGB($str);
        if ($c != '') {
            $str = Zeed_Util_Zh::pinyin(iconv($c, 'GBK', $str));
        }
        
        // 变态的字符统统去掉
        $str = preg_replace('/[^\w\.\(\)\-\,]/', '_', $str);
        
        return $str;
    }
    
    /**
     * Check if  $dir is a directory and it is empty
     *
     * @param string $dirname
     * @return boolean
     */
    public static function is_empty_dir($dirname)
    {
        $result = false;
        if (is_dir($dirname)) {
            $result = true;
            $handle = opendir($dirname);
            while (($name = readdir($handle)) !== false) {
                if ($name != "." && $name != "..") {
                    $result = false;
                    break;
                }
            }
            closedir($handle);
        }
        
        return $result;
    }
    
    /**
     * @var finfo
     */
    private static $_finfo;
    
    /**
     * @return finfo
     */
    public static function finfo()
    {
        if (is_null(self::$_finfo)) {
            if (DIRECTORY_SEPARATOR == '/') {
                // *nix
                $magicPath = self::findNixMagicFile();
            } else {
                // win
                $magicPath = ZEED_PATH_DATA . '/mime/win/magic';
            }
            self::$_finfo = new finfo(FILEINFO_MIME, $magicPath);
        }
        
        return self::$_finfo;
    }
    
    /**
     * Return information about a file
     *
     * @param String $file
     * @return String
     */
    public static function mime($file)
    {
        if (class_exists('finfo')) {
            return self::finfo()->file($file);
        } else {
            if (DIRECTORY_SEPARATOR == '/') {
                // *nix
                $fileBin = '/usr/bin/file';
                $magicPath = self::findNixMagicFile();
            } else {
                // win
                $fileBin = PP_ROOT . '/../shell/win/file.exe';
                $magicPath = ZEED_PATH_DATA . '/mime/win/magic';
            }
            $cmd = $fileBin . ' -i -b -m ' . $magicPath . ' ' . $file;
            $info = array();
            exec($cmd, $info);
            
            return $info[0];
        }
        
        die('Get file\'s MimeType Fail.');
    }
    
    /**
     * @return string
     */
    private static function findNixMagicFile()
    {
        $magicPath = ZEED_PATH_DATA . '/mime/linux/magic';
        $searchpaths = array();
        $searchpaths[] = '/usr/share/file/magic'; // debian
        $searchpaths[] = '/usr/share/magic'; // centos
        foreach ($searchpaths as $_spath) {
            if (file_exists($_spath)) {
                $magicPath = $_spath;
                break;
            }
        }
        
        return $magicPath;
    }
    
    /**
     * 直接从内容中获取其MIMETYPE信息
     * 
     * @param string $buffer
     * @return string
     */
    public static function mimeBuffer($buffer)
    {
        return self::finfo()->buffer($buffer);
    }
    
    /**
     * Return icon url of a certain file
     *
     * @param String $filename
     * @param String $size
     * @return String
     */
    public static function icon($filename, $size = "S")
    {
        $ext = substr($filename, strrpos($filename, '.') + 1);
        $icon = "/static/common/icons/" . $ext . ".gif";
        if (file_exists(ZEED_PATH_VIEW . $icon)) {
            return $icon;
        }
        return "/static/common/icons/default.icon.gif";
    }

    /**
     * 获取文件行数
     * 
     * @param string $filename
     * @return integer file line number
     */
    public static function getFileLines($filename)
    {
        if (!is_readable($filename)) {
            throw new Zeed_Exception("file $filename can not readable.");
        }
        
        $lineNum = 0;
        
        if ($stream = fopen($filename, 'r')) {
            while (!feof($stream)) {
                fgets($stream); // Burn a line so feof will work correctly.
                $lineNum++;  // This will increment even for blank lines and even if the very last line is blank.
            }
        }

        return $lineNum;
    }
    
}

// End ^ LF ^ UTF-8