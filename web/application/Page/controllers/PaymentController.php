<?php
/**
 * 支付方式
 * 
 * @author     王炼
 * @since      2015-06-08 15:24:52
 */

class PaymentController extends PageAbstract
{
    /**
     * 单页频道 - 支付方式 - 默认首页
     */
    public function index()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(5, 'index');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 支付方式 - 货到付款
     */
    public function payarrive()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(5, 'payarrive');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 支付方式 - 分期付款
     */
    public function paystages()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(5, 'paystages');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 支付方式 - 邮局汇款
     */
    public function payremit()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(5, 'payremit');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 支付方式 - 公司转账
     */
    public function payta()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(5, 'payta');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
}

// End ^ Native EOL ^ UTF-8
