<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
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


/**
 * Thumb Url: /upload/thumb/ab/cd/defg_AM.jpg
 * 
 * @author ahdong
 *
 */
class ThumbController extends AdminAbstract
{
    protected $_suffix = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
    
    public function index()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $t = explode('?', $uri);
        $file = $t[0];
        $t = explode('_', $file);
        $file = $t[0];
        $thumb = $t[1];
        $thumb = explode('.', $thumb);
        $thumbSize = $thumb[0]; // 缩略图配置大小
        $suffix = $thumb[1]; // 文件扩展名

        
        if ($thumbSize == '' || $suffix == '' || ! in_array($suffix, $this->_suffix)) {
            header("Status: 404 Not Found");
            exit();
        }
        
        $t = explode('/', $file);
        unset($t[0], $t[1], $t[2]);
        $fileid = implode('', $t);
        
        if ($fileid == '') {
            header("Status: 404 Not Found");
            exit();
        }
        
        $thumbConfig = Zeed_Config::loadGroup('thumbsize.' . $thumbSize);
        if (empty($thumbConfig)) {
            header("Status: 404 Not Found");
            exit();
        }
        
        $exists = Zeed_Storage::instance()->getOutput($fileid . '_' . $thumbSize, $suffix, true);
        if ($exists) {
            exit();
        }
        
        $this->_generateThumb($fileid, $suffix, $thumbSize, $thumbConfig);
        exit();
    }
    
    protected function _generateThumb($id, $suffix, $thumbSize, $thumbConfig)
    {
        $file = Zeed_Storage::instance()->get($id, $suffix);
        if ($file === NULL) {
            header("Status: 404 Not Found");
            exit();
        }
        $time = microtime(true);
        $rand = rand(1000, 9999);
        $tmpSrc = ZEED_PATH_DATA . 'tmp/thumb_' . $time . $rand . '_' . $id . '.' . $suffix;
        $tmpDst = ZEED_PATH_DATA . 'tmp/thumb_' . $time . $rand . '_' . $id . '_' . $thumbSize . '.' . $suffix;
        $tmpWrite = @file_put_contents($tmpSrc, $file);
        if ($tmpWrite === false) {
            exit('ERROR WHILE SWAPPING');
        }
        
        try {
             $this->_doGenerateThumb($tmpSrc, $tmpDst, $thumbSize, $thumbConfig);
            if (Zeed_Storage::instance()->putFile($tmpDst, $suffix, $id . '_' . $thumbSize)){
                Zeed_Storage::instance()->getOutput($id . '_' . $thumbSize, $suffix, true);
            }
            @unlink($tmpSrc);
            @unlink($tmpDst);
        } catch (Exception $e) {
            header("Status: 404 Not Found");
            echo $e->getMessage();
            exit('ERROR WHILE THUMBBING');
            
        }
        exit;
    }
    
    protected function _doGenerateThumb($originalImage, $cachedImage, $thumbSize, $thumbConfig)
    {
        $WxH = explode('x', $thumbConfig['size']);
        $width = $WxH[0];
        $height = $WxH[1];
        $config = array();
        $config['source_image'] = $originalImage;
        $config['new_image'] = $cachedImage;
        $config['dynamic_output'] = false;
        $config['create_thumb'] = true;
        $config['thumb_marker'] = '';
        $config['maintain_ratio'] = true; // 是否保持原来的比例
        $config['width'] = $width;
        $config['height'] = $height;
        
        $oinfo = getimagesize($originalImage);
        if (! $thumbConfig['zoomin'] && $oinfo[0] < $width && $oinfo[1] < $height) {
            //如果图片较小并且不允许放大，拷贝原图
            if (! @copy($originalImage, $cachedImage)) {
                throw new Zeed_Exception('copy failed');
            }
        } else {
            //生成缩略图
            $uImage = new Util_Image($config);
            $fun = $thumbConfig['type'];
            if (! $uImage->$fun()) {
                throw new Zeed_Exception($uImage->display_errors());
            }
        }
        
        if ($thumbConfig['water']) {
            $winfo = getimagesize($thumbConfig['water']);
            $issmall = $winfo === false || $oinfo[0] < $winfo[0] || $oinfo[1] < $winfo[1] || $width < $winfo[0] || $height < $winfo[1];
            if ($issmall) {
                //如果图片较小，不加水印
            } else {
                //加水印
                $position = explode(',', $thumbConfig['wposition']);
                $config['source_image'] = $cachedImage;
                $uImage = new Util_Image($config);
                $uImage->wm_overlay_path = $thumbConfig['water'];
                $uImage->wm_type = 'overlay';
                $uImage->wm_vrt_alignment = $position[0];
                $uImage->wm_hor_alignment = $position[1];
                $uImage->wm_use_truetype = true;
                if (! $uImage->watermark()) {
                    throw new Zeed_Exception($uImage->display_errors());
                }
            }
        }
        
        return true;
    }
}

?>