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
 * @package    Zeed_Benchmark
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Benchmark.php 13049 2012-06-15 17:11:04Z xsharp $
 */

class Zeed_Benchmark
{
    /**
     * Benchmark timestamps
     *
     * @var array
     */
    private static $marks;
    private static $_resetThisCounter = false;
    
    /**
     * 重置开始时间
     *
     * @param string $name
     * @param boolean $resetAll 如果设置为 false，那么只清除上一次开始时间，并且下次结束时间也不做统计
     */
    public static function reset($name, $resetAll = true)
    {
        if (isset(self::$marks[$name])) {
            if ($resetAll) {
                self::$marks[$name] = array();
            } else {
                if (! empty(self::$marks[$name])) {
                    array_shift(self::$marks[$name]);
                    self::$_resetThisCounter = true;
                }
            }
        }
    }
    
    /**
     * Set a benchmark start point.
     *
     * @param   string  benchmark name
     * @return  void
     */
    public static function start($name)
    {
        if (! isset(self::$marks[$name])) {
            self::$marks[$name] = array();
        }
        
        $mark = array(
                'start' => microtime(TRUE), 
                'stop' => FALSE, 
                'memory_start' => self::memory_usage(), 
                'memory_stop' => FALSE);
        
        array_unshift(self::$marks[$name], $mark);
    }
    
    /**
     * Set a benchmark stop point.
     *
     * @param   string  benchmark name
     * @return  void
     */
    public static function stop($name)
    {
        if (self::$_resetThisCounter) {
            self::$_resetThisCounter = false;
            return;
        }
        
        if (isset(self::$marks[$name]) and self::$marks[$name][0]['stop'] === FALSE) {
            self::$marks[$name][0]['stop'] = microtime(TRUE);
            self::$marks[$name][0]['memory_stop'] = self::memory_usage();
        }
    }
    
    /**
     * Get the elapsed time between a start and stop.
     *
     * @param   string   benchmark name, TRUE for all
     * @param   integer  number of decimal places to count to
     * @return  array
     */
    public static function get($name, $decimals = 4)
    {
        if ($name === TRUE) {
            $times = array();
            $names = array_keys(self::$marks);
            
            foreach ($names as $name) {
                // Get each mark recursively
                $times[$name] = self::get($name, $decimals);
            }
            
            // Return the array
            return $times;
        }
        
        if (! isset(self::$marks[$name]))
            return FALSE;
        
        if (empty(self::$marks[$name])) {
            return array('time' => 0, 'memory' => 0, 'count' => 0);
        }
        
        if (self::$marks[$name][0]['stop'] === FALSE) {
            // Stop the benchmark to prevent mis-matched results
            self::stop($name);
        }
        
        // Return a string version of the time between the start and stop points
        // Properly reading a float requires using number_format or sprintf
        $time = $memory = 0;
        $count = count(self::$marks[$name]);
        for ($i = 0; $i < $count; $i ++) {
            $time += self::$marks[$name][$i]['stop'] - self::$marks[$name][$i]['start'];
            $memory += self::$marks[$name][$i]['memory_stop'] - self::$marks[$name][$i]['memory_start'];
        }
        
        return array(
                'time' => number_format($time, $decimals), 
                'memory' => number_format(($memory / 1024 / 1024), 4), 
                'count' => $count);
    }
    
    /**
     * Dump marks to log file.(Testing)
     *
     * @return array
     */
    public static function dump()
    {
        if (! count(self::$marks)) {
            return false;
        }
        $marks = Zeed_Benchmark::get(true);
        
        $logFile = ZEED_PATH_DATA . 'log/Benchmark-' . date('Y-m-d') . '.log';
        $clientIP = Zeed_Util::clientIP();
        $clientName = 'xsharp' . rand(1, 8);
        $time = time();
        if (false != $handle = fopen($logFile, 'a')) {
            $logContent = '';
            foreach ($marks as $name => $mark) {
                $time += rand(1, 8);
                //$logContent .= $clientIP . ' ' . $clientName . ' [' . date('m/M/Y:H:i:s +0800') . '] "GET ' . $name . ' HTTP/1.1" ' . $mark['time'] . ' ' . $mark['memory'] . ' ' . $mark['count'] . "\n";
                $logContent .= $clientIP . ' - ' . $clientName . ' [' . date('m/M/Y:H:i:s +0800', $time) . '] "GET /' . $name . ' HTTP/1.1" 200 ' . (int) ($mark['time'] * 1000000) . "\r\n";
            }
            fwrite($handle, $logContent);
            fclose($handle);
        }
    }
    
    /**
     * Returns the current memory usage. This is only possible if the
     * memory_get_usage function is supported in PHP.
     *
     * @return  integer
     */
    private static function memory_usage()
    {
        static $func = NULL;
        
        if ($func === NULL) {
            // Test if memory usage can be seen
            $func = function_exists('memory_get_usage');
        }
        
        return $func ? memory_get_usage() : 0;
    }
    
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
                $prefix = '<FIELDSET STYLE="FONT-SIZE:12PX;FONT-FAMILY:COURIER NEW;"><LEGEND STYLE="PADDING:5PX;">' . (is_string($memo)? $memo : print_r($memo, true)) . '</LEGEND>';
                $postfix = '</FIELDSET>';
            } else {
                $prefix = $postfix = "";
            }
            echo $prefix . '<PRE STYLE="FONT-SIZE:12PX;PADDING:5PX;BORDER-LEFT:5PX SOLID #0066CC;FONT-FAMILY:COURIER NEW;COLOR:BLACK;TEXT-ALIGN:LEFT;BACKGROUND-COLOR:' . $color_bg . '">' . "\n";
            print_r($var);
            echo "\n</PRE>\n" . $postfix;
        } else {
            if (! is_null($memo)) {
                if (is_string($memo)) {
                    echo $memo;
                } else {
                    print_r($memo);
                }
                echo " - - - - -\n";
            }
            print_r($var);
            echo "\n";
        }
    }
    
    public static function println($str, $flush = true)
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            echo $str . '<br />';
        } else {
            echo $str . "\n";
        }
        
        if ($flush) {
            Zeed_Util::flush();
        }
    }
    
    /**
     *
     */
    public static function sql()
    {
        if (class_exists('Fit_Db', false)) {
            $dbInstances = & Fit_Db::$instances;
        } elseif (class_exists('Zeed_Db', false)) {
            $dbInstances = & Zeed_Db::$instances;
        } else {
            return null;
        }
        
        if (empty($dbInstances)) {
            return null;
        }
        
        $totalTime = 0;
        $queryCount = 0;
        $longestTime = 0;
        $longestQuery = null;
        $sqlAll = array();
        $i = 0;
        foreach ($dbInstances as $name => $dbAdapter) {
            $profiler = $dbAdapter->getProfiler();
            $_cQueryCount = $profiler->getTotalNumQueries();
            if ($_cQueryCount < 1) {
                continue;
            } else {
                $totalTime += $profiler->getTotalElapsedSecs();
                $queryCount += $profiler->getTotalNumQueries();
            }
            
            foreach ($profiler->getQueryProfiles() as $_qp) {
                $_es = $_qp->getElapsedSecs();
                $_q = $_qp->getQuery();
                $sqlAll["$_es"] = array('sql' => $_q, 'time' => $_es, 'id' => $i, 'con' => $name);
                
                if ($_qp->getQueryType() == Zend_Db_Profiler::SELECT) {
                    $sqlAll["$_es"]['explain'] = $dbAdapter->query('EXPLAIN ' . $_q)->fetchAll();
                }
                
                if ($_es > $longestTime) {
                    $longestTime = $_es;
                    $longestQuery = $_q;
                }
                $i ++;
            }
        }
        
        if ($queryCount < 1) {
            return null;
        }
        
        echo '<a href="#showsql" name="showsql" style="text-decoration:none;" onclick="(document.getElementById(\'queryProfiler\').style.display == \'none\') ? (document.getElementById(\'queryProfiler\').style.display = \'block\') : (document.getElementById(\'queryProfiler\').style.display = \'none\')">+</a>';
        echo '<div id="queryProfiler" style="display:none; text-align:left; padding-left:2px; line-height:130%; color:#003366; font-size:11px; font-family:Courier New; margin:0; background-color:#FAF8C5; border:1px dotted #666;">';
        if (count($sqlAll) > 0) {
            ksort($sqlAll);
            reset($sqlAll);
            $perIn = (int) (255 / $queryCount);
            $fColor = 0;
            foreach ($sqlAll as $val) {
                $fColor += $perIn;
                $bColor = 255 - $fColor;
                $val['fColor'] = $fColor;
                $val['bColor'] = $bColor;
                
                $sqlAll2[$val['id']] = $val;
            }
            ksort($sqlAll2);
            reset($sqlAll2);
            unset($sqlAll);
            
            foreach ($sqlAll2 as $val) {
                $val['time'] = $val['time'] * 1000;
                $val['time'] = (string) sprintf('%f', $val['time']) . 'ms';
                echo sprintf('%02d', $val['id']) . '.<span style="border-left: 10px solid rgb(255,' . $val['bColor'] . ',' . $val['bColor'] . '); ;border-bottom: 1px solid rgb(255,' . $val['bColor'] . ',' . $val['bColor'] . '); line-height:21px;">' . $val['sql'] . '</span> (' . $val['con'] . ') ... ' . $val['time'] . '<br />';
                
                if (! isset($val['explain'])) {
                    continue;
                }
                $explain = '<span style="padding-left: 30px ;">';
                foreach ($val['explain'] as $_val) {
                    $explain .= ' <strong>Table:</strong> ' . $_val['table'];
                    if ($_val['key'] != '') {
                        $explain .= ' <strong>Key:</strong> ' . $_val['key'];
                    }
                    if ($_val['Extra'] != '') {
                        if (preg_match("/temporary|filesort/i", $_val['Extra'])) {
                            $eColor = 'red';
                        } else {
                            $eColor = 'green';
                        }
                        $explain .= ' <strong>Extra:</strong> <span = style="background-color:' . $eColor . '; color: white;">' . $_val['Extra'] . '</span>';
                    }
                }
                echo $explain . '</span><br>';
            
            }
        }
        
        echo '<div style="padding-left:1px; margin-top:5px;">';
        echo 'Executed ' . $queryCount . ' queries in ' . $totalTime . ' seconds' . "<br />\n";
        echo 'Average query length: ' . $totalTime / $queryCount . ' seconds' . "<br />\n";
        echo 'Queries per second: ' . $queryCount / $totalTime . "<br />\n";
        echo 'Longest query length: ' . $longestTime . "<br />\n";
        echo "Longest query: \n" . $longestQuery . "<br />\n";
        echo '</div>';
        echo '</div>';
    }
}

// End ^ LF ^ UTF-8
