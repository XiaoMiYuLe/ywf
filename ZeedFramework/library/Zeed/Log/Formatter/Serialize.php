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
 * @since      2010-8-4
 * @version    SVN: $Id$
 */


/**
 * @category   Zend
 * @package    Zend_Log
 * @subpackage Formatter
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Simple.php 20096 2010-01-06 02:05:09Z bkarwin $
 */
class Zeed_Log_Formatter_Serialize extends Zend_Log_Formatter_Simple
{
    /**
     * Formats data into a single line to be written by the writer.
     *
     * @param  array    $event    event data
     * @return string             formatted line to write to the log
     */
    public function format($event)
    {
        $output = $this->_format;
        foreach ($event as $name => $value) {

            if ((is_object($value) && !method_exists($value,'__toString'))) {
                $value = gettype($value);
            }
            else if (is_array($value)) {
                $value = serialize($value);
            }

            $output = str_replace("%$name%", $value, $output);
        }

        return $output;
    }
}


// End ^ Native EOL ^ encoding
