<?php

/**
 * 获取图片的地址
 */
class Support_Image_Url
{
    /**
     * 获取图片的地址
     *
     * @param string $image_path 图片路径
     * @param string $delimiter 是否多张并进行分割
     * @return string/array
     */
    public static function getImageUrl ($image_path, $delimiter = false)
    {
        if (empty($image_path)) {
            return '';
        }
        
        $config = Zeed_Config::loadGroup('urlmapping');
        $domain = $config['upload_cdn'];
        
        if ($delimiter) {
            $image_path = explode('|', $image_path);
        }
        
        if (is_array($image_path)) {
            foreach ($image_path as $k => $v) {
                if (false === strpos($v, $config['upload_url'] . '/')) {
                    $image_path[$k] = $domain . $config['upload_url'] . $v;
                } else {
                    $image_path[$k] = $domain . $v;
                }
            }
        } else {
            if (false === strpos($image_path, $config['upload_url'] . '/')) {
                $image_path = $domain . $config['upload_url'] . $image_path;
            } else {
                $image_path = $domain . $image_path;
            }
        }
        return $image_path;
    }
}
