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
class RegionController extends TrendAbstract
{
    /**
     * 根据父级 ID 获取子级地区
     */
    public function getRegionByPid ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $pid = $this->input->query('pid', 0);
        
        $data['regions'] = Trend_Model_Region::instance()->fetchByFV('pid', $pid);
        
        $this->setData('data', $data);
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8