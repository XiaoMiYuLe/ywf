<?php
/**
 * 切割图片 - 按照指定宽高，从图片的指定起始位置切割一张图片出来
 */

class Widget_Image_Cut
{
    /**
     * params
     */
    private static $_res = array('status' => 0, 'error' => null, 'data' => null);
    
    /**
     * 执行切割
     */
    public static function run( $img, $save_path, $start_w = 0, $start_h = 0, $width = 0, $height = 0 )
    {
        try {
            
            if (! $img) {
                throw new Zeed_Exception('请选择原始图片');
            }
            if (! $save_path) {
                throw new Zeed_Exception('请提供保存路径');
            }
            
            $allow_mime = array(
                    'image/jpeg',
                    'image/png'
            );
            
            $img_info = getimagesize($img);
            
            if (! in_array($img_info['mime'], $allow_mime)) {
                
                throw new Zeed_Exception("暂不支持 {$img_info['mime']} 格式");
            }
        } catch (Exception $e) {
            self::$_res['status'] = 1;
            self::$_res['error'] = $e->getMessage();
            return self::$_res;
        }
        
        $width = $width ? $width : $img_info[0];
        $height = $height ? $height : $img_info[1];
        
        switch ($img_info['mime']) {
            case 'image/jpeg':
                $im = imageCreateFromJpeg($img);
                break;
            case 'image/png':
                $im = imageCreateFromPng($img);
                break;
            default:
                break;
        }
        
        $im1 = imagecreatetruecolor($width, $height);
        imagecopyresampled($im1, $im, 0, 0, $start_w, $start_h, $width, $height, $width, $height);
        imagejpeg($im1, $save_path, 100); // 以最高质量进行输出
        
        imagedestroy($im);
        imagedestroy($im1);
        
        return self::$_res;
    }
}
