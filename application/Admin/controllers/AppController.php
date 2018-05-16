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
class AppController extends AdminAbstract
{

    /**
     * 模块列表管理
     */
    public function index ()
    {
        $apps = AppModel::instance()->getAllApps();
        foreach ($apps as &$app) {
            $secret = $app["appsecret"];
            $len = mb_strlen($secret);
            $app["appsecret_simple"] = mb_substr($secret, 0, 3) . '******' . mb_substr($secret, $len - 3, 3);
        }
        $data['apps'] = $apps;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_SUCCESS, 'php', 'app.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加模块
     */
    public function add ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $this->addResult(self::RS_SUCCESS, 'php', 'app.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加模块 - 保存
     */
    public function addSave ()
    {
        $set = $this->_validateApp();
        if ($set['status'] == 0) {
            try {
                AppModel::instance()->addApp($set['data']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add app failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 修改模块
     */
    public function edit ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $this->addResult(self::RS_INPUT, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $appkey = $this->input->query('appkey');
        $app = AppModel::instance()->fetchByPK($appkey);
        if (null === $app || ! is_array($app)) {
            $this->setStatus(1);
            $this->setError('The app is not exist.');
            return self::RS_SUCCESS;
        }
        $data['app'] = $app[0];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'app.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 修改模块 - 保存
     */
    public function editSave ()
    {
        $set = $this->_validateApp();
        if ($set['status'] == 0) {
            try {
                AppModel::instance()->updateApp($set['data'], $set['data']['appkey_now']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit app failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 保存模块－校验
     */
    private function _validateApp ()
    {
        $res = array(
                'status' => 0,
                'error' => null,
                'data' => null
        );
        
        $res['data'] = array(
                'appkey_now' => $this->input->post('appkey_now', ''),
                'appkey' => $this->input->post('appkey'),
                'appsecret' => $this->input->post('appkey') . '_secret',
                'name' => $this->input->post('name')
        );
        
        /* 数据验证 */
        if (empty($res['data']['appkey'])) {
            $res['status'] = 1;
            $res['error'] = '模块名称不能为空';
            return $res;
        }
        
        /* 校验该模块是否已存在 - 仅在编辑状态下，模块名未做改变时不做该判断 */
        if ($res['data']['appkey_now'] != $res['data']['appkey']) {
            $app_info = AppModel::instance()->fetchByPK($res['data']['appkey']);
            if (! empty($app_info)) {
                $res['status'] = 1;
                $res['error'] = '模块已存在';
                return $res;
            }
        }
        
        return $res;
    }

    /**
     * ajax - 删除模块
     */
    public function delete ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isAJAX()) {
            throw new Zeed_Exception('请求方式错误');
        }
        
        $appkey = $this->input->query('appkey');
        
        if (! $appkey) {
            throw new Zeed_Exception('缺少参数，或参数错误');
        }
        
        try {
            AppModel::instance()->deleteByPK($appkey);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除模块失败。 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8