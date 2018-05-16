<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 页面静态化
 * @category Zeed
 * @package Zeed_Benchmark
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-10-31
 * @version SVN: $Id$
 */
class Support_Html 
{

    /**
     * 生成Html文件
     * @param string $template_html
     * @param string $html_name
     * @param array $data
     */
    public function createHtml($template_html, $html_name, $data = array()) {
        require_once ZEED_PATH_VIEW . '/admin/view.init.php';
        foreach ($data as $k => $v) {
            $smarty->assign($k, $v);
        }
        $content = $smarty->fetch($template_html);
        $smarty->MakeHtmlFile($html_name, $content); // 写入内容到news.html文件
    }

}
