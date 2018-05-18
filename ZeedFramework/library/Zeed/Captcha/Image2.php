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
 * @since      Aug 24, 2011
 * @version    SVN: $Id$
 */

/**
 * @link    http://code.google.com/p/cool-php-captcha
 */
class Zeed_Captcha_Image2
{
    
    /** Width of the image */
    public $width = 200;
    
    /** Height of the image */
    public $height = 70;
    
    /** Dictionary word file (empty for randnom text) */
    public $wordsFile = false; //'words/en.php';
    
    /**
     * Path for resource files (fonts, words, etc.)
     *
     * "resources" by default. For security reasons, is better move this
     * directory to another location outise the web server
     *
     */
    public $resourcesPath = ZEED_PATH_DATA;
    
    /** Min word length (for non-dictionary random text generation) */
    public $minWordLength = 6;
    
    /**
     * Max word length (for non-dictionary random text generation)
     * 
     * Used for dictionary words indicating the word-length
     * for font-size modification purposes
     */
    public $maxWordLength = 6;
    
    /** Save captcha text to session while set word */
    public $sessionVar = true;
    
    /** Background color in RGB-array */
    public $backgroundColor = array(255, 255, 255);
    
    /** Foreground colors in RGB-array */
    public $colors = array(array(27, 78, 181));
    
    public $lineColors = array(0x1B4EB5,0xFFFFFF, 0x1B4EB5);
    
    /** Shadow color in RGB-array or null */
    public $shadowColor = false;// array(100, 100, 100);
    

    /**
     * Font configuration
     *
     * - font: TTF file
     * - spacing: relative pixel space between character
     * - minSize: min font size
     * - maxSize: max font size
     */
    public $fonts = array(
            'Antykwa' => array(
                    'spacing' => 22, 
                    'minSize' => 33, 
                    'maxSize' => 36, 
                    'font' => 'AntykwaBold.ttf'),
            'Heineken' => array(
                    'spacing' => 33, 
                    'minSize' => 30, 
                    'maxSize' => 40, 
                    'font' => 'Heineken.ttf') ,
            'DingDong' => array(
                    'spacing' => 22, 
                    'minSize' => 30, 
                    'maxSize' => 36, 
                    'font' => 'Ding-DongDaddyO.ttf'),
            'TimesNewRomanBold' => array(
                    'spacing' => 22, 
                    'minSize' => 34, 
                    'maxSize' => 38, 
                    'font' => 'TimesNewRomanBold.ttf')
            /*'Jura' => array(
                    'spacing' => 20, 
                    'minSize' => 30, 
                    'maxSize' => 40, 
                    'font' => 'Jura.ttf'),
            'Candice' => array(
                    'spacing' => - 2, 
                    'minSize' => 34, 
                    'maxSize' => 37, 
                    'font' => 'Candice.ttf'), 
            'StayPuft' => array(
                    'spacing' => - 2.5, 
                    'minSize' => 34, 
                    'maxSize' => 38, 
                    'font' => 'StayPuft.ttf'),
            'VeraSans' => array(
                    'spacing' => 18, 
                    'minSize' => 20, 
                    'maxSize' => 38, 
                    'font' => 'VeraSansBold.ttf'),
            'Duality' => array(
                    'spacing' => 35, 
                    'minSize' => 36, 
                    'maxSize' => 44, 
                    'font' => 'Duality.ttf')*/);
    
    /** Wave configuracion in X and Y axes */
    public $Yperiod = 6;
    public $Yamplitude = 3;
    public $Xperiod = 5;
    public $Xamplitude = 2;
    
    /** letter rotation clockwise */
    public $maxRotation = 8;
    
    /**
     * Internal image size factor (for better image quality)
     * 1: low, 2: medium, 3: high
     */
    public $scale = 3;
    
    /** 
     * Blur effect for better image quality (but slower image processing).
     * Better image results with scale=3
     */
    public $blur = true;
    
    /** Debug? */
    public $debug = false;
    
    /** Image format: jpeg or png */
    public $imageFormat = 'jpeg';
    
    /** GD image */
    protected $im;
    
    /** Word to display */
    protected $_word;
    
    /** ID of captcha */
    protected $_id;
    
    public function __construct($config = array())
    {
    
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
     * 
     * @param string $word
     */
    public static function setValidWord($id, $word)
    {
        $k = 'Zeed_Captcha_' . $id;
        $_SESSION[$k] = $word;
    }
    
    /**
     * @return string
     */
    public static function generateRandomId()
    {
        return md5(mt_rand(0, 1000) . microtime(true));
    }
    
    public function setWord($word = null)
    {
        if (empty($word)) {
            $word = $this->_generateCaptchaText();
        }
        $this->_word = $word;
        if ($this->sessionVar) {
            self::setValidWord($this->_id, $word);
        }
        
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
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function disableSessionVar()
    {
        $this->sessionVar = false;
        return $this;
    }
    
    public function enableSessionVar()
    {
        $this->sessionVar = true;
        return $this;
    }
    
    public function display()
    {
        $ini = microtime(true);
        
        /** Initialization */
        $this->ImageAllocate();
        
        /** Text insertion */
        $word = ! empty($this->_word) ? $this->_word : $this->setWord()->getWord();
        $fontcfg = $this->fonts[array_rand($this->fonts)];
        $this->WriteText($word, $fontcfg);
        
        /** Transformations */
        
        $this->WaveImage();
        $this->_drawLine();
        /*
        if ($this->blur && function_exists('imagefilter')) {
            imagefilter($this->im, IMG_FILTER_GAUSSIAN_BLUR);
        }*/
        $this->ReduceImage();
        
        if ($this->debug) {
            imagestring($this->im, 1, 1, $this->height - 8, "$word {$fontcfg['font']} " . round((microtime(true) - $ini) * 1000) . "ms", $this->GdFgColor);
        }
        
        /** Output */
        $this->WriteImage();
        $this->Cleanup();
    }
    
    /**
     * Creates the image resources
     */
    protected function ImageAllocate()
    {
        // Cleanup
        if (! empty($this->im)) {
            imagedestroy($this->im);
        }
        
        $this->im = imagecreatetruecolor($this->width * $this->scale, $this->height * $this->scale);
        
        // Background color
        $this->GdBgColor = imagecolorallocate($this->im, $this->backgroundColor[0], $this->backgroundColor[1], $this->backgroundColor[2]);
        imagefilledrectangle($this->im, 0, 0, $this->width * $this->scale, $this->height * $this->scale, $this->GdBgColor);
        
        // Foreground color
        $color = $this->colors[mt_rand(0, sizeof($this->colors) - 1)];
        $this->GdFgColor = imagecolorallocate($this->im, $color[0], $color[1], $color[2]);
        
        // Shadow color
        if (! empty($this->shadowColor) && is_array($this->shadowColor) && sizeof($this->shadowColor) >= 3) {
            $this->GdShadowColor = imagecolorallocate($this->im, $this->shadowColor[0], $this->shadowColor[1], $this->shadowColor[2]);
        }
    }
    
    /**
     * Text generation
     *
     * @return string Text
     */
    protected function _generateCaptchaText()
    {
        $text = $this->_generateDictionaryCaptchaText();
        if (! $text) {
            $text = $this->_generateRandomCaptchaText();
        }
        return $text;
    }
    
    /**
     * Random text generation
     *
     * @return string Text
     */
    protected function _generateRandomCaptchaText($length = null)
    {
        if (empty($length)) {
            $length = rand($this->minWordLength, $this->maxWordLength);
        }
        
        $words = "abcdefghijlmnopqrstvwyz";
        $vocals = "aeiou";
        
        $text = "";
        $vocal = rand(0, 1);
        for ($i = 0; $i < $length; $i ++) {
            if ($vocal) {
                $text .= substr($vocals, mt_rand(0, 4), 1);
            } else {
                $text .= substr($words, mt_rand(0, 22), 1);
            }
            $vocal = ! $vocal;
        }
        return $text;
    }
    
    /**
     * Random dictionary word generation
     *
     * @param boolean $extended Add extended "fake" words
     * @return string Word
     */
    protected function _generateDictionaryCaptchaText($extended = false)
    {
        if (empty($this->wordsFile)) {
            return false;
        }
        
        // Full path of words file
        if (substr($this->wordsFile, 0, 1) == '/') {
            $wordsfile = $this->wordsFile;
        } else {
            $wordsfile = $this->resourcesPath . '/' . $this->wordsFile;
        }
        
        $fp = fopen($wordsfile, "r");
        $length = strlen(fgets($fp));
        if (! $length) {
            return false;
        }
        $line = rand(1, (filesize($wordsfile) / $length) - 2);
        if (fseek($fp, $length * $line) == - 1) {
            return false;
        }
        $text = trim(fgets($fp));
        fclose($fp);
        
        /** Change ramdom volcals */
        if ($extended) {
            $text = preg_split('//', $text, - 1, PREG_SPLIT_NO_EMPTY);
            $vocals = array('a', 'e', 'i', 'o', 'u');
            foreach ($text as $i => $char) {
                if (mt_rand(0, 1) && in_array($char, $vocals)) {
                    $text[$i] = $vocals[mt_rand(0, 4)];
                }
            }
            $text = implode('', $text);
        }
        
        return $text;
    }
    
    /**
     * Text insertion
     */
    protected function WriteText($text, $fontcfg = array())
    {
        if (empty($fontcfg)) {
            // Select the font configuration
            $fontcfg = $this->fonts[array_rand($this->fonts)];
        }
        
        // Full path of font file
        $fontfile = $this->resourcesPath . '/font/' . $fontcfg['font'];
        
        /** Increase font-size for shortest words: 9% for each glyp missing */
        $lettersMissing = $this->maxWordLength - strlen($text);
        $fontSizefactor = 1 + ($lettersMissing * 0.09);
        
        // Text generation (char by char)
        $x = $ox = round(($this->width * 1.3 / 20) * $this->scale);
        $y = round(($this->height * 27 / 40) * $this->scale);
        $length = strlen($text);
        for ($i = 0; $i < $length; $i ++) {
            $degree = rand($this->maxRotation * - 1, $this->maxRotation);
            $fontsize = round( ($this->width * $this->scale) / strlen($text) );
            $fontsize = rand($fontsize, $fontsize * 1.4);
            //exit($fontsize);
            $spacing = 0 - ($fontsize / $fontcfg['spacing']);
            //$fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize']) * $this->scale * $fontSizefactor;
            $letter = substr($text, $i, 1);
            
            if ($this->shadowColor) {
                $coords = imagettftext($this->im, $fontsize, $degree, $x + $this->scale, $y + $this->scale, $this->GdShadowColor, $fontfile, $letter);
            }
            $coords = imagettftext($this->im, $fontsize, $degree, $x, $y, $this->GdFgColor, $fontfile, $letter);
            //$x += ($coords[2] - $x) + ($fontcfg['spacing'] * $this->scale);
            $x += ($coords[2] - $x) + ($spacing * $this->scale);
            //break;
        }
    }
    
    /**
     * Wave filter
     */
    protected function WaveImage()
    {
        // X-axis wave generation
        $xp = $this->scale * $this->Xperiod * rand(1, 3);
        $k = rand(0, 10);
        for ($i = 0; $i < ($this->width * $this->scale); $i ++) {
            imagecopy($this->im, $this->im, $i - 1, sin($k + $i / $xp) * ($this->scale * $this->Xamplitude), $i, 0, 1, $this->height * $this->scale);
        }
        
        // Y-axis wave generation
        
        $k = rand(0, 10);
        $yp = $this->scale * $this->Yperiod * rand(1, 3);
        for ($i = 0; $i < ($this->height * $this->scale); $i ++) {
            imagecopy($this->im, $this->im, sin($k + $i / $yp) * ($this->scale * $this->Yamplitude), $i - 1, 0, $i, $this->width * $this->scale, 1);
        }
    }
    
    /**
     * Draw lines
     */
    protected function _drawLine()
    {
        $width = $this->width * $this->scale;
        $height = $this->height * $this->scale;
        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 00, 00, 00);
        imagefilledrectangle($image, 0, 0, $width, $height, $white);
        
        for ($j = 0; $j < 2; $j++) {
            $lineColor = $this->lineColors[array_rand($this->lineColors)];
            $centerX = rand(-10 * $this->scale, $width);
            $centerY = rand(-10 * $this->scale, $height);
            $eWidth = rand($width*0.9, $width*1.5);
            $eHeight = rand($height*0.9, $height*1.5);
            $weight = rand(2 * $this->scale, 3 * $this->scale);
            for ($i = 0; $i < $weight; $i++) {
                imageellipse($image, $centerX, $centerY, $eWidth, $eHeight, $lineColor);
                $eWidth -= 2;
                $eHeight -= 2;
            }
        }
        
        $Xperiod = 8;
        $Xamplitude = 2;
        $Yperiod = 15;
        $Yamplitude = 2;
        // X-axis wave generation
        $xp = $this->scale * $Xperiod * rand(1, 3);
        $k = rand(0, 100);
        for ($i = 0; $i < ($width); $i ++) {
            imagecopy($image, $image, $i - 1, sin($k + $i / $xp) * ($this->scale * $Xamplitude), $i, 0, 1, $height);
        }
        
        // Y-axis wave generation
        $k = rand(0, 100);
        $yp = $this->scale * $Yperiod * rand(1, 3);
        for ($i = 0; $i < ($height); $i ++) {
            imagecopy($image, $image, sin($k + $i / $yp) * ($this->scale * $Yamplitude), $i - 1, 0, $i, $width, 1);
        }
        
        $x = rand(5, 15)*$this->scale;
        $y = rand(5, 15)*$this->scale;
        $w = $width - rand(5, 15)*$this->scale;
        $h = $height - rand(5, 15)*$this->scale;
        
        for($i = $x; $i < $w; $i++) {
            for ($j = $y; $j < $h; $j++) {
                if (imagecolorat($image, $i, $j) == 0x000000) continue;
                imagecopy($this->im, $image, $i, $j, $i, $j, 1, 1);
            }
        }
        
        imagedestroy($image);
    }
    
    /**
     * Reduce the image to the final size
     */
    protected function ReduceImage()
    {
        // Reduzco el tamaño de la imagen
        $imResampled = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($imResampled, $this->im, 0, 0, 0, 0, $this->width, $this->height, $this->width * $this->scale, $this->height * $this->scale);
        imagedestroy($this->im);
        $this->im = $imResampled;
    }
    
    /**
     * File generation
     */
    protected function WriteImage()
    {
        if ($this->imageFormat == 'png' && function_exists('imagepng')) {
            header("Content-type: image/png");
            imagepng($this->im);
        } else {
            header("Content-type: image/jpeg");
            imagejpeg($this->im, null, 80);
        }
    }
    
    /**
     * Cleanup
     */
    protected function Cleanup()
    {
        imagedestroy($this->im);
    }
    
    public function setResourcesPath($path)
    {
        $this->resourcesPath = $path;
    }
}