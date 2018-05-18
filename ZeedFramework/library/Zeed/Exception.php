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
 * @category Zeed
 * @package Zeed_Exception
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-6-30
 * @version SVN: $Id: Exception.php 13065 2012-06-20 05:40:40Z xsharp $
 */
class Zeed_Exception extends Exception
{
    private $_appMessage;
    public function appMessage($message)
    {
        $this->_appMessage[] = $message;
    }
    
    /**
     *
     * @return String
     */
    public function toString()
    {
        if (is_array($this->_appMessage)) {
            $str = implode("\n", $this->_appMessage) . "\n";
        } else {
            $str = $this->_appMessage;
        }
        $str .= parent::__toString();
        
        $pathRemove = array(realpath(ZEED_ROOT), realpath(ZEED_PATH));
        $pathReplace = array('ZEED_ROOT', 'ZEED_PATH');
        $str = str_replace($pathRemove, $pathReplace, $str);
        
        return $str;
    }
    public function display($message = null)
    {
        if (is_null($message)) {
            $message = $this->getMessage();
        }
        
        echo '<div style="background-color: #EAEAEA; font-family: Courier New; font-size: 10pt; padding: 4px">';
        echo nl2br(str_replace(PP_ROOT, 'INEWS_ROOT', $this->getFile()) . ' (' . $this->getLine() . '): ' . $message);
        echo '</div>';
    }
}

// End ^ LF ^ UTF-8
