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
 * Tracker 类型日志追踪格式
 *
 * @author Nroe
 */
class Zeed_Log_Formatter_Tracker extends Zeed_Log_Formatter_Serialize
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

            if ($name == 'message') {
                if (! is_array($value) || ! isset($value['tag']) || ! isset($value['data'])) {
                    throw new Zeed_Exception('Tracker formatter message define invalid');
                }
            }

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

// End ^ Native EOwL ^ encoding
