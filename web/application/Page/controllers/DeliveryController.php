<?php
/**
 * 配送方式
 * 
 * @author     王炼
 * @since      2014-11-13 10:05:26
 */

class DeliveryController extends PageAbstract
{
    /**
     * 单页频道 - 配送方式 - 默认首页
     */
    public function index()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(4, 'index');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 配送方式 - 乐乐的测试
     */
    public function lele()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(4, 'lele');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 配送方式 - 限时达
     */
    public function inreceive()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(4, 'inreceive');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 配送方式 - 配送服务查询
     */
    public function dsservice()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(4, 'dsservice');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 配送方式 - 配送费收取标准
     */
    public function dfguide()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(4, 'dfguide');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
}

// End ^ Native EOL ^ UTF-8
