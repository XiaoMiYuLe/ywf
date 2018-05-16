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
 * @since 2010-12-30
 * @version SVN: $Id$
 */


class Store_ContentHelper
{
    /**
     * 店铺设置信息
     *
     * @var array
     */
    protected static $_extSetting;
    
    /**
     * @var Store_Entity_Content
     */
    protected static $_contentObj;
    
    /**
     * 添加商户
     * 
     * @param array $set
     * @return integer 当返回值为0时添加失败
     */
    public static function addContent($set)
    {
        if (self::prepare($set)) {
            self::$_contentObj->ctime = self::$_contentObj->mtime = date(DATETIME_FORMAT);
            
            /**
             * 1.insert into content, return store_id
             * 2.insert cas user info, return userid
             * 3.update store content for userid
             */
            self::$_contentObj->store_id = (int)self::$_contentObj->store_id;
            self::$_contentObj->store_id = Store_Model_Content::instance()->addForEntity(self::$_contentObj);
            $userid = Store_Helper_User::register($set);
            if ($userid) {
                $set_update = array('userid' => $userid);
                Store_Model_Content::instance()->updateForEntity($set_update, self::$_contentObj->store_id);
            }
            
            return self::$_contentObj->store_id;
        }
        
        return false;
    }
    
    /**
     * 编辑商户
     * 
     * @param integer $store_id
     * @param array $set
     * @return boolean 当返回值为 0 时，表示更新失败或无更新
     */
    public static function updateContentByStoreid($store_id, $set)
    {
        if (self::prepare($set)) {
            self::$_contentObj->mtime = date(DATETIME_FORMAT);
            
            /**
             * 1.save content
             * 2.save user info
             */
            Store_Model_Content::instance()->updateForEntity(self::$_contentObj, $store_id);
            
            Cas_Model_User::instance()->modifyPassword($set['userid_edit'], $set['password']);
        }
        
        return true;
    }
    
    /**
     * 对代入参数做预处理
     *
     * @param array $set
     * @return boolean
     */
    private static function prepare(& $set)
    {
        /* 处理签约信息 */
        $set['is_signing'] = 1; // 默认为已签约
        
        /* 处理审核通过时间 */
        if ((! $set['store_id'] && $set['is_verify'] == 1) || 
                ($set['store_id'] && $set['is_verify_old'] != 1 && $set['is_verify'] == 1)) {
            $set['verify_time'] = date(DATETIME_FORMAT);
        }
        
        /* 处理设置 - 发布商品是否需要平台审核 */
        if (! isset($set['goods_verify'])) {
            $set['goods_verify'] = 0;
        }
        
        /* 处理店铺初始状态及创建人信息 */
        if (! $set['store_id']) {
            $set['status'] = 1;
            $author = Com_Admin_Authorization::getLoggedInUser();
            $set['creator_userid'] = $author['userid'];
        }
        
        /* 处理营业执照图片 */
        $files_business_image = $_FILES['business_image'];
        if ($files_business_image['error'] === UPLOAD_ERR_OK) {
            $files_upload = Support_Attachment::upload($files_business_image);
            $set['business_image'] = $files_upload['filepath'];
        }
        
        /* 处理营业执照图片 */
        $files_logo = $_FILES['logo'];
        if ($files_logo['error'] === UPLOAD_ERR_OK) {
            $files_upload = Support_Attachment::upload($files_logo);
            $set['logo'] = $files_upload['filepath'];
        }
        
        unset($set['rev'], $set['ctime']);
        
        self::$_contentObj = new Store_Entity_Content();
        self::$_contentObj->fromArray($set);
        
        return true;
    }
    
    /**
     * 校验公司名称是否可用
     * 
     * @param string $company_name
     * @return boolean
     */
    public static function isCompanynameAvailable($company_name)
    {
        $store = Store_Model_Content::instance()->fetchByFV('company_name', $company_name);
        if (is_array($store) && count($store)) {
            return false;
        }
        return true;
    }
    
    /**
     * 校验店铺名称是否可用
     * 
     * @param string $store_name
     * @return boolean
     */
    public static function isStorenameAvailable($store_name)
    {
        $store = Store_Model_Content::instance()->fetchByFV('store_name', $store_name);
        if (is_array($store) && count($store)) {
            return false;
        }
        return true;
    }
    
    /**
     * 回溯
     */
    public static function revertContentByContentid()
    {

    }
    
    /**
     * 从回收站恢复
     */
    public static function restoreContentByContentid()
    {

    }
}

// End ^ Native EOL ^ UTF-8