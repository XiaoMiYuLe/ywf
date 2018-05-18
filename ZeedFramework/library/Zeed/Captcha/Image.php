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
 * @since      May 7, 2010
 * @version    SVN: $Id$
 */

class Zeed_Captcha_Image
{
    protected $_options = array(
            'width' => 200, 
            'height' => 70, 
            'font' => null, 
            'fontColor' => array(0x0000FF, 0xFF0000, 0x009900, 0x0000FF), 
            'wordLen' => 6, 
            'wordUseNumbers' => true, 
            'backgroundColor' => 0xFFFFFF);
    
    protected $_word;
    protected $_id;
    
    static $V = array("a", "e", "i", "o", "u", "y");
    static $VN = array("a", "e", "i", "o", "u", "y", "2", "3", "4", "5", "6", "7", "8", "9");
    static $C = array(
            "b", 
            "c", 
            "d", 
            "f", 
            "g", 
            "h", 
            "j", 
            "k", 
            "m", 
            "n", 
            "p", 
            "q", 
            "r", 
            "s", 
            "t", 
            "u", 
            "v", 
            "w", 
            "x", 
            "z");
    static $CN = array(
            "b", 
            "c", 
            "d", 
            "f", 
            "g", 
            "h", 
            "j", 
            "k", 
            "m", 
            "n", 
            "p", 
            "q", 
            "r", 
            "s", 
            "t", 
            "u", 
            "v", 
            "w", 
            "x", 
            "z", 
            "2", 
            "3", 
            "4", 
            "5", 
            "6", 
            "7", 
            "8", 
            "9");
    
    public function __construct($config = null)
    {
        if (is_array($config)) {
            foreach ($config as $k => $v) {
                if (isset($this->_options[$k])) {
                    $this->_options[$k] = $v;
                }
            }
        }
    }
    
    /**
     * @return string
     */
    public function generate()
    {
        $id = $this->_generateRandomId();
        $this->_word = $this->_generateWord();
        self::setValidWord($id, $this->_word);
        
        return $id;
    }
    
    public function display()
    {
        $word = ! empty($this->_word) ? $this->_word : $this->_generateWord();
        $this->createImage($word);
    }
    
    /**
     * @return string
     */
    protected function _generateWord()
    {
        $word = '';
        $wordLen = $this->_options['wordLen'];
        if ($this->_options['wordUseNumbers']) {
            $vowels = $this->_options['wordUseNumbers'] ? self::$VN : self::$V;
            $consonants = $this->_options['wordUseNumbers'] ? self::$CN : self::$C;
        } else {
            $vowels = $this->_options['wordUseNumbers'] ? self::$VN : self::$V;
            $consonants = $this->_options['wordUseNumbers'] ? self::$CN : self::$C;
        }
        
        for ($i = 0; $i < $wordLen; $i = $i + 2) {
            $consonant = $consonants[array_rand($consonants)];
            $vowel = $vowels[array_rand($vowels)];
            $word .= $consonant . $vowel;
        }
        
        if (strlen($word) > $wordLen) {
            $word = substr($word, 0, $wordLen);
        }
        
        return $word;
    }
    
    /**
     * @return string
     */
    protected function _generateRandomId()
    {
        return md5(mt_rand(0, 1000) . microtime(true));
    }
    
    /**
     *
     * @param string $id
     * @param string $word
     * @return boolean
     */
    public static function isValid($id, $word)
    {
        $k = 'Zeed_Captcha_' . $id;
        
        if (isset($_SESSION[$k]) && strtolower($word) == strtolower($_SESSION[$k])) {
            unset($_SESSION[$k]);
            return true;
        }
        
        $_SESSION[$k] = null;
        return false;
    }
    
    /**
     * 获取指定ID正确的验证字串
     * 
     * @param string $id
     * @return string
     */
    public static function getValidWord($id)
    {
        $k = 'Zeed_Captcha_' . $id;
        return isset($_SESSION[$k]) ? $_SESSION[$k] : null;
    }
    
    /**
     * @todo 改进存储, 增加失效时间等
     * @param string $word
     */
    public static function setValidWord($id, $word)
    {
        $k = 'Zeed_Captcha_' . $id;
        $_SESSION[$k] = $word;
    }
    
    /**
     *
     * @param string|array $key
     * @param mixed $val
     * @return Zeed_Captcha_Image
     */
    public function setParam($key, $val = null)
    {
        $this->_options[$key] = $val;
        
        return $this;
    }
    
    public function setWord($word = null)
    {
        if (empty($word)) {
            $word = $this->_generateWord();
        }
        $this->_word = $word;
        self::setValidWord($this->_id, $word);
        
        return $this;
    }
    
    public function getWord()
    {
        return $this->_word;
    }
    
    public function setId($id)
    {
        $this->_id = $id;
        
        return $this;
    }
    
    public function getWordLen()
    {
        return $this->_options['wordLen'];
    }
    
    public function setWordlen($wordlen)
    {
        $this->_options['wordLen'] = $wordlen;
        return $this;
    }
    
    public function createImage($text = null)
    {
        if (! $this->_options['width'] || ! is_int($this->_options['width']))
            trigger_error("\$config['width'] not int > 0", E_USER_ERROR);
        
        if (! $this->_options['height'] || ! is_int($this->_options['height']))
            trigger_error("\$config['height'] not int > 0", E_USER_ERROR);
        
        if (! $this->_options['font'])
            trigger_error("\$config['font'] not defined", E_USER_ERROR);
        
        if (! $text) {
            $text = substr(md5(microtime()), 0, 6);
        }
        
        $fontSize = (int) $this->_options['height'] * 1;
        
        $height = $this->_options['height'];
        $width = $this->_options['width'];
        
        /**
         * 根据字体计算文本框图形大小
         */
        $angle = rand(- 8, 8);
        $temp_info = imageftbbox($fontSize, $angle, $this->_options['font'], $text);
        $temp_width1 = abs($temp_info[0]) + abs($temp_info[4]);
        $temp_height1 = abs($temp_info[1]) + abs($temp_info[5]);
        $temp_width2 = abs($temp_info[6]) + abs($temp_info[2]);
        $temp_height2 = abs($temp_info[3]) + abs($temp_info[7]);
        $temp_width = 3 + ($temp_width1 > $temp_width2 ? $temp_width1 : $temp_width2);
        $temp_height = 3 + ($temp_height1 > $temp_height2 ? $temp_height1 : $temp_height2);
        $xcoord = $temp_info[0] > $temp_info[6] ? $temp_info[0] : $temp_info[6];
        $ycoord = $temp_info[7] > $temp_info[5] ? - $temp_info[5] : - $temp_info[7];
        // Zeed_Benchmark::print_r($temp_info);
        // Zeed_Benchmark::print_r($temp_width);
        // Zeed_Benchmark::print_r($temp_height);
        // exit;
        

        // 创建一个文本图
        $textImage = imagecreatetruecolor($temp_width, $temp_height);
        imagefilledrectangle($textImage, 0, 0, $temp_width - 1, $temp_height - 1, $this->_options['backgroundColor']);
        $fontColor = $this->_options['fontColor'][array_rand($this->_options['fontColor'], 1)];
        
        $t_xcoord = $xcoord + 10;
        $t_ycoord = $ycoord + 2;
        $t_fontsize = ($fontSize * rand(90, 95) / 100);
        $t_angle = $angle + rand(-3, 3);
        for ($t_i = 0; $t_i < strlen($text); $t_i++) {
            $c = substr($text, $t_i, 1);
            //$c = rand(0, 2) == 2 ? strtoupper($c) : $c;
            imagefttext($textImage, $t_fontsize, $t_angle, $t_xcoord, $t_ycoord, $fontColor, $this->_options['font'], $c);
            $t_xcoord = $t_xcoord + $t_fontsize - rand($t_fontsize*0.26, $t_fontsize*0.40);
            $t_fontsize = ($fontSize * rand(90, 95) / 100);
            $t_angle = $angle + rand(-3, 3);
        }
        
        //imagefttext($textImage, $fontSize * 0.95, $angle, $xcoord + 10, $ycoord + 2, $fontColor, $this->_options['font'], $text);
        
        // 根据设定的大小缩小文本图
        $image_final = imagecreatetruecolor($width, $height);
        imagefilledrectangle($image_final, 0, 0, $width - 1, $height - 1, $this->_options['backgroundColor']);
        imagecopyresized($image_final, $textImage, 0, 0, 0, 0, $width, $height, $temp_width, $temp_height);
        // 变形, 增加干扰
        self::_distortion($image_final, $this->_options['backgroundColor'], $width, $height);
        
        header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
        header("Content-type: image/jpeg");
        imagejpeg($image_final, null, 80);
        imagedestroy($textImage);
        imagedestroy($image_final);
    }
    
    /**
     *
     *
     * @param resource $image
     * @param string $background
     * @param integer $width
     * @param integer $height
     */
    private static function _distortion(&$image, $background, $width = 0, $height = 0)
    {
        if (! $width)
            $width = imagesx($image);
        if (! $height)
            $height = imagesy($image);
        
        $orig = imagecreatetruecolor($width, $height);
        imagecopy($orig, $image, 0, 0, 0, 0, $width, $height);
        imagefilledrectangle($image, 0, 0, $width, $height, $background);
        
        $v_f1 = rand(200, 250) / 100; //  od 2 do 3 za fju
        $v_w1 = rand(0, $width * 100) / 100; // od 0 do width
        

        $v_f2 = rand(40, 60) / 100; // od 0.1 do 1 za prigusenje
        $v_w2 = rand(0, $width * 100) / 100; // od 0 do width
        

        $v_f3 = rand(40, 60) / 100; // od 0.1 do 1 za prigusenje dolje
        $v_w3 = rand(0, $width * 100) / 100; // od 0 do width
        

        $y4_max = 0;
        $y5_max = 0;
        
        $an = array();
        $as = array();
        
        for ($x = 0; $x < $width; $x ++) {
            if ($x % 9 == 0) {
                //$x += rand(0, 1);
                //continue;
            }
            $y1 = self::_sin($x, $v_f1, $v_w1, $width, $height);
            $y2 = self::_sin($x, $v_f2, $v_w2, $width, $height);
            $y3 = self::_sin($x, $v_f3, $v_w3, $width, $height);
            
            $y4 = $y1 * $y2 / $height / 3;
            $y5 = $y1 * $y3 / $height / 3;
            
            $an[$x] = $y4;
            $as[$x] = $y5;
            
            if ($y4 > $y4_max)
                $y4_max = $y4;
            if ($y5 > $y5_max)
                $y5_max = $y5;
        }
        
        for ($x = 0; $x < $width; $x ++) {
            @$as[$x] = $height - $y5_max - 1 + $as[$x];
            if ($x % 10 == 0) {
                //continue;
            }
            for ($y = 0; $y < $height; $y ++) {
                if ($y % 10 == 0) {
                    $y += rand(0, 1);
                    continue;
                }
                @imagesetpixel($image, $x, self::_y($y, $an[$x], $as[$x], $height), imagecolorat($orig, $x, $y));
            }
        }
    }
    
    private static function _sin($x, $f, $w, $width, $height)
    {
        return (int) ($height / 2) * (1 - sin($f * 2 * M_PI * ($x + $w) / $width));
    }
    
    private static function _y($y, $b1, $b2, $height)
    {
        return (int) $b1 + ($y / $height * ($b2 - $b1));
    }
}

// End ^ LF ^ encoding
