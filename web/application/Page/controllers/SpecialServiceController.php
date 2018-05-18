<?php
/**
 * 特色服务
 * 
 * @author     王炼
 * @since      2015-06-08 15:26:42
 */

class SpecialServiceController extends PageAbstract
{
    /**
     * 单页频道 - 特色服务 - 默认首页
     */
    public function index()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(7, 'index');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 单页频道 - 特色服务 - 节能补贴
     */
    public function essubsidies()
    {
        $data['page'] = Page_Model_Listing::instance()->getPage(7, 'essubsidies');
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
}

// End ^ Native EOL ^ UTF-8