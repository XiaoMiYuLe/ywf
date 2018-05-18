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
 * @package    Zeed_Task
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: View.php 6709 2010-09-08 13:47:02Z xsharp $
 */

abstract class Zeed_Task
{
    protected $phpBin = '';
    protected $shellFront = 'shellstrap.php';
    
    protected $_task;
    
    /**
     * 未处理的队列
     */
    const QUEUE_STATUS_UNSETTLED = 0;
    
    /**
     * 正在处理的队列
     */
    const QUEUE_STATUS_RUNNING = 1;
    
    /**
     * 成功
     */
    const QUEUE_STATUS_SUCCESS = 2;
    
    /**
     * 失败的队列
     */
    const QUEUE_STATUS_FAIL = 9;
    
    /**
     * 最大允许失败的次数
     */
    const QUEUE_FAILCOUNTMAX = 5;
    
    /**
     * 最大允许执行时间(单位:秒)
     */
    const QUEUE_RUNTIMEMAX = 900;
    
    /**
     * 执行任务流程:
     * 
     * 
     */
    public function run()
    {
        $task = $this->_task;
        $cronString = (! empty($task['cron'])) ? $task['cron'] : '';
        
        if (strlen($cronString) < 9) {
            // 单次任务, 执行后删除
            $task['status'] = self::QUEUE_STATUS_RUNNING;
            $this->updateTask($task);
            $task['output'] = $this->_run($task);
            $task['status'] = self::QUEUE_STATUS_SUCCESS;
            $this->deleteTask($task);
            $this->log($task);
        } elseif (0 < ($lastRan = Zeed_Task_Cron::parseCron($cronString))) {
            // 循环任务, 执行后设置成功状态
            if ($lastRan > strtotime($task['ranat'])) {
                $task['status'] = self::QUEUE_STATUS_RUNNING;
                $this->updateTask($task);
                $task['output'] = $this->_run($task);
                $task['status'] = self::QUEUE_STATUS_SUCCESS;
                $this->updateTask($task);
                $this->log($task);
            } else {
                // 时辰未到, 什么也不做
            }
        } else {
            // CRONTAB格式错误
            echo "Crontab Format Error, Skipped.";
        }
        
        flush();
    }
    
    private function _run($task)
    {
        $output = '';
        if (! isset($_SERVER['HTTP_HOST'])) {
            echo "\nCronId: " . $task['cronId'] . ' Command: ' . $task['cmd'] . ' ... ';
            $cmd = $this->phpBin . ' -f ' . $this->shellFront . ' ' . $task['cmd'];
            
            exec($cmd, $output);
            echo "ok";
        }
        
        if (is_array($output)) {
            $output = implode("\n", $output);
        }
        
        return $output;
    }
    
    protected function log($taskUpdated)
    {
    
    }
    
    public function instance()
    {
    
    }
    
    /**
     * @param array $task
     * @return Zeed_Task
     */
    public function setTask($task)
    {
        $this->_task = $task;
        
        return $this;
    }
    
    /**
     * 设置PHP磁盘路径
     * 
     * @param string $phpPath
     * @return Zeed_Task
     */
    public function setPhpBin($phpPath)
    {
        $this->phpBin = $phpPath;
        
        return $this;
    }
    
    /**
     * setup shell bootstrap.
     * 
     * @param string $shellstrap
     * @return Zeed_Task
     */
    public function setShellFront($shellstrap)
    {
        $this->shellFront = $shellstrap;
        
        return $this;
    }
    
    /**
     * 设置任务执行时的pid
     * 
     * @param array|string $pids
     * @return Zeed_Task
     */
    public function setPids($pids)
    {
    }
}

// End ^ LF ^ UTF-8
