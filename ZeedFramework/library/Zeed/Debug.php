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
 * @package    Zeed_Debug
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Debug.php 6710 2010-09-08 13:51:53Z xsharp $
 */

class Zeed_Debug
{
    
    /**
     * Colorful print_r()
     *
     * @param Array|String|Mixed $var
     * @param String $memo
     */
    public static function print_r($var, $memo = null)
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $color_bg = "RGB(" . rand(100, 255) . "," . rand(100, 255) . "," . rand(100, 255) . ")";
            if (! is_null($memo)) {
                $prefix = '<FIELDSET style="font-size:12px;font-family:Courier New;"><LEGEND style="padding:5px;">' . $memo . '</LEGEND>';
                $postfix = '</FIELDSET>';
            } else {
                $prefix = $postfix = "";
            }
            echo $prefix . '<pre style="font-size:12px;padding:5px;border-left:5px solid #0066cc;font-family:Courier New;color:black;text-align:left;background-color:' . $color_bg . '">' . "\n";
            print_r($var);
            echo "\n</pre>\n" . $postfix;
        } else {
            if (! is_null($memo)) {
                echo $memo . " - - - - -\n";
            }
            print_r($var);
            echo "\n";
        }
    }
    
    /**
     * @var string
     */
    protected static $_sapi = null;
    
    /**
     * Get the current value of the debug output environment.
     * This defaults to the value of PHP_SAPI.
     *
     * @return string;
     */
    public static function getSapi()
    {
        if (self::$_sapi === null) {
            self::$_sapi = PHP_SAPI;
        }
        return self::$_sapi;
    }
    
    /**
     * Set the debug ouput environment.
     * Setting a value of null causes Zend_Debug to use PHP_SAPI.
     *
     * @param string $sapi
     * @return void;
     */
    public static function setSapi($sapi)
    {
        self::$_sapi = $sapi;
    }
    
    /**
     * Debug helper function.  This is a wrapper for var_dump() that adds
     * the <pre /> tags, cleans up newlines and indents, and runs
     * htmlentities() before output.
     *
     * @param  mixed  $var   The variable to dump.
     * @param  string $label OPTIONAL Label to prepend to output.
     * @param  bool   $echo  OPTIONAL Echo output if true.
     * @return string
     */
    public static function dump($var, $label = null, $echo = true)
    {
        // format the label
        $label = ($label === null) ? '' : rtrim($label) . ' ';
        
        // var_dump the variable into a buffer and keep the output
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        
        // neaten the newlines and indents
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        if (self::getSapi() == 'cli') {
            $output = PHP_EOL . $label . PHP_EOL . $output . PHP_EOL;
        } else {
            if (! extension_loaded('xdebug')) {
                $output = htmlspecialchars($output, ENT_QUOTES);
            }
            
            $output = '<pre>' . $label . $output . '</pre>';
        }
        
        if ($echo) {
            echo ($output);
        }
        return $output;
    }
}

// End ^ LF ^ encoding
