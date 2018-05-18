<?php
/**
 * 购物指南
 * 
 * @author     王炼
 * @since      2014-11-27 17:10:22
 */

class ShoppingGuideController extends PageAbstract
{
    /**
     * 单页频道 - 购物指南 - 默认首页
     */
    public function index()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(3, 'index');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 购物指南 - 会员介绍
     */
    public function cas()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(3, 'cas');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 购物指南 - 团购抢购
     */
    public function tuan()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(3, 'tuan');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 购物指南 - 常见问题
     */
    public function faq()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(3, 'faq');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 购物指南 - 联系客服
     */
    public function service()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(3, 'service');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
}

// End ^ Native EOL ^ UTF-8
