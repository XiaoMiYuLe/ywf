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

class VersionController extends TrendAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 版本管理
     */
    public function version()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where[] = "1=1";
        $content = Trend_Model_Version::instance()->fetchByWhere($where);
        $data['content'] = $content[0] ? $content[0] : array();
    	$this->setData('data', $data);
    	$this->addResult(self::RS_SUCCESS, 'php', 'version.edit');
    	return parent::multipleResult(self::RS_SUCCESS);
    }
    
    
    public function versionAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if ($this->input->isPOST()) {
            $this->versionAddSave();
            return self::RS_SUCCESS;
        }
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'version.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
  
    /**
     * 添加 - 保存
     */
    public function versionAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $arr = array(
                        'web_code' =>$set['data']['web_code'],
                        'ios_code' =>$set['data']['ios_code'],
                        'android_code' =>$set['data']['android_code'],
                        'guide_url' =>$set['data']['guide_url'],
                        'status' =>$set['data']['status'],
                        'mtime' =>date(DATETIME_FORMAT) 
                );
                $result = Trend_Model_Version::instance()->update($arr,"id = 1");
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add trend_version failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    private function _validate ()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $res['data'] = array(
                'web_code' =>$this->input->post('web_code'),
                'ios_code' =>$this->input->post('ios_code'),
                'android_code' =>$this->input->post('android_code'),
                'guide_url' =>$this->input->post('guide_url'),
                'status' =>$this->input->post('status'),
                'mtime' =>date(DATETIME_FORMAT)
        );
        return $res;
    }
    

}

// End ^ Native EOL ^ UTF-8