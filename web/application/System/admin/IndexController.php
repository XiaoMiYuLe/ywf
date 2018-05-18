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

class IndexController extends SystemAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 推广规则
     */
    public function generalizeRule()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where[] = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0] ? $content[0] : array();
    	$this->setData('data', $data);
    	$this->addResult(self::RS_SUCCESS, 'php', 'generalize.rule');
    	return parent::multipleResult(self::RS_SUCCESS);
    }
    
    
    public function generalizeruleAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        if ($this->input->isPOST()) {
            $this->generalizeruleAddSave();
            return self::RS_SUCCESS;
        }
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'generalize.rule');
        return parent::multipleResult(self::RS_SUCCESS);
    }
  
    /**
     * 添加 - 保存
     */
    public function generalizeruleAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                if(!empty($set['data']['generalize_rule'])){
                    $generalize_rule  =array(
                            'generalize_rule'=> $set['data']['generalize_rule']
                    );
                    System_Model_Manage::instance()->update($generalize_rule, null, null, null);
                }
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add generalize_rule failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    

    /**
     * 短信推广内容
     */
    public function noteGeneralize()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0];
    	$this->setData('data', $data);
    	$this->addResult(self::RS_SUCCESS, 'php', 'note.generalize');
    	return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function notegeneralizeAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
       
        if ($this->input->isPOST()) {
            $this->notegeneralizeAddSave();
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'note.generalize');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存
     */
    public function notegeneralizeAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $note_generalize  =array(
                        'note_generalize'=> $set['data']['note_generalize']
                );
                
                System_Model_Manage::instance()->update($note_generalize, null, null, null);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add note_generalize failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 平台介绍
     */
    public function platformIntroduce()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'platform.introduce');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function platformintroduceAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
         
        if ($this->input->isPOST()) {
            $this->platformintroduceAddSave();
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'platform.introduce');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存
     */
    public function platformintroduceAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $platform_introduce  =array(
                        'platform_introduce'=> $set['data']['platform_introduce']
                );
    
                System_Model_Manage::instance()->update($platform_introduce, null, null, null);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add platform_introduce failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 我们的愿景
     */
    public function ourVision()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'our.vision');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function ourvisionAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
         
        if ($this->input->isPOST()) {
            $this->ourvisionAddSave();
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'our.vision');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存
     */
    public function ourvisionAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $our_vision  =array(
                        'our_vision'=> $set['data']['our_vision']
                );
    
                System_Model_Manage::instance()->update($our_vision, null, null, null);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add our_vision failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 经营理念
     */
    public function managementIdea()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'management.idea');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function managementideaAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
         
        if ($this->input->isPOST()) {
            $this->managementideaAddSave();
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'management.idea');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存
     */
    public function managementideaAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $management_idea  =array(
                        'management_idea'=> $set['data']['management_idea']
                );
    
                System_Model_Manage::instance()->update($management_idea, null, null, null);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add management_idea failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 公司介绍
     */
    public function companyIntroduce()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'company.introduce');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function companyintroduceAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
         
        if ($this->input->isPOST()) {
            $this->companyintroduceAddSave();
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'company.introduce');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存
     */
    public function companyintroduceAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $company_introduce  = array(
                        'company_introduce'=> $set['data']['company_introduce']
                );
    
                System_Model_Manage::instance()->update($company_introduce, null, null, null);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add company_introduce failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 安全保障
     */
    public function securityAssurance()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'security.assurance');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function securityAssuranceAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
         
        if ($this->input->isPOST()) {
            $this->securityAssuranceAddSave();
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'security.assurance');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存
     */
    public function securityAssuranceAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $security_assurance  = array(
                        'security_assurance'=> $set['data']['security_assurance']
                );
    
                System_Model_Manage::instance()->update($security_assurance, null, null, null);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add security_assurance failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    
    /**
     * 联系我们
     */
    public function contactUs()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'contact.us');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function contactusAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
         
        if ($this->input->isPOST()) {
            $this->contactusAddSave();
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'contact.us');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存
     */
    public function contactusAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $contact_us  = array(
                        'contact_us'=> $set['data']['contact_us']
                );
    
                System_Model_Manage::instance()->update($contact_us, null, null, null);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add contact_us failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 经纪背景图片
     * 
     */
    public function ecomanImage()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'ecoman.image');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 经纪协议
     *
     */
    public function ecomanagreement()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $where = "1=1";
        $content = System_Model_Manage::instance()->fetchByWhere($where);
        $data['content'] = $content[0];
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'ecomanagreement');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function  ecomanagreementAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
         
        if ($this->input->isPOST()) {
            $this-> ecomanagreementSave();
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'ecoman.image');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    /**
     * 添加 - 保存
     */
    public function ecomanagreementSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                $ecoman_agreement  =array(
                    'ecoman_agreement'=> $set['data']['ecoman_agreement']
                );
    
               $a =  System_Model_Manage::instance()->update($ecoman_agreement, null, null, null);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add platform_introduce failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    public function ecomanimageAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
         
        if ($this->input->isPOST()) {
            $this->ecomanimageAddSave();
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'ecomanagreement');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    
    
    /**
     * 添加 - 保存
     */
    public function ecomanimageAddSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                if ($set['data']['type'] != 2) {
            	    $files = $set['data']['ecoman_image'];
            	    
            	    if ($files['name']) {
            	    	$files_upload = Support_Attachment::upload($files);
            	    	
            	           
            	    	if ($files['error'] == UPLOAD_ERR_OK) {
            	    		$set['data']['ecoman_image'] = $files_upload['filepath'];
            	    	} else {
            	    		throw new Zeed_Exception ('上传图片出现一些意外');
            	    	}
            	    } else {
            	    	unset($set['data']['ecoman_image']);
            	    }
            	}
            	$ecoman_image  = array(
            	        'ecoman_image'=> $set['data']['ecoman_image']
            	);
                System_Model_Manage::instance()->update($ecoman_image, null, null, null);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Add ecoman_image failed : ' . $e->getMessage());
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
                'generalize_rule' =>$this->input->post('generalize_rule'),
                'note_generalize' =>$this->input->post('note_generalize'),
                'platform_introduce' =>$this->input->post('platform_introduce'),
                'our_vision' =>$this->input->post('our_vision'),
                'management_idea' =>$this->input->post('management_idea'),
                'company_introduce' =>$this->input->post('company_introduce'),
                'security_assurance' =>$this->input->post('security_assurance'),
                'register_agreement' =>$this->input->post('register_agreement'),
                'ecoman_image' =>$_FILES['ecoman_image'],
                'contact_us' =>$this->input->post('contact_us'),
                'ecoman_agreement' =>$this->input->post('ecoman_agreement'),
                
        );
        return $res;
    }
    

}

// End ^ Native EOL ^ UTF-8