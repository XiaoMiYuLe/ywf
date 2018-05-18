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

require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$smarty->assign($data);

/* 处理关联属性数据 */
if (! empty($data['properties'])) {
    $property_id_arr = array();
    foreach ($data['properties'] as $k => $v) {
        $property_id_arr[] = $v['property_id'];
    }
    $property_ids = implode(',', $property_id_arr);
    
    /* 获取属性信息 */
    $properties = Trend_Model_Property_To_Group::instance()->getPropertyInfo($data['property_group_id'], $property_id_arr);
    
    $smarty->assign('property_ids', $property_ids);
    $smarty->assign('properties', $properties);
}

$smarty->display('propertygroup.edit.html');

// End ^ Native EOL ^ UTF-8