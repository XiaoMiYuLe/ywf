<?php
/**
 * 售后服务
 * 
 * @author     王炼
 * @since      2015-06-08 15:25:56
 */

class AfterServiceController extends PageAbstract
{
    /**
     * 单页频道 - 售后服务 - 默认首页
     */
    public function index()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(6, 'index');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 售后服务 - 价格保护
     */
    public function priceprotect()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(6, 'priceprotect');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 售后服务 - 退款说明
     */
    public function refundexplain()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(6, 'refundexplain');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 售后服务 - 返修/退换货
     */
    public function extraservice()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(6, 'extraservice');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 售后服务 - 取消订单
     */
    public function cancelorder()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(6, 'cancelorder');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
}

// End ^ Native EOL ^ UTF-8