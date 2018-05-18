<?php
/**
 * 联系我们
 * 
 * @author     王炼
 * @since      2015-06-08 14:23:35
 */

class ContactController extends PageAbstract
{
    /**
     * 单页频道 - 联系我们 - 默认首页
     */
    public function index()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(2, 'index');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 联系我们 - 北京分公司
     */
    public function beijing()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(2, 'beijing');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 联系我们 - 武汉分公司
     */
    public function wuhan()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(2, 'wuhan');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 联系我们 - 西安分公司
     */
    public function xian()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(2, 'xian');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 联系我们 - 沈阳分公司
     */
    public function shenyang()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(2, 'shenyang');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 联系我们 - 深圳分公司
     */
    public function shenzhen()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(2, 'shenzhen');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 联系我们 - 欧洲分公司
     */
    public function europe()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(2, 'europe');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
}

// End ^ Native EOL ^ UTF-8
