<?php
/**
 * Playcool Project
 *
 * LICENSE
 *
 * http://www.playcool.com/license/ice
 *
 * @category   ICE
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Jun 3, 2009
 * @version    SVN: $Id: Action.php 169 2014-09-30 17:56:33 Cyrano $
 */

class Zeed_Controller_Action implements Zeed_Controller_Action_Interface
{
    
    const RS_SUCCESS = 'success';
    const RS_INPUT = 'input';
    
    protected $_request;
    
    protected $_params = array();
    
    /**
     * 是否跳过开启 XSS 过滤，如果设置为 true，那么该控制器下所有方法都将不启动 XSS 过滤
     * 如果设置为 array ，所有在 array 中的方法都将不启动 XSS 过滤
     *
     * @var boolean|array
     */
    protected $_skip_xss_clean = false;
    
    /**
     * 是否跳过开启 Session，如果设置为 true，那么该控制器下所有方法都将不启动 Session
     * 如果设置为 array ，所有在 array 中的方法都将不启动 Session
     *
     * @var boolean|array
     */
    protected $_skip_session_create = false;
    
    /**
     * @var Array 允许使用的视图类型
     */
    protected $_allowedResultType = array(
            'default' => 'Zeed_View_Default', 
            'php' => 'Zeed_View_Php', 
            'redirector' => 'Zeed_View_Redirector', 
            'json' => 'Zeed_View_Json', 
            'xml' => 'Zeed_View_Xml', 
            'jsonp' => 'Zeed_View_Jsonp');
    
    protected $_resultType = array();
    
    /**
     * @var Zeed_Request_Http
     */
    public $input;
    
    public function __construct(Zeed_Controller_Request $request)
    {
//         $_GET = $this->_addcslashes($_GET);
//         $_POST = $this->_addcslashes($_POST);
//         $_REQUEST = $this->_addcslashes($_REQUEST);
        
        $this->input = $this->_request = $request;
        
        $this->_init();
        
        if (defined('ZEED_IN_CONSOLE') && ZEED_IN_CONSOLE) {
            return;
        }
        
        $actionName = $this->input->getActionName();
        
        /**
         * HTTP Process...
         */
        if (($this->input->isPOST() || $this->input->isGET()) && (! $this->_skip_xss_clean || (is_array($this->_skip_xss_clean) && ! in_array($actionName, $this->_skip_xss_clean)))) {
        	if ($this->input->isPOST()) {
        		$_POST = $this->input->xssClean($_POST);
        	}
        	if ($this->input->isGET()) {
        		$_GET = $this->input->xssClean($_GET);
        	}
            define('XSS_CLEAN', true);
        }
        
        /**
         * Session Start
         */
        if (! $this->_skip_session_create || (is_array($this->_skip_session_create) && ! in_array($actionName, $this->_skip_session_create))) {
            Zeed_Session::instance();
        }
    }
    
    /**
     * 自定义初始化一些设置
     *
     * @return void
     * @Overwrite
     */
    protected function _init()
    {
    }
    
    /**
     * 设置Controller中方法的视图
     * <code>
     * $this->addResult(self::RS_SUCCESS, 'php', 'login_success');
     * $this->addResult(self::RS_INPUT, 'php', 'login_form');
     * </code>
     *
     * @param String $result
     * @param String $resultType
     * @param String $resource
     * @return Zeed_Controller_Action
     */
    public function addResult($result, $resultType = null, $resource = null)
    {
        if (is_null($resultType)) {
            $resultType = 'default';
        } else {
            $resultType = strtolower($resultType);
        }
        
        if (! in_array($resultType, array_keys($this->_allowedResultType))) {
            Zeed_Loader::loadClass('Zeed_Exception');
            throw new Zeed_Exception('视图类型不被允许. 允许的视图类型:<code>' . implode(',', $this->_allowedResultType) . '</code>');
        }
        
        $this->_resultType[$result] = array('type' => $resultType, 'resource' => $resource);
        
        return $this;
    }
    
    /**
     * 设置Controller内部交换参数
     *
     * @param string|array $key
     * @param mixed $val
     * @return Zeed_Controller_Action
     */
    public function setParam($key, $val = null)
    {
        if (is_string($key)) {
            $this->_params[$key] = $val;
        } else if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->_params[$k] = $v;
            }
        } else {
            throw new Zeed_Exception('setParam()参数错误');
        }
        
        return $this;
    }
    
    /**
     *
     * @param string $key
     * @return mixed
     */
    public function getParam($k = null)
    {
        if (is_string($k) || is_numeric($k)) {
            if (strstr($k, '.')) {
                $h = explode('.', $k);
                $return = $this->_params;
                foreach ($h as $_h) {
                    $return = isset($return[$_h]) ? $return[$_h] : null;
                }
                return $return;
            } else {
                return @$this->_params[$k];
            }
        }
        
        return $this->_params;
    }
    
    /**
     * 设置用于渲染视图的数据, 比如:JSON/XML等
     *
     * @var array
     */
    protected $_data;
    
    /**
     * @param string|array|object $k
     * @param mixed $v
     * @return Zeed_Controller_Action
     */
    public function setData($k, $v = null)
    {
        // 首次使用setData()
        if (is_null($this->_data)) {
            if (is_array($k)) {
                $this->_data = $k;
            } elseif (is_object($k)) {
                throw new Zeed_Exception('setData()不支持直接赋值对象, 可以使用 setData($KEY, $OBJECT)');
            } else {
                $this->_data = array();
                $this->_data[(string) $k] = $v;
            }
            
            return $this;
        }
        
        // 再次使用setData()
        if (is_array($k)) {
            $this->_data = array_merge($this->_data, $k);
        } elseif (is_object($k)) {
            throw new Zeed_Exception('setData()不支持直接赋值对象, 可以使用 setData($KEY, $OBJECT)');
        } else {
            $this->_data[(string) $k] = $v;
        }
        
        return $this;
    }
    
    /**
     * 获取设置的(结果)值，支持根据多级KEY获取，点号（.）分割
     *
     * @param string $k
     * @return array mixed
     */
    public function getData($k = null)
    {
        if (is_string($k) || is_numeric($k)) {
            if (strstr($k, '.')) {
                $h = explode('.', $k);
                $return = $this->_data;
                foreach ($h as $_h) {
                    $return = isset($return[$_h]) ? $return[$_h] : null;
                }
                return $return;
            } else {
                return @$this->_data[$k];
            }
        }
        
        return $this->_data;
    }
    
    /**
     * @return Zeed_Controller_Action
     */
    public function clearData()
    {
        $this->_data = null;
        return $this;
    }
    
    /**
     * Get config of views.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->_resultType;
    }
    
    /**
     * Get allowed result type by key-name.
     *
     * @param string $key
     */
    public function getResultType($name)
    {
        return $this->_allowedResultType[$name];
    }
    
    /**
     * Default method, Overwrite it plz.
     */
    public function index()
    {
        echo '<h2>Default Method. Overwrite me plz!</h2> (<code>' . __METHOD__ . '</code>)';
    }
    
    public function __get($name)
    {
        if (key_exists($name, get_object_vars($this))) {
            return $this->$name;
        }
        return null;
    }
    
    /**
     * 递归方式对传入数据进行过滤处理
     * 
     * @param string|array $data
     * @return string|array
     */
    private function _addcslashes($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->_addcslashes($value);
            }
            return $data;
        } else {
            return addcslashes($data, "\000\n\r\\'\"\032");
        }
    }
}

// End ^ LF ^ UTF-8
