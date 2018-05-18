<?php

class Zeed_Console_Shutdown_Unix
{
    /**
     * 程序允许退出标志
     * @var boolean
     */
    private $_allowExit = false;
    
    /**
     * 退出信号标志
     * @var boolean
     */
    private $_forceExit = false;
    
    /**
     * PID
     *
     * @var integer
     */
    private $_pid = null;
    
    /**
     * @var Zeed_Console_Shutdown_Unix
     */
    private static $_instance = null;
    
    /**
     * Constructor
     *
     * @return void
     */
    protected function __construct()
    {
        declare(ticks = 1);
        
        register_tick_function(array($this, 'checkExit'));
        pcntl_signal(SIGTERM, array($this, 'sigHandler')); /* 捕捉 kill 信号 */
        pcntl_signal(SIGINT, array($this, 'sigHandler')); /* 捕捉 CTRL+C 或 CTRL+BREAK 信号*/
    }
    
    /**
     * Enforce singleton; disallow cloning
     *
     * @return void
     */
    private function __clone()
    {
    }
    
    /**
     * @example
     * $shutdown = Zeed_Console_Shutdown_Unix::getinstance();
     * 
     * while(true) {
     * // do something...
     * // done. can exit
     * $shutdown->wait2exit();
     * }
     * 
     * @return Zeed_Console_Shutdown_Unix
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     * 退出信号处理
     * 
     * @return void
     */
    public function sigHandler()
    {
        if ($this->_allowExit) {
            $this->_shutdown();
        } else {
            $this->_forceExit = true;
        }
    }
    
    /**
     * 检查程序退出
     * 
     * @return void
     */
    public function checkExit()
    {
        if ($this->_forceExit && $this->_allowExit) {
            $this->_shutdown();
        }
    }
    
    /**
     * 等待退出
     * 
     * @return void
     */
    public function wait2exit()
    {
        $this->_allowExit = true;
        sleep(1);
        
        $this->_allowExit = false;
    }
    
    /**
     * 程序退出
     * 
     * @return void
     */
    private function _shutdown()
    {
    	$note = sprintf('Exit. (Process ID = %s)', getmypid());
    	
        if (method_exists('Zeed_Util', 'println')) {
        	Zeed_Util::println($note);
        	Zeed_Util::println('%n');
        } else {
        	echo $note;
        }
        
        exit;
    }
}