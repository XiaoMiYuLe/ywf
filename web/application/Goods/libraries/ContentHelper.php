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


class ContentHelper extends Trend_Content
{
    /**
     * 商品详细信息
     *
     * @var array
     */
    protected static $_extDetail;
    
    /**
     * 扩展信息(非用户自定义字段)
     *
     * @var array
     */
    protected static $_extSet;
    
    /**
     * 允许的扩展属性(用户自定义字段)
     *
     * @var array
     */
    protected static $_extProperties;
    
    /**
     * 允许的规格(用户自定义字段)
     *
     * @var array
     */
    protected static $_extSpecs;
    
    /**
     * 自定义表字段
     *
     * @var array
     */
    protected static $_extendFields;
    
    /**
     * @var Goods_Entity_Content
     */
    protected static $_contentObj;
    
    /**
     * 添加商品
     * 
     * @param array $set
     * @return integer 当返回值为0时添加失败
     */
    public static function addContent($set, $options = null)
    {
        if (self::prepare($set)) {
            self::$_contentObj->ctime = self::$_contentObj->mtime = date(DATETIME_FORMAT);
            self::$_contentObj->userid = $set['userid'];
            
            /**
             * 1.insert into content, return content_id
             * 2.save detail, property, spec, attachment, related goods...
             */
            $content_id = 0;
            $new_id     = 0;
            try {
                Goods_Model_Content::instance()->beginTransaction();
                
                if (isset($set['is_spec']) && $set['is_spec'] == 'on') {
                    self::$_contentObj->is_spec = 1;
                    foreach (self::$_extSpecs as $key => $item) {
                        self::$_contentObj->property_related = $item['property_related'];
                        self::$_contentObj->sku = $item['sku'];
                        self::$_contentObj->weight = $item['weight'];
                        self::$_contentObj->price = $item['price'];
                        self::$_contentObj->price_market = $item['price_market'];
                        self::$_contentObj->price_cost = $item['price_cost'];
                        self::$_contentObj->stock = $item['stock'];
                        self::$_contentObj->is_shelf = $item['is_shelf'];
                        self::$_contentObj->parent_id = $key <= 0 ? 0 : $content_id;
                        $new_id = Goods_Model_Content::instance()->addForEntity(self::$_contentObj);
                        
                        $key <= 0 && $content_id = $new_id;
                    }
                } else {
                    self::$_contentObj->is_spec = 0;
                    $content_id = Goods_Model_Content::instance()->addForEntity(self::$_contentObj);
                }
                
                if (empty($content_id)) {
                    throw new Zeed_Exception('添加数据失败');
                }
                
            } catch (Zeed_Exception $e) {
                Goods_Model_Content::instance()->rollBack();
                return false;
            }
            
            self::$_contentObj->content_id = $content_id;
            self::saveContentDetail(true);
            self::saveContentProperty(true);
            self::saveContentAttachment(true);
            self::saveContentRelated(true);
            
            return self::$_contentObj->content_id;
        }
        
        return false;
    }
    
    /**
     * 编辑商品
     * 
     * @param integer $content_id
     * @param array $set
     * @return boolean 当返回值为 0 时，表示更新失败或无更新
     */
    public static function updateContentByContentid($content_id, $set)
    {
        if (self::prepare($set)) {
            self::$_contentObj->mtime = date(DATETIME_FORMAT);
            self::$_contentObj->userid = $set['userid'];
            
            /**
             * 1.save content
             * 2.save detail, property, spec, attachment, related goods...
             */
            try {
                Goods_Model_Content::instance()->beginTransaction();
                if (isset($set['is_spec']) && $set['is_spec'] == 'on') { // 如果本次编辑开启了规格
                    if ($set['old_is_spec'] == 1) { // 如果原来规格已开启
                        if (isset($set['specs']) && ! empty($set['specs']) && count($set['specs'])) {
                            self::$_contentObj->is_spec = 1;
                            foreach (self::$_extSpecs as $key => $item) {
                                $this_del = $item['is_del'];
                                $this_id = $item['content_id'];
                                self::$_contentObj->property_related = $item['property_related'];
                                self::$_contentObj->sku = $item['sku'];
                                self::$_contentObj->weight = $item['weight'];
                                self::$_contentObj->price = $item['price'];
                                self::$_contentObj->price_market = $item['price_market'];
                                self::$_contentObj->price_cost = $item['price_cost'];
                                self::$_contentObj->stock = $item['stock'];
                                self::$_contentObj->is_shelf = $item['is_shelf'];
            
                                // 本次有 ：更新 或 删除 或 追加
                                if ($this_id && $this_del == 0) { // 编辑
                                    self::$_contentObj->parent_id = $key == 0 ? 0 : $content_id;
                                    if ($key == 0) {
                                        self::$_contentObj->parent_id = 0;
                                    } else {
                                        self::$_contentObj->parent_id = $content_id;
                                        self::$_contentObj->content_id = $this_id;
                                    }
                                    Goods_Model_Content::instance()->updateForEntity(self::$_contentObj, $this_id);
                                } elseif ($this_id && $this_del == 1) { // 删除
                                    Goods_Model_Content::instance()->update(array(
                                    'is_del' => 1
                                    ), "content_id=$this_id");
                                } elseif (empty($this_id) && empty($this_del)) { // 追加
                                    self::$_contentObj->store_id = $set['store_id'];
                                    self::$_contentObj->ctime = date(DATETIME_FORMAT);
                                    self::$_contentObj->mtime = self::$_contentObj->ctime;
                                    self::$_contentObj->userid = $set['userid'];
                                    self::$_contentObj->is_spec = 1;
                                    self::$_contentObj->parent_id = $content_id;
                                    if ($key <= 0) { // 当点击选错了，并继续加入规格：第一，把第一条做为主商品；第二，把此主商品下的规格属性is_del=1
                                        self::$_contentObj->parent_id = 0;
                                        Goods_Model_Content::instance()->updateForEntity(self::$_contentObj, $content_id);
                                        Goods_Model_Content::instance()->update(array(
                                        'is_del' => 1
                                        ), "parent_id=$content_id");
                                    } else {
                                        unset(self::$_contentObj->content_id);
                                        Goods_Model_Content::instance()->addForEntity(self::$_contentObj);
                                    }
                                }
                            }
                            self::$_contentObj->content_id = $content_id;
                        } else {
                            return false;
                        }
                    } else { // 如果原来规格未开启
                        if (isset($set['specs']) && ! empty($set['specs']) && count($set['specs'])) {
                            self::$_contentObj->is_spec = 1;
                            foreach (self::$_extSpecs as $key => $item) {
                                self::$_contentObj->property_related = $item['property_related'];
                                self::$_contentObj->sku = $item['sku'];
                                self::$_contentObj->weight = $item['weight'];
                                self::$_contentObj->price = $item['price'];
                                self::$_contentObj->price_market = $item['price_market'];
                                self::$_contentObj->price_cost = $item['price_cost'];
                                self::$_contentObj->stock = $item['stock'];
                                self::$_contentObj->is_shelf = $item['is_shelf'];
            
                                if ($key <= 0) {
                                    self::$_contentObj->parent_id = 0;
                                    Goods_Model_Content::instance()->updateForEntity(self::$_contentObj, $content_id);
                                } else {
                                    unset(self::$_contentObj->content_id);
                                    self::$_contentObj->parent_id = $content_id;
                                    self::$_contentObj->ctime = self::$_contentObj->mtime = date(DATETIME_FORMAT);
                                    Goods_Model_Content::instance()->addForEntity(self::$_contentObj);
                                }
                            }
                            self::$_contentObj->content_id = $content_id;
                        } else {
                            return false;
                        }
                    }
                } else { // 如果本次编辑未开启规格
                    if ($set['old_is_spec'] == 1) { // 如果原来规格已开启
                        self::$_contentObj->is_spec = 0;
                        self::$_contentObj->property_related = '';
                        Goods_Model_Content::instance()->updateForEntity(self::$_contentObj, $content_id);
                        Goods_Model_Content::instance()->update(array(
                        "is_del" => 1
                        ), "parent_id=$content_id");
                    } else { // 如果原来也规格未开启
                        Goods_Model_Content::instance()->updateForEntity(self::$_contentObj, $content_id);
                    }
                }
            
                self::saveContentDetail(false);
                self::saveContentProperty(false);
                self::saveContentAttachment(false);
                self::saveContentRelated(false);
            
            } catch (Zeed_Exception $e) {
                Goods_Model_Content::instance()->rollBack();
                return false;
            }
            
            Goods_Model_Content::instance()->commit();
            return true;
            
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
        /* 处理属性 */
        if (isset($set['property'])) {
            $prepare_property = array();
            if (is_array($set['property']) && count($set['property'])) {
                $set['property_detail'] = implode(',', $set['property']);
                foreach ($set['property'] as $k => $v) {
                    $property_temp = explode('_', $v);
                    $prepare_property[$k]['property_id'] = $property_temp[0];
                    $prepare_property[$k]['property_value_id'] = $property_temp[1];
                }
            }
            self::$_extProperties = $prepare_property;
        } else {
            self::$_extProperties = array();
        }
        
        /* 处理规格 */
        if (isset($set['specs'])) {
            $prepare_spec = array();
            if (is_array($set['specs']) && count($set['specs'])) {
                foreach ($set['specs'] as $k => $v) {
                    $prepare_spec[$k] = json_decode($v, true);
                }
            }
            self::$_extSpecs = $prepare_spec;
        } else {
            self::$_extSpecs[0] = $set;
        }
        
        /* Attachment - 图片相册 */
        if (isset($set['attachment_ids']) && ! empty($set['attachment_ids'])) {
            if (is_array($set['attachment_ids'])) {
                self::$_extSet['attachment_ids'] = array_unique($set['attachment_ids']);
                $set['attachment_ids'] = implode(',', self::$_extSet['attachment_ids']);
            } else {
                $set['attachment_ids'] = explode(',', str_replace('attachmentid_', '', $set['attachment_ids']));
                self::$_extSet['attachment_ids'] = array_unique($set['attachment_ids']);
                $set['attachment_ids'] = implode(',', self::$_extSet['attachment_ids']);
            }
        }
        
        /* Attachment - 处理图片封面 */
        if (isset($set['image_default'])) {
            if (! $set['image_default']) { // 默认取相册中的第一张做为封面
                $image_default = Trend_Model_Attachment::instance()->fetchByPK(self::$_extSet['attachment_ids']);
                if (! empty($image_default)) {
                    $set['image_default'] = $image_default[0]['filepath'];
                }
            } else { // 对图片地址做处理
                $set['image_default'] = str_replace('/uploads', '', $set['image_default']);
            }
        }
        
        /* goods - 关联商品 */
        if (isset($set['related_content_id']) && ! empty($set['related_content_id'])) {
            if (is_array($set['related_content_id'])) {
                self::$_extSet['related_content_id'] = array_unique($set['related_content_id']);
            } else {
                $set['related_content_id'] = explode(',', $set['related_content_id']);
                self::$_extSet['related_content_id'] = array_unique($set['related_content_id']);
            }
        }
        
        /* Content Detail */
        if (isset($set['memo']) && ! empty($set['memo'])) {
            self::$_extDetail['memo'] = $set['memo'];
        }
        if (isset($set['body']) && ! empty($set['body'])) {
            self::$_extDetail['body'] = $set['body'];
        }
        if (isset($set['meta_title']) && ! empty($set['meta_title'])) {
            self::$_extDetail['meta_title'] = $set['meta_title'];
        }
        if (isset($set['meta_keywords']) && ! empty($set['meta_keywords'])) {
            self::$_extDetail['meta_keywords'] = $set['meta_keywords'];
        }
        if (isset($set['meta_description']) && ! empty($set['meta_description'])) {
            self::$_extDetail['meta_description'] = $set['meta_description'];
        }
        if (isset($set['property_detail']) && ! empty($set['property_detail'])) {
            self::$_extDetail['property'] = $set['property_detail'];
        }
        if (isset($set['specs']) && ! empty($set['specs'])) {
            $spec_temp = json_decode($set['specs'][0], true);
            $property_related_temp = explode(',', $spec_temp['property_related']);
            $property_related_id = array();
            if (! empty($property_related_temp)) {
                foreach ($property_related_temp as $k => $v) {
                    $property_related_temp2 = explode(':', $v);
                    $property_related_id[$k] = $property_related_temp2[0];
                }
            }
            self::$_extDetail['spec'] = implode(',', $property_related_id);
        }
        
        if (isset($set['attachment_ids']) && ! empty($set['attachment_ids'])) {
            self::$_extDetail['attachment'] = $set['attachment_ids'];
        }
        if (isset(self::$_extSet['related_content_id']) && ! empty(self::$_extSet['related_content_id'])) {
            self::$_extDetail['related'] = implode(',', self::$_extSet['related_content_id']);
        }
        
        if (isset($set['specs'])) {
            
            $show_spec = array();
            if (is_array($set['specs']) && count($set['specs'])) {
                foreach ($set['specs'] as $k => $v) {
                    $show_spec_temp = json_decode($v, true);
                    $show_property_related_temp = explode(',', $show_spec_temp['property_related']);
                    if (! empty($show_property_related_temp)) {
                        foreach ($show_property_related_temp as $v) {
                            /* 取得property数据 */
                            $show_property_related_temp2 = explode(':', $v);
                            $cols = array('property_id','label_name','note');
                            $property = Trend_Model_Property::instance()->fetchByPK($show_property_related_temp2[0],$cols);
                            if(!array_key_exists($show_property_related_temp2[0],$show_spec)){
                                $show_spec[$show_property_related_temp2[0]] = $property ? $property[0] : array(); 
                            }
                            /* 取得property_value数据 */
                            $cols = array('property_value_id','property_value','property_image');
                            $property_value = Trend_Model_Property_Value::instance()->fetchByPK($show_property_related_temp2[1],$cols);
                            $show_spec[$show_property_related_temp2[0]]['value'][] = $property_value ? $property_value[0] : array();
                        }
                    }
                }
                $show_spec = array_values($show_spec);
                $set['serialize_specs'] = json_encode($show_spec);
            }
        } else {
           $set['serialize_specs'] = '';
        }
        
        unset($set['rev'], $set['ctime'], $set['property']);
        
        self::$_contentObj = new Goods_Entity_Content();
        self::$_contentObj->fromArray($set);
        
        return true;
    }
    
    /**
     * 保存商品详细信息
     * 
     * @param boolean $newContentFlag
     * @retrun void
     */
    private static function saveContentDetail($newContentFlag = true)
    {
        /* 么有商品详细信息 */
        if (empty(self::$_extDetail)) {
            return;
        }
        
        $content_id = self::$_contentObj->content_id;
        $contentDetail = self::$_extDetail;
        
        if ($newContentFlag) {
            $contentDetail['content_id'] = $content_id;
            Goods_Model_Content_Detail::instance()->addForEntity($contentDetail);
        } else {
            Goods_Model_Content_Detail::instance()->updateForEntity($contentDetail, $content_id);
        }
    }
    
    /**
     * 保存商品属性信息
     *
     * @param boolean $newContentFlag
     * @return void
     */
    private static function saveContentProperty($newContentFlag = true)
    {
        /* 么有扩展属性 */
        if (empty(self::$_extProperties)) {
            return;
        }
        
        $content_id = self::$_contentObj->content_id;
        $contentPropertyNew = self::$_extProperties;
        
        /* 编辑扩展属性 */
        $cpRetainUpdate = array(); // 待更新属性
        if (! $newContentFlag) {
            // 获取原有扩展属性信息
            $contentPropertyExists = Goods_Model_Property::instance()->fetchByFV('content_id', $content_id);
            
            // 处理提交的扩展属性信息
            foreach ($contentPropertyNew as &$v_cpn) {
                $v_cpn['content_id'] = $content_id;
            }
            
            // 过滤提交数据
            if (is_array($contentPropertyExists)) {
                $cpDelete = array();
                foreach ($contentPropertyExists as $_cp) {
                    $goods_property_id = $_cp['goods_property_id'];
                    unset($_cp['goods_property_id']);
                    if (! in_array($_cp, $contentPropertyNew)) {
                        // 删除
                        $cpDelete[] = $goods_property_id;
                    } else {
                        // 保留
                        $cpRetainUpdate[] = $goods_property_id;
                    }
                }
                if (count($cpDelete)) {
                    Goods_Model_Property::instance()->deleteByFV('goods_property_id', $cpDelete);
                }
            }
        }
        
        /* 添加扩展属性 */
        if (count($contentPropertyNew)) {
            foreach ($contentPropertyNew as $v) {
                if (in_array($v, $cpRetainUpdate)) {
                    continue;
                }
                $v['content_id'] = $content_id;
        
                Goods_Model_Property::instance()->addForEntity($v);
            }
        }
    }
    
    
    /**
     * 保存图片信息
     * 
     * @param boolean $newContentFlag
     * @return void
     */
    private static function saveContentAttachment($newContentFlag = true)
    {
        /* 么有图片 */
        if (! isset(self::$_extSet['attachment_ids'])) {
            return;
        }
        
        $content_id = self::$_contentObj->content_id;
        $userid = self::$_contentObj->userid;
        
        /* 编辑关联图片信息 */
        $caRetain = array(); // 待更新图片信息
        if (! $newContentFlag) {
            $contentAttachmentExists = Goods_Model_Attachment::instance()->fetchByFV('content_id', $content_id);
            if (is_array($contentAttachmentExists)) {
                $caDelete = array();
                foreach ($contentAttachmentExists as $_ca) {
                    if (! in_array($_ca['attachmentid'], self::$_extSet['attachment_ids'])) {
                        // 删除
                        $caDelete[] = $_ca['attachmentid'];
                    } else {
                        // 保留
                        $caRetain[] = $_ca['attachmentid'];
                    }
                }
                
                if (count($caDelete)) {
                    Goods_Model_Attachment::instance()->deleteByContentid($content_id, $caDelete);
                }
            }
        }
        
        /* 添加关联图片信息 */
        if (count(self::$_extSet['attachment_ids'])) {
            foreach (self::$_extSet['attachment_ids'] as $v) {
                if (in_array($v, $caRetain)) {
                    continue;
                }
                $set = array('content_id' => $content_id, 'attachmentid' => $v, 'userid' => $userid);
                
                Goods_Model_Attachment::instance()->addForEntity($set);
            }
        }
    }
    
    /**
     * 保存相关商品信息
     * 
     * @param boolean $newContentFlag
     * @return void
     */
    private static function saveContentRelated($newContentFlag = true)
    {
        /* 么有相关商品 */
        if (! isset(self::$_extSet['related_content_id'])) {
            return;
        }
        
        $content_id = self::$_contentObj->content_id;
        
        /* 编辑关联商品信息 */
        $crRetain = array(); // 待更新商品信息
        if (! $newContentFlag) {
            $contentRelatedExists = Goods_Model_Related::instance()->fetchByFV('content_id', $content_id);
            if (is_array($contentRelatedExists)) {
                $crDelete = array();
                foreach ($contentRelatedExists as $_cr) {
                    if (! in_array($_cr['related_content_id'], self::$_extSet['related_content_id'])) {
                        // 删除
                        $crDelete[] = $_cr['related_content_id'];
                    } else {
                        // 保留
                        $crRetain[] = $_cr['related_content_id'];
                    }
                }
                if (count($crDelete)) {
                    Goods_Model_Related::instance()->deleteByContentid($content_id, $crDelete);
                }
            }
        }
        
        /* 添加关联商品信息 */
        if (count(self::$_extSet['related_content_id'])) {
            foreach (self::$_extSet['related_content_id'] as $v) {
                if (in_array($v, $crRetain)) {
                    continue;
                }
                $set = array('content_id' => $content_id, 'related_content_id' => $v);
                
                Goods_Model_Related::instance()->addForEntity($set);
            }
        }
    }
    
    
    /**
     * 将商品扔进回收站 - 逻辑删除
     * 
     * @param integer|array $content_id
     * @return boolean
     */
    public static function trashContentByContentid($content_id)
    {
        if (is_array($content_id)) {
            foreach ($content_id as $_content_id) {
                Goods_Model_Content::instance()->updateStatusByContentid($_content_id, 'is_del', 1);
            }
        } else {
            Goods_Model_Content::instance()->updateStatusByContentid(intval($content_id), 'is_del', 1);
        }
        
        return true;
    }
    
    /**
     * 彻底删除商品 - 扔进回收站表
     * 
     * @param integer|array $content_id
     * @return boolean
     */
    public static function deleteContentByContentid($content_id)
    {
        if (is_array($content_id)) {
            foreach ($content_id as $_content_id) {
                self::_deleteContentByContentid($_content_id);
            }
        } else {
            self::_deleteContentByContentid(intval($content_id));
        }
        
        return true;
    }
    
    /**
     * 彻底删除商品 - 扔进回收站表 - 执行
     *
     * @param integer $content_id
     * @return boolean
     */
    private static function _deleteContentByContentid($content_id)
    {
        $goods = array();
        
        /* 获取商品主体信息 */
        $content = Goods_Model_Content::instance()->fetchByPK($content_id);
        if (empty($content)) {
            return false;
        }
        // 获取除主商品以外的规格商品
        $spec_list = Goods_Model_Content::instance()->fetchByWhere("parent_id in ($content_id)", '', '', '', '');
        if (! empty($spec_list)) {
            foreach ($spec_list as $item) {
                $content[] = $item;
            }
        }
        $goods['content'] = $content;
        
        /* 获取商品详细信息，并和商品主体信息合并 */
        $goods['content_detail'] = null;
        $content_detail = Goods_Model_Content_Detail::instance()->fetchByPK($content_id);
        if (! empty($content_detail)) {
            $goods['content_detail'] = $content_detail[0];
        }
        
        /* 获取商品扩展属性信息 */
        $goods['property'] = Goods_Model_Property::instance()->fetchByFV('content_id', $content_id);
        
        /* 获取商品图片信息 */
        $goods['attachment'] = Goods_Model_Attachment::instance()->fetchByFV('content_id', $content_id);
        
        /* 获取关联商品信息 */
        $goods['related'] = Goods_Model_Content::instance()->fetchByFV('content_id', $content_id);
        
        /* 入回收站表 */
        
        
        // 保存相应信息
        $set = NULL;
        $set['content_id'] = $content_id;
        $set['category'] = $content[0]['category'];
        $set['brand_id'] = $content[0]['brand_id'];
        $set['userid'] = $content[0]['userid'];
        $set['name'] = $content[0]['name'];
        $set['sku'] = $content[0]['sku'];
        $set['image_default'] = $content[0]['image_default'];
        $set['stock'] = $content[0]['stock'];
        $set['weight'] = $content[0]['weight'];
        $set['price'] = $content[0]['price'];
        $set['price_market'] = $content[0]['price_market'];
        $set['price_cost'] = $content[0]['price_cost'];
        $set['data'] = serialize($goods);
        $set['is_del'] = 0;
        $set['ctime'] = date(DATETIME_FORMAT);
        $trash_count = Goods_Model_Content_Trash::instance()->getCount(array(
            'content_id' => $content_id
        ));
        if ($trash_count >= 1) {
            Goods_Model_Content_Trash::instance()->update($set, 'content_id = ' . $content_id);
        } else {
            Goods_Model_Content_Trash::instance()->insert($set);
        }
        return true;
    }
    
    /**
     * 将商品上架/下架
     *
     * @param integer|array $content_id
     * @param integer $status
     * @return boolean
     */
    public static function publishContent($content_id, $status)
    {
        if (is_array($content_id)) {
            foreach ($content_id as $_content_id) {
                Goods_Model_Content::instance()->updateStatusByContentid($_content_id, 'is_shelf', $status);
            }
        } else {
            Goods_Model_Content::instance()->updateStatusByContentid(intval($content_id), 'is_shelf', $status);
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