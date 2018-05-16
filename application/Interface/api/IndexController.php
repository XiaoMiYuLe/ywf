<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-3-21
 * @version    SVN: $Id$
 */

class IndexController extends InterfaceApiAbstract
{
    /**
     * 接口入口 - 对内接口调用
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $res = $this->_validate();
        try {
            
            if ($res['status'] != 0) {
                throw new Zeed_Exception($res['error']);
            }
            
            $params = $res['data']['params'];
            $params_app = $res['data']['params_app'];
            
            $apimap = Zeed_Config::loadGroup('apimap');
            if (! $apimap) {
                throw new Zeed_Exception('配置文件 apimap.php 缺失');
            }
            
            $key = $params_app['app'] . '.' . $params_app['class'];
            if (! isset($apimap[$key])) {
                throw new Zeed_Exception('配置文件 apimap.php 中 {$key} 参数缺失');
            }
            
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setMsg($e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $result = $apimap[$key]['class']::run($params);
        
        /* 接结果进行过滤 */
        $result = $this->_filter($result);
        
        $this->setStatus($result['status']);
        $this->setMsg($result['error']);
        $this->setData('data', $result['data']);
        
        return self::RS_SUCCESS;
    }

    private function _validate()
    {
        $res = array('status' => 0, 'error' => '', 'data' => '');
        
        try {
            
            if (! $this->input->isPOST()) {
                throw new Zeed_Exception('请求方式不正确');
            }
            
            $configs_apps = Zeed_Config::loadGroup('interface.apps');
            if (! $configs_apps) {
                throw new Zeed_Exception('配置文件 interface.php 缺失');
            }
            
            /* 获取参数，并做基础处理 */
            $params = $this->input->post();
            if (empty($params['app']) || empty($params['class']) || empty($params['sign'])) {
                throw new Zeed_Exception('缺少参数或参数错误');
            }
            
            /* 获取密钥 */
            if ($configs_apps[$params['app']]) {
                $secret = $configs_apps[$params['app']]['secret'];
            } else {
                $secret = $configs_apps['default']['secret'];
            }
            
            $sign_local = MD5($params['app'] . $params['class'] . $secret);
            if ($sign_local !== $params['sign']) {
                throw new Zeed_Exception('未经授权，拒绝访问');
            }
        } catch (Zeed_Exception $e) {
            $res['status'] = 1;
            $res['error'] = $e->getMessage();
            return $res;
        }
        
        
        $res['data']['configs_apps'] = $configs_apps[$params['app']];
        $res['data']['params_app'] = array(
                'app' => $params['app'],
                'class' => $params['class']
        );
        unset($params['app'], $params['class'], $params['sign']);
        $res['data']['params'] = $params;
        
        /* 处理上传图片 */
        if (! empty($_FILES)) {
            $this->addUploadFile($_FILES);
        }
        
        return $res;
    }
    
    /**
     * 上传图片 - 支持多张
     */
    public function addUploadFile ($file)
    {
        /* 取得该数组的key */
        foreach ($file as $k => $v) {
            if ($v['error'] == UPLOAD_ERR_OK) {
                $upload_file = $v['tmp_name'];
                $_POST[$k] = "@{$upload_file}";
            }
        }
    }
    
    /**
     * 对接口返回的结果进行过滤
     */
    private function _filter ($arr)
    {
        $arr_json = json_encode($arr);
        $arr_json = str_replace(':null', ':""', $arr_json);
        return json_decode($arr_json, true);
    }
}

// End ^ Native EOL ^ UTF-8