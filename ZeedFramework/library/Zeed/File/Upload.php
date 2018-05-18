<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      May 11, 2010
 * @version    SVN: $Id$
 */

class Zeed_File_Upload
{
    protected $_error = array();
    protected $_data = array();
    protected $_queue = array();
    
    protected $_options = array(
            'max_size' => 2048000, // 2000k
            'max_width' => 1920,
            'max_height' => 1200,
            'allowed_types' => '', // 未实现
            'upload_path' => '',
            'upload_url' => '',
            'overwrite' => FALSE,
            'encrypt_name' => FALSE, // 未实现
            'mimes' => array(), // 未实现
            'remove_spaces' => TRUE, // 未实现
            'xss_clean' => FALSE, // 未实现
            'archive_date' => '',
            'archive_mime' => FALSE);
    
    /**
     * @param Array $config
     */
    public function __construct($config = array())
    {
        $this->setConfig($config);
    }
    
    /**
     * 处理数组方式的上传
     *
     * @param Array $arr
     * @param Array $arr1
     * @param Array $arr2
     * @param Array $arr3
     * @param String $return
     * @return Array
     */
    public function _prepareArr($errorArr, $nameArr, $tmpArr, $sizeArr, $return = 'c')
    {
        if (is_array($errorArr) and count($errorArr) > 0) {
            foreach ($errorArr as $k => $v) {
                if (is_array($v)) {
                    $r = $this->_prepareArr($v, $nameArr . '[' . $k . ']', $tmpArr . '[' . $k . ']', $sizeArr . '[' . $k . ']', $return);
                    $str = '$' . $return . '[' . $k . '] = $r;';
                    @eval($str);
                } else {
                    if ($v == 4)
                        continue;
                    
                    $str = '$name = ' . $nameArr . '[' . $k . '];';
                    $str .= '$tmp_name = ' . $tmpArr . '[' . $k . '];';
                    $str .= '$size = ' . $sizeArr . '[' . $k . '];';
                    @eval($str);
                    
                    $ret = $this->_prepare($name, $tmp_name, $size);
                    $str = '$' . $return . '[' . $k . '] = $ret;';
                    eval($str);
                }
            }
        }
        
        return $$return;
    }
    
    /**
     * 预处理上传文件
     *
     * @param String $name
     * @param String $tmp_name
     * @param Integer $size
     * @return Array
     */
    private function _prepare($name, $tmp_name, $size)
    {
        $data = array();
        $data['name'] = $name;
        $data['tmp_name'] = $tmp_name;
        if (file_exists($tmp_name)) {
            $data['type'] = Zeed_File::mime($tmp_name);
            $data['hash'] = md5_file($tmp_name);
            $data['size'] = $size;
            $data['target'] = $this->generateTargetFilename($name, $data['type']);
            $data['target_exist'] = (file_exists($this->generateTargetFilename($name, $data['type']))) ? 1 : 0;
            if ($size > $this->getConfig('max_size')) {
                $data['error'] = true;
                $data['error_msg'] = 'Invalid filesize.';
            } elseif ($this->isImage($data['type'])) {
                $imgProperties = $this->getImageProperties($tmp_name);
                $data = array_merge($data, $imgProperties);
            }
        } else {
            $data['type'] = null;
            $data['hash'] = null;
            $data['size'] = 0;
        }
        
        // 加入待处理队列,去除重复(MD5值相同)的文件
        $this->_queue[$data['hash']] = $data;
        
        return $data;
    }
    
    /**
     * 上传到服务器上后移动到目标目录前的准备工作
     * 
     * @param string $field 为空时处理所有
     * @return array 一维或多维数组
     */
    public function prepare($field = null)
    {
        if (is_null($field)) {
            $fields = array_keys($_FILES);
            $ret = array();
            foreach ($fields as $field) {
                $ret[$field] = $this->prepare($field);
            }
            $this->_data = $ret;
            return $ret;
        }
        
        $_FILES_error = $_FILES[$field]['error'];
        if (is_array($_FILES_error)) {
            $this->_data = $this->_prepareArr($_FILES_error, '$_FILES[' . $field . '][name]', '$_FILES[' . $field . '][tmp_name]', '$_FILES[' . $field . '][size]');
        } else {
            $this->_data = $this->_prepare($_FILES[$field]['name'], $_FILES[$field]['tmp_name'], $_FILES[$field]['size']);
        }
        
        return $this->_data;
    }
    
    /**
     * 处理(从临时目录向目标目录移动)单个文件
     *
     * @param string $hashCode
     * @param string $newName
     * @param boolean $overwrite
     * @return boolean|array
     */
    public function doUpload($hashCode, $newName = null, $overwrite = false)
    {
        if (isset($this->_queue[$hashCode])) {
            $t = $this->_queue[$hashCode];
            if (! is_null($newName)) {
                $t['target'] = dirname($t['target']) . '/' . $this->cleanFilename($newName);
            }
            if (! $overwrite) {
                $t['target'] = Zeed_File::autoRenameFile($t['target']);
            }
            unset($this->_queue[$hashCode]);
            if ($this->move_uploaded_file($t['tmp_name'], $t['target'])) {
                $t['url'] = str_replace($this->getUploadPath(), "", $t['target']);
                return $t;
            } else {
                return false;
            }
        }
        
        return false;
    }
    
    /**
     * @return array
     */
    public function doUploadAll()
    {
        if (count($this->_queue) > 0) {
            $ret = array();
            reset($this->_queue);
            while (list($k, $t) = each($this->_queue)) {
                $ret[$k] = $this->doUpload($k, null, $this->getConfig('overwrite'));
            }
        } else {
            $ret = null;
        }
        
        return $ret;
    }
    
    /**
     * @param string $tmp_name
     * @param string $targetFile
     * @return boolean
     */
    protected function move_uploaded_file($tmp_name, $targetFile)
    {
        if (! is_dir($targetFolder = dirname($targetFile))) {
            Zeed_Util::mkpath($targetFolder);
        }
        if (! @move_uploaded_file($tmp_name, $targetFile)) {
            if (! @copy($this->file_temp, $this->upload_path . $this->file_name)) {
                return false;
            }
        }
        
        // 添加到同步队列
        

        return true;
    
    }
    
    /**
     * 生成目标地址
     *
     * @param String $name
     * @param String $mimeType
     * @return String
     */
    public function generateTargetFilename($name, $mimeType = null)
    {
        $path = $this->getUploadPath();
        if ($this->getConfig('archive_mime')) {
            if (preg_match('/([a-z]{1,})\//', $mimeType, $mm)) {
                $path .= $mm[1] . '/';
            }
        }
        
        if ($this->getConfig('archive_date') != '') {
            $path .= '' . date($this->getConfig('archive_date')) . '/';
        }
        return $path . $this->cleanFilename($name);
    }
    
    /**
     * @return String
     */
    public function getUploadPath()
    {
        $path = $this->getConfig('upload_path');
        $path = str_replace("\\", "|", $path);
        $path = str_replace("/", "|", $path);
        $path = str_replace("||", "|", $path);
        $path = str_replace("|", "/", $path);
        $path = (substr($path, - 1) == '/') ? $path : $path . '/';
        
        return $path;
    }
    
    /**
     * 清理文件名种的非法字符
     * 
     * @see Zeed_File::filename_security()
     * @param String $filename
     * @return String
     */
    public function cleanFilename($filename)
    {
        return Zeed_File::filename_security($filename);
    }
    
    /**
     * 根据MimeType判断是否为图片
     * 只处理JPG/PNG/GIF
     *
     * @param String $fileMimeType
     * @return Boolean
     */
    public function isImage($fileMimeType)
    {
        $png_mimes = array(
                'image/x-png');
        $jpeg_mimes = array(
                'image/jpg',
                'image/jpe',
                'image/jpeg',
                'image/pjpeg');
        
        if (in_array($fileMimeType, $png_mimes)) {
            $fileMimeType = 'image/png';
        }
        
        if (in_array($fileMimeType, $jpeg_mimes)) {
            $fileMimeType = 'image/jpeg';
        }
        
        $img_mimes = array(
                'image/gif',
                'image/jpeg',
                'image/png');
        
        return (in_array($fileMimeType, $img_mimes, TRUE)) ? TRUE : FALSE;
    }
    
    /**
     * 获取图片文件的宽高等信息.
     * 该函数不判断参数文件是否为图片,如果需要判断,参见UtilUpload::isImage($fileMimeType)
     *
     * @param String $path
     * @return Array|Null
     */
    public function getImageProperties($path)
    {
        if (function_exists('getimagesize')) {
            $ret = array();
            if (FALSE !== ($D = @getimagesize($path))) {
                $types = array(
                        1 => 'gif',
                        2 => 'jpeg',
                        3 => 'png');
                
                $ret['image_width'] = $D['0'];
                $ret['image_height'] = $D['1'];
                $ret['image_type'] = (! isset($types[$D['2']])) ? 'unknown' : $types[$D['2']];
                // $ret['image_size_str']	= $D['3'];  // string containing height and width
            }
            
            if ($ret['image_width'] > $this->getConfig('max_width')) {
                $ret['error'] = true;
                $ret['error_msg'] = 'Invalid dimensions.';
            }
            
            return $ret;
        }
        
        return null;
    }
    
    /**
     * Set configuration
     *
     * @param Array $config
     * @return UtilUpload
     */
    public function setConfig($config = array())
    {
        if (is_array($config)) {
            $paramsAllow = array_keys($this->_options);
            while (list($key, $val) = each($config)) {
                if (in_array($key, $paramsAllow)) {
                    $this->_options[$key] = $val;
                }
            }
        }
        
        return $this;
    }
    
    /**
     * 获取当前的配置值
     *
     * @param String|Array $confIndex
     */
    public function getConfig($confIndex)
    {
        if (! is_array($confIndex)) {
            if (isset($this->_options[$confIndex])) {
                return $this->_options[$confIndex];
            } else {
                throw new Zeed_Exception('Invalid parameter:<b>' . $confIndex . '</b>');
            }
        }
        
        if (is_array($confIndex) and count($confIndex) > 0) {
            $ret = array();
            while (list(, $key) = each($confIndex)) {
                
                if (isset($this->_options[$key])) {
                    $ret[$key] = $this->_options[$key];
                } else {
                    throw new Zeed_Exception('Invalid parameter:<b>' . $key . '</b>');
                }
            }
            
            return $ret;
        }
    }
    
    /**
     * Runs the file through the XSS clean function
     *
     * This prevents people from embedding malicious code in their files.
     * I'm not sure that it won't negatively affect certain files in unexpected ways,
     * but so far I haven't found that it causes trouble.
     *
     * @access	public
     * @return	void
     */
    function do_xss_clean()
    {
        require_once (dirname(__FILE__) . '/../misc/XSSClean.php');
        $file = $this->upload_path . $this->file_name;
        
        if (filesize($file) == 0) {
            return FALSE;
        }
        
        if (($data = @file_get_contents($file)) === FALSE) {
            return FALSE;
        }
        
        if (! $fp = @fopen($file, 'r+b')) {
            return FALSE;
        }
        
        $data = fread($fp, filesize($file));
        $data = XSSClean::instance()->clean($data);
        
        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    
    public function getData()
    {
        return $this->_data;
    }
    
    public function getQueue()
    {
        return $this->_queue;
    }

}

// End ^ LF ^ encoding
