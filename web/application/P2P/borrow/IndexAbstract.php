<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */
class IndexAbstract extends Zeed_Controller_Action
{
    protected $_data = array('status' => 0, 'error' => null, 'data' => null);
    protected $_resultSmartyType = array();
    
    public function setData($k, $v = null)
    {
        if (is_array($k)) {
            $this->_data = $k;
            return;
        }
        if (strstr($k, '.')) {
            $h = explode('.', $k);
            $this->_data[$h[0]][$h[1]] = $v;
        } else {
            $this->_data[$k] = $v;
        }
        
        return $this;
    }
    
    /**
     * 设置错误: $this->_data['error']...
     *
     * @param int $code 错误码
     * @param string $msg 错误消息
     * @return AdminAbstract
     */
    public function setError($code, $msg = null)
    {
        if (is_array($code)) {
            $this->_data['error'] = $code;
        } elseif (is_string($code)) {
            if (! is_null($msg)) {
                $this->_data['error'][$code] = $msg;
            } else {
                $this->_data['error'][] = $code;
            }
        }
        
        return $this;
    }
    
    /**
     * 设置返回数据状态: $this->_data['status']...
     *
     * @param mixed $status
     * @return AdminAbstract
     */
    public function setStatus($status)
    {
        $this->_data['status'] = $status;
        return $this;
    }
    
    /**
     * Get error message.
     * 
     * @return array
     */
    public function getError()
    {
        return @$this->_data['error'];
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see Zeed_Controller_Action::addResult()
     */
    public function addResult($result, $resultType = null, $resource = null)
    {
        $this->_resultSmartyType[$result][$resultType] = array('type' => $resultType, 'resource' => $resource);
        
        return parent::addResult($result, $resultType, $resource);
    }
    
    /**
     * 当配置了多种视图时, 自动根据是否是AJAX或显式的视图类型参数来决定返回何种视图.
     *
     * @param string $result
     */
    protected function smartResult($result)
    {
        $isJsonView = $this->input->isAJAX();
        $rstype = $this->input->query('rstype');
        if ($isJsonView || $rstype == 'json') {
            if ($rstype != '' && $rstype != 'json') {
                return $result;
            }
            if (! is_null($this->_data['data']) && isset($this->_resultSmartyType[$result]['json'])) {
                $this->_resultType[$result] = $this->_resultSmartyType[$result]['json'];
            }
        }
        
        return $result;
    }
    
    /**
     * 当方法需要根据参数返回多种视图时使用
     *
     * @param string $result
     */
    protected function multipleResult($result)
    {
        // JSON View
        $rstype = $this->input->query('rstype');
        if ($rstype == '') {
            return $result;
        }
        
        if (isset($this->_resultSmartyType[$result][$rstype])) {
            $this->_resultType[$result] = $this->_resultSmartyType[$result][$rstype];
            if ($rstype == 'json') {
                if (null != $this->input->query('skin')) { 
                    // 经由PHP自由拼接JSON格式数据.
                    $this->_resultType[$result]['resource'] = 'view.json';
                } elseif (is_null($this->_data['data'])) { 
                    // JSON Error, No data assgined.
                    $this->_data['status'] = 1;
                    $this->_data['error'] = 'no data assigned';
                }
            }
            
            return $result;
        }
        
        return $result;
    }
    
    /**
     *
     * @return integer
     */
    public function validatePageNum()
    {
        $page = (int) $this->input->get('page');
        return $page > 0 ? $page : 1;
    }
    
    /**
     * 处理 categoryid 参数(alias: cid)
     *
     * @return array
     */
    public function validateCategoryid()
    {
        $mapping = array('cid', 'categoryid', 'cids', 'categoryids');
        $return = null;
        foreach ($mapping as $key) {
            if (! is_null($return = $this->input->query($key, null))) {
                if (is_array($return)) {
                    return $return;
                }
                break;
            }
        }
        
        if (empty($return)) {
            return null;
        }
        
        if (is_string($return)) {
            if (strpos($return, ',')) {
                $return = explode(',', $return);
            } else {
                $return = array((int) $return);
            }
        }
        
        return $return;
    }
    
    /**
     * 返回错误提示信息
     *
     * @param array
     */
    public function _commonPrompt($arr = array())
    {
        $p = http_build_query($arr);
        header("Location: /commonPrompt?{$p}");
        exit;
    }
}

// End ^ Native EOL ^ UTF-8