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
class Store_Model_Content extends Zeed_Db_Model
{

    /**
     * @var string The table name.
     */
    protected $_name = 'content';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'store_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'store_';

    /**
     * 检查商户是否存在
     * 
     * @param integer $store_id
     *            商户ID
     * @param string $store_type
     *            商户类型
     * @param integer $userid
     *            商户对应的用户ID
     * @return boolean Description
     */
    public function checkExists ($store_id, $store_type = NULL, $userid = NULL)
    {
        $query = $this->select()
            ->from($this->getTable())
            ->columns($this->_primary)
            ->where('store_id = ?', $store_id, Zend_Db::INT_TYPE);
        if ($store_type !== NULL) {
            $query->where('store_type = ?', $store_type);
        }
        if ($userid != NULL) {
            $query->where('userid = ?', $userid, Zend_Db::INT_TYPE);
        }
        return $query->query()->fetch();
    }

    /**
     * 添加商铺信息
     * 
     * @param unknown_type $set            
     * @param unknown_type $isAddUser            
     */
    public function addStore ($set, $isAddUser = false)
    {
        unset($set['store_id']);
        
        try {
            if ($isAddUser) {
                $user_set = array(
                        'username' => $set['username'],
                        'password' => $set['password'],
                        'phone' => $set['tel'],
                        'email' => $set['email'],
                        'realname' => $set['legalp_name'],
                        'company_name' => $set['store_name']
                );
                
                $admin_user_id = Cas_Helper_CreateUser::register($user_set);
                if (empty($admin_user_id)) {
                    return false;
                }
                unset($set['username']);
                unset($set['password']);
                $set['userid'] = $admin_user_id;
            }
            
            $logo_files = $set['logo'];
            if (! empty($logo_files['name'])) {
                $logo_files_upload = Support_Attachment::upload($logo_files);
                if ($logo_files['error'] == UPLOAD_ERR_OK) {
                    $set['logo'] = $logo_files_upload['filepath'];
                } else {
                    Cas_Helper_CreateUser::deleteUsr($admin_user_id);
                    return false;
                }
            } else {
                unset($set['logo']);
            }
            
            $files = $set['business_image'];
            if (! empty($files['name'])) {
                $files_upload = Support_Attachment::upload($files);
                if ($files['error'] == UPLOAD_ERR_OK) {
                    $set['business_image'] = $files_upload['filepath'];
                } else {
                    Cas_Helper_CreateUser::deleteUsr($admin_user_id);
                    return false;
                }
            } else {
                unset($set['business_image']);
            }
            
            $store_id = $this->addForEntity($set);
            return $store_id;
        } catch (Exception $e) {
            Cas_Helper_CreateUser::deleteUsr($admin_user_id);
            throw new Zeed_Exception($e->getMessage(), $e->getCode());
        }
        
        return false;
    }

    /**
     * 修改商铺信息
     * 
     * @param unknown_type $set            
     * @throws Zeed_Exception
     */
    public function editStore ($set)
    {
        $current_store_id = $set['store_id'];
        unset($set['store_id']);
        
        try {
            
            $logo_files = $set['logo'];
            if (! empty($logo_files['name'])) {
                $logo_files_upload = Support_Attachment::upload($logo_files);
                if ($logo_files['error'] == UPLOAD_ERR_OK) {
                    $set['logo'] = $logo_files_upload['filepath'];
                } else {
                    return false;
                }
            } else {
                unset($set['logo']);
            }
            
            $files = $set['business_image'];
            if (! empty($files['name'])) {
                $files_upload = Support_Attachment::upload($files);
                if ($files['error'] == UPLOAD_ERR_OK) {
                    $set['business_image'] = $files_upload['filepath'];
                } else {
                    $this->rollBack();
                    return false;
                }
            } else {
                unset($set['business_image']);
            }
            
            $store_id = $this->updateForEntity($set, $current_store_id);
            return $store_id;
        } catch (Exception $e) {
            throw new Zeed_Exception($e->getMessage(), $e->getCode());
        }
        
        return false;
    }

    /**
     *
     * @return Store_Model_Content
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8