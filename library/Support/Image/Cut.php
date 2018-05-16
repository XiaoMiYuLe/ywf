<?php

/**
 * 将图片切割成九块并保存
 */
class Support_Image_Cut
{
    /**
     * 执行切割
     * 
     * @param string $img 完整路径的图片
     * @param string $save_path 要保存的图片地址
     * @return boolean
     */
    public static function run ($img, $save_path = ZEED_PATH_UPLOAD)
    {
        if (! $img) {
            return false;
        }
        
        /* 获取图片名称等信息 */
        $img_name = substr(strrchr($img, '/'), 1);
        $img_suffix = strrchr($img_name, '.');
        $img_prefix = str_replace($img_suffix, '', $img_name);
        
        /* 获取图片基本信息 */
        $img_info = getimagesize($img);
        
        /* 计算截取时的步进 */
        $d_x = floor($img_info[0] / 3);
        $d_y = floor($img_info[1] / 3);
        
        $n = 1;
        for ($i = 0; $i < 3; $i++) {
            for ($k = 0; $k < 3; $k++) {
                $start_w = $d_x * $k;
                $start_h = $d_y * $i;
                $img_cut_path = $save_path . $img_prefix . '_' . $n . $img_suffix;
                Widget_Image_Cut::run($img, $img_cut_path, $start_w, $start_h, $d_x, $d_y);
                
                $n++;
            }
        }
        
        return true;
    }
    
    /**
     * 根据给定的文件，获取切割后的九张图片的地址
     * 
     * @param string $img
     * @return boolean|array
     */
    public static function getCutImages ($img)
    {
        if (! $img) {
            return false;
        }
        
        $img_name = substr(strrchr($img, '/'), 1);
        $img_suffix = strrchr($img_name, '.');
        $img_prefix = str_replace($img_suffix, '', $img);
        
        $cut_imgs = array();
        for ($i = 1; $i < 10; $i++) {
            $cut_imgs[] = $img_prefix . '_' . $i . $img_suffix;
        }
        
        return $cut_imgs;
    }
}
