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
 * @since 2010-12-6
 * @version SVN: $Id$
 */

class TestController extends IndexAbstract
{
    /**
     * 
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $arr = array();
        if ($this->input->isAJAX()) {
            $p = (int) $this->input->post('p');
            $psize = (int) $this->input->post('psize');
            $arr = array(
                'p'=>$p,
                'psize'=>$psize,
            );
            $result = Api_Goods_GetGoodsList::run($arr);
            return $result;
        }
     
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'test.index');
        return parent::multipleResult(self::RS_SUCCESS);
        
    }
    public function test()
    {
        $arr = array();
        if ($this->input->isAJAX()) {
            $p = (int) $this->input->post('p');
            $psize = (int) $this->input->post('psize');
            $arr = array(
                'p'=>$p,
                'psize'=>$psize,
            );
        }
        $result = Api_Goods_GetGoodsList::run($arr);
        var_dump($result);die;
        return $result;
    }
    
}

// End ^ Native EOL ^ UTF-8























