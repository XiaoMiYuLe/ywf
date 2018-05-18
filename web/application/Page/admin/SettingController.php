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

class SettingController extends PageAdminAbstract
{
    protected $_skip_xss_clean = true;
    
    /**
     * 单页设置
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 读取设置信息 */
        $data['settings'] = Page_Model_Config::instance()->fetchAll()->toArray();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 编辑
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
    
        $data = array();
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'setting.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 编辑 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                foreach ($set['data'] as $k => $v) {
                    $set = array('value' => $v);
                    $where = "name = '{$k}'";
                    Page_Model_Config::instance()->update($set, $where);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('更新设置失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        $res['data'] = array(
                'public_header' => $this->input->post('public_header'),
                'public_footer' => $this->input->post('public_footer'));
        
        return $res;
    }
    
}
// End ^ Native EOL ^ UTF-8