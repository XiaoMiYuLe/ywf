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
class GoodsAdminAbstract extends AdminAbstract
{
    /**
     * 保存商品前的预处理
     */
    protected function prepareSave()
    {
        /**
         * raw data for $_POST
         */
        $data = $this->input->post();
        
        /**
         * validating userid
         */
        if (! isset($data['userid'])) {
            $loggedInUser = Com_Admin_Authorization::getLoggedInUser();
            $data['userid'] = $loggedInUser['userid'];
        }
        
        /**
         * validating UUID
         * 
         * @todo 商品自动保存时需要用到该字段
         */
        if (! isset($data['uuid'])) {
            $data['uuid'] = Zeed_Util_UUID::generate();
        } elseif (0) {
            $this->setStatus(1);
            $this->setError('uuid error');
        }
    
        $this->_data['data'] = $data;
    
        return $this->_data;
    }
    
    /**
     * 处理 content_id 参数(alias: content_ids)
     *
     * @return array
     */
    public function validateContentid()
    {
        /* 对传入参数做基础处理 */
        $mapping = array('content_id', 'content_ids');
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
}

// End ^ Native EOL ^ UTF-8