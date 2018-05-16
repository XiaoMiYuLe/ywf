<?php

/**
 * 获取图片的宽高
 */
class Support_Image_Info
{

    /**
     * 获取图片的宽高 - 支持批量
     *
     * @param string|array $image 图片路径
     *      批量模式，并且以字符串形式传入时，以半角逗号进行分隔，同时，请确保单条图片路径中不包含半角逗号
     * @return array
     */
    public static function Wh($image)
    {
        /* 若为空，则直接空值返回 */
        if (empty($image)) {
            return null;
        }
        
        /* 读取配置文件 */
        $url_mapping = Zeed_Config::loadGroup('urlmapping');
        
        /* 图片基础数据处理，将字符串型转为数组 */
        if (is_string($image)) {
            $image = explode(',', $image);
        }
        
        /* 获取图片的宽高 */
        if (! empty($image)) {
            $image_info = array();
            foreach ($image as $k => $v) {
                $url_whole = $v;
                if (false === strpos($v, 'http://')) {
                    $url_whole = $url_mapping['upload_cdn'] . '/uploads' . $v;
                }
                
                if (@fopen($url_whole, 'r')) {
                    // 获取单张图片信息
                    $img_info = getimagesize($url_whole);
                    
                    // 组织返回数据
                    $image_info[$k] = array(
                            'url_whole' => $url_whole,
                            'url_simple' => $v,
                            'width' => $img_info[0],
                            'height' => $img_info[1]
                    );
                } else {
                    $image_info[$k] = null;
                }
            }
        }
        
        return $image_info;
    }
}
