<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      May 7, 2010
 * @version    SVN: $Id: CaptchaController.php 8811 2010-12-04 03:32:38Z xsharp $
 */

class CaptchaController extends Zeed_Controller_Action
{
    public function index()
    {
        exit('404 Not Found');
    }
    
    public function image()
    {
        $options = array(
          'width' => 100, 
          'height' => 40, 
          'font' => null, 
          'fontColor' => array(0x2197d7), 
          'wordLen' => 4, 
          'wordUseNumbers' => true, 
          'backgroundColor' => 0xFFFFFF
        );
        $capimg = new Zeed_Captcha_Image($options);
//        $capimg->setOption($options);
        $capimg->setParam('font', ZEED_PATH_DATA . 'font/DejaVuSerif.ttf');
        $capimg->setId('');
        $capimg->setWord();
        $capimg->display();
    }
}

// End ^ LF ^ encoding
