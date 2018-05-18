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
 * @since      2010-12-14
 * @version    SVN: $Id$
 */

class Com_Event
{
    // Event callbacks
    private static $events = array();
    
    // Cache of events that have been run
    private static $has_run = array();
    
    // Data that can be processed during events
    public static $data;
    
    /**
     * 插件运行入口名称
     *
     * @var string
     */
    private static $run_method_name = 'run';
    
    /**
     * Add a callback to an event queue.
     *
     * @param   string   event name
     * @param   array    http://php.net/callback
     * @return  boolean
     */
    public static function add($name, $callback)
    {
        if (! isset(self::$events[$name])) {
            // Create an empty event if it is not yet defined
            self::$events[$name] = array();
        } elseif (in_array($callback, self::$events[$name], TRUE)) {
            // The event already exists
            return FALSE;
        }
        
        // Add the event
        self::$events[$name][] = $callback;
        
        return TRUE;
    }
    
    /**
     * Add a callback to an event queue, before a given event.
     *
     * @param   string   event name
     * @param   array    existing event callback
     * @param   array    event callback
     * @return  boolean
     */
    public static function addBefore($name, $existing, $callback)
    {
        if (empty(self::$events[$name]) or ($key = array_search($existing, self::$events[$name])) === FALSE) {
            // Just add the event if there are no events
            return self::add($name, $callback);
        } else {
            // Insert the event immediately before the existing event
            return self::insertEvent($name, $key, $callback);
        }
    }
    
    /**
     * Add a callback to an event queue, after a given event.
     *
     * @param   string   event name
     * @param   array    existing event callback
     * @param   array    event callback
     * @return  boolean
     */
    public static function addAfter($name, $existing, $callback)
    {
        if (empty(self::$events[$name]) or ($key = array_search($existing, self::$events[$name])) === FALSE) {
            // Just add the event if there are no events
            return self::add($name, $callback);
        } else {
            // Insert the event immediately after the existing event
            return self::insertEvent($name, $key + 1, $callback);
        }
    }
    
    /**
     * Inserts a new event at a specfic key location.
     *
     * @param   string   event name
     * @param   integer  key to insert new event at
     * @param   array    event callback
     * @return  void
     */
    private static function insertEvent($name, $key, $callback)
    {
        if (in_array($callback, self::$events[$name], TRUE))
            return FALSE;
            
        // Add the new event at the given key location
        self::$events[$name] = array_merge(// Events before the key
array_slice(self::$events[$name], 0, $key), // New event callback
array(
                $callback), // Events after the key
array_slice(self::$events[$name], $key));
        
        return TRUE;
    }
    
    /**
     * Replaces an event with another event.
     *
     * @param   string   event name
     * @param   array    event to replace
     * @param   array    new callback
     * @return  boolean
     */
    public static function replace($name, $existing, $callback)
    {
        if (empty(self::$events[$name]) or ($key = array_search($existing, self::$events[$name], TRUE)) === FALSE)
            return FALSE;
        
        if (! in_array($callback, self::$events[$name], TRUE)) {
            // Replace the exisiting event with the new event
            self::$events[$name][$key] = $callback;
        } else {
            // Remove the existing event from the queue
            unset(self::$events[$name][$key]);
            
            // Reset the array so the keys are ordered properly
            self::$events[$name] = array_values(self::$events[$name]);
        }
        
        return TRUE;
    }
    
    /**
     * Get all callbacks for an event.
     *
     * @param   string  event name
     * @return  array
     */
    public static function get($name)
    {
        return empty(self::$events[$name]) ? array() : self::$events[$name];
    }
    
    /**
     * Clear some or all callbacks from an event.
     *
     * @param   string  event name
     * @param   array   specific callback to remove, FALSE for all callbacks
     * @return  void
     */
    public static function clear($name, $callback = FALSE)
    {
        if ($callback === FALSE) {
            self::$events[$name] = array();
        } elseif (isset(self::$events[$name])) {
            // Loop through each of the event callbacks and compare it to the
            // callback requested for removal. The callback is removed if it
            // matches.
            foreach (self::$events[$name] as $i => $event_callback) {
                if ($callback === $event_callback) {
                    unset(self::$events[$name][$i]);
                }
            }
        }
    }
    
    /**
     * Execute all of the callbacks attached to an event.
     *
     * @param   string   event name
     * @param   array    data can be processed as Event::$data by the callbacks
     * @return  void
     */
    public static function run($name, & $data = NULL)
    {
        if (! empty(self::$events[$name])) {
            // So callbacks can access Event::$data
            self::$data = & $data;
            $callbacks = self::get($name);
            
            foreach ($callbacks as $callback) {
                @Zeed_Loader::loadClass($callback);
                
                if (class_exists($callback)) {
                    $o = new $callback();
                    if ($o instanceof Com_Application_Event_Abstract) {
                        try {
                            call_user_func(array(
                                    $o,
                                    self::$run_method_name));
                        } catch (Exception $e) {
                            return;
                        }
                    } else {
                        throw new Zeed_Exception('Hook must instance of Com_Application_Event_Abstract.');
                    }
                }
            }
            
            // Do this to prevent data from getting 'stuck'
            $clear_data = '';
            self::$data = & $clear_data;
        }
        
        // The event has been run!
        self::$has_run[$name] = $name;
    }
    
    /**
     * Check if a given event has been run.
     *
     * @param   string   event name
     * @return  boolean
     */
    public static function hasRun($name)
    {
        return isset(self::$has_run[$name]);
    }

} // End Event

// End ^ Native EOL ^ encoding
