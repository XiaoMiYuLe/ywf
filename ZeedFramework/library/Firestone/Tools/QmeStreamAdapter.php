<?php
/**
 * QmeStreamAdapter
 * 
 * @package Firestone_Tools
 * @since 2010-8-26
 * @author wida<wida@foxmail.com>
 */
class Firestone_Tools_QmeStreamAdapter
{
    
    /**
     * 
     * @handle
     */
    private $fileHandle = null;
    
    /**
     * 模式
     * @return QmeStreamAdapter
     */
    public static function instance($serverno)
    {
        static $object = array();
        if (!isset($object[$serverno]))
            $object[$serverno] = new self();
        
        return $object[$serverno];
    }
    
    /**
     * 
     * 初始化handle
     * @param handle $fileHandle
     * @return QmeStreamAdapter
     */
    public function setHandle($fileHandle)
    {
        $this->fileHandle = $fileHandle;
        stream_set_timeout($this->fileHandle, 1);
        return $this;
    }
    
    /**
     * 
     * @param string $packString 二进制包
     * @return Firestone_Tools_QmeStreamAdapter
     */
    public function send($packString, $length = null)
    {
        if (is_null($length)) {
            fwrite($this->fileHandle, $packString);
        } else {
            fwrite($this->fileHandle, $packString, $length);
        }
        
        return $this;
    }
    
    /**
     * @param int $size
     * @param srting|null $format
     * @return string
     */
    public function read($size, $format = null)
    {
        
        if (is_null($format) || empty($format)) {
            return fread($this->fileHandle, $size);
        } else {
            $str = fread($this->fileHandle, $size);
            if (empty($str))
                return; //探测header时str可能为空  为空时不处理放回
            return unpack($format, $str);
        }
    }
    
    /**
     * 移动文件指针
     * @param int $offset                            偏移量
     * @param SEEK_SET|SEEK_CUR|SEEK_END $whence  基准位置
     * @return QmeStreamAdapter
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        fseek($this->fileHandle, $offset, $whence);
        return $this;
    }
    
    /**
     * 重置文件指针到文件头
     * @return QmeStreamAdapter
     */
    public function frewind()
    {
        rewind($this->fileHandle);
        return $this;
    }
    
    /**
     * 获取文件大小
     * @return int 文件大小
     */
    public function getSize()
    {
        $whence = fseek($this->fileHandle, 0, SEEK_CUR);
        fseek($this->fileHandle, 0, SEEK_END);
        $cur = ftell($this->fileHandle);
        fseek($this->fileHandle, $whence, SEEK_SET);
        return $cur;
    }
    
    /**
     * 返回文件当前指针
     * @return int 
     */
    public function tell()
    {
        return ftell($this->fileHandle);
    }
    
    /**
     * destruct
     */
    function __destruct()
    {
        fclose($this->fileHandle);
    }

}

// End ^ Native EOL ^ encoding