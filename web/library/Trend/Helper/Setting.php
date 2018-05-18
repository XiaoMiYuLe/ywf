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
class Trend_Helper_Setting
{
    /**
     * 获取平台设置
     *
     * @param string|array $name 参数名，如果是字符串，则以半角逗号进行拼接
     * @param string $format_type 对结果进行格式化。null：默认为数组；json：json 格式；serialize：序列化；
     * @return array|string
     */
    public static function platform($name = null, $format_type = null)
    {
        /* 获取设置信息 */
        if ($name) {
            if (is_string($name)) {
                $name = explode(',', $name);
            }
            foreach ($name as $k => &$v) {
                $v = "'" . $v . "'";
            }
            $name = implode(',', $name);
            
            $where = "name IN ({$name})";
            $settings = Trend_Model_Setting::instance()->fetchByWhere($where);
        } else {
            $settings = Trend_Model_Setting::instance()->getAllSettings();
        }
        
        /* 设置基本信息处理 */
        if (! empty($settings)) {
            foreach ($settings as &$v) {
                // 处理 checkbox 的设置值
                if ($v['val_inputtype'] == 'checkbox') {
                    $v['val'] = $v['val'] ? explode(',', $v['val']) : array();
                }
                
                // 处理设置中的可选值
                if (! $v['val_options']) {
                    continue;
                }
                
                $options = explode(',', $v['val_options']);
                foreach ($options as $kk => $vv) {
                    $options_single = explode(':', $vv);
                    $v['val_options_arr'][$kk]['option_title'] = $options_single[0];
                    $v['val_options_arr'][$kk]['option_value'] = $options_single[1];
                }
            }
        }
        
        if ($format_type == 'json') {
            $settings = json_encode($settings);
        } elseif ($format_type == 'serialize') {
            $settings = serialize($settings);
        }
        
        return $settings;
    }
    
}

// End ^ Native EOL ^ UTF-8