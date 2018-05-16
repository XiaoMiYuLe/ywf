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
 * @since      2011-12-30
 * @version    SVN: $Id$
 */

class IndexController extends Zeed_Controller_Action
{
    /**
     * /upload/fc/7f/34/24/7a/25908ecec209e5e3f94783.jpg
     */
    public function index()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $t = explode('?', $uri);
        $file = $t[0];
        $t = explode('/', $file);
        unset($t[0], $t[1]);
        $file = implode('', $t);
        $t = explode('.', $file);
        Zeed_Storage::instance()->getOutput($t[0], $t[1], true);
        exit;
    }
    
    /**
     * CKEditor 编辑器上传
     */
    public function ckupload()
    {
        $funcNum = $this->input->get('CKEditorFuncNum');
        $files = $_FILES['upload'];
        $url = '';
        $message = '上传失败，好像发生一些意外错误呢，请联系管理员看看吧';
        
        if ($files['name']) {
            $files_upload = Support_Attachment::upload($files);
            if ($files['error'] == UPLOAD_ERR_OK) {
                $url = '/upload' . $files_upload['filepath'];
                $message = '上传成功';
            }
        }
        
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '{$url}', '{$message}');</script>";
        exit;
    }
}

// End ^ Native EOL ^ UTF-8