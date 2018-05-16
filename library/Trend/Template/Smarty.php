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
 * @since      2011-10-25
 * @version    SVN: $Id$
 */

class Trend_Template_Smarty extends Smarty_Resource_Custom
{
    private static $_cacheTemplateIndexByName;
    
    /**
     * fetch template and its modification time from data source
     *
     * @param string  $name    template name
     * @param string  &$source template source
     * @param integer &$mtime  template modification timestamp (epoch)
     */
    protected function fetch($name, &$source, &$mtime)
    {
        if (isset(self::$_cacheTemplateIndexByName[$name])) {
            return self::$_cacheTemplateIndexByName[$name];
        }
        
        $rows = Trend_Model_Template::instance()->fetchByPK($name);
        $row = array();
        if (count($rows)) {
            $source = $row['body'] = $rows[0]['body'];
            $timestamp = $row['mtime'] = strtotime($rows[0]['mtime']);
            self::$_cacheTemplateIndexByName[$name] = $row;
        } else {
            $source = $row['body'] = 'Specified template(ID: ' . $name . ') not exist.';
            $timestamp = $row['mtime'] = time();
            self::$_cacheTemplateIndexByName[$name] = $row;
        }
    }
    
    /**
     * Fetch template's modification timestamp from data source
     *
     * {@internal implementing this method is optional.
     * Only implement it if modification times can be accessed faster than loading the complete template source.}}
     *
     * @param string $name template name
     * @return integer|boolean timestamp (epoch) the template was modified, or false if not found
     */
    protected function fetchTimestamp($name)
    {
        if (isset(self::$_cacheTemplateIndexByName[$name])) {
            return self::$_cacheTemplateIndexByName[$name]['mtime'];
        }
        
        return 1;
    }
    
    /**
     * populate Source Object with meta data from Resource
     *
     * @param Smarty_Template_Source   $source    source object
     * @param Smarty_Internal_Template $_template template object
     */
    public function populate(Smarty_Template_Source $source, Smarty_Internal_Template $_template = null)
    {
        $source->filepath = strtolower($source->type . ':' . $source->name);
        $source->uid = sha1($source->type . ':' . $source->name);
        
        $mtime = $this->fetchTimestamp($source->name);
        if ($mtime !== null) {
            $source->timestamp = $mtime;
        } else {
            $this->fetch($source->name, $content, $timestamp);
            $source->timestamp = isset($timestamp) || $timestamp;
            if (isset($content)) {
                $source->content = $content;
            }
        }
        $source->exists = ! ! $source->timestamp;
    }
    
    /**
     * Load template's source into current template object
     *
     * @param Smarty_Template_Source $source source object
     * @return string template source
     * @throws SmartyException if source cannot be loaded
     */
    public function getContent(Smarty_Template_Source $source)
    {
        $this->fetch($source->name, $content, $timestamp);
        if (isset($content)) {
            return $content;
        }
        
        throw new SmartyException("Unable to read template {$source->type} '{$source->name}'");
    }
    
    /**
     * Determine basename for compiled filename
     *
     * @param Smarty_Template_Source $source source object
     * @return string resource's basename
     */
    protected function getBasename(Smarty_Template_Source $source)
    {
        return basename($source->name);
    }
}

// End ^ Native EOL ^ UTF-8