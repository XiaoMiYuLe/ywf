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
 * @since 2011-10-26
 * @version SVN: $Id$
 */
class Trend_Helper_Region
{
    /**
     * 根据地区 ID 获取完整的地区名称路径
     *
     * @param integer $region_id 地区 ID
     * @param boolean $is_contain_country 返回结果中是否包含国家
     * @return string
     */
    public static function getNameAllByRegionid($region_id, $is_contain_country = false)
    {
        $region_id = (int) $region_id;
        
        /* 获取当前地区信息 */
        $region_now = Trend_Model_Region::instance()->fetchByPK($region_id, array('hid', 'grade', 'region_name'));
        if (empty($region_now)) {
            return null;
        }
        $region_now = $region_now[0];
        
        /* 若当前地区已经是顶级，则直接返回地区名称 */
        if ($region_now['grade'] == 1) {
            return $region_now['region_name'];
        }
        
        /* 获取所有父级信息 */
        $hid_arr = explode(':', $region_now['hid']);
        unset($hid_arr[0], $hid_arr[$region_now['grade']]); // 去掉首尾
        $region_parents = Trend_Model_Region::instance()->fetchByPK($hid_arr, array('grade', 'region_name'));
        
        if (empty($region_parents)) {
            return null;
        }
        
        /* 对结果进行排序 */
        $grade_arr = array();
        foreach ($region_parents as $v) {
            $grade_arr[] = $v['grade'];
        }
        array_multisort($grade_arr, SORT_ASC, $region_parents);
        
        /* 处理国家 */
        if (! $is_contain_country) {
            unset($region_parents[0]);
        }
        
        /* 返回地区路径名称 */
        $result_arr = array();
        foreach ($region_parents as $v) {
            $result_arr[] = $v['region_name'];
        }
        
        return implode(' ', $result_arr);
    }
}

// End ^ Native EOL ^ UTF-8