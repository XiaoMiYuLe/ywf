<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
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

class IndexController extends ArticleAbstract
{
    /**
     * 商品前台首页
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    public function test()
    {
        $categories = Goods_Model_Category::instance()->getAllCategories();
        
        Zeed_Benchmark::print_r($categories);
        exit;
        
        echo 'product frontend interface test';
        exit;
    }
}

// End ^ Native EOL ^ UTF-8