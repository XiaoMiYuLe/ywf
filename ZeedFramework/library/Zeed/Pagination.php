<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      Sep 26, 2010
 * @version    SVN: $Id: Pagination.php 7427 2010-09-27 07:33:47Z xsharp $
 */

/**
 * 本分页类仅用于展示分页样式, 不获取数据
 */
class Zeed_Pagination
{
    
    /**
     * 设置每页行数
     * 
     * @param integer $perpage
     * @return Zeed_Pagination
     */
    public function setCountPerPage($perpage)
    {
    }
    
    /**
     * 设置显示的页面数
     * 
     * @param integer $pageRange
     * @return setPageRange
     */
    public function setPageRange($pageRange)
    {
    }
    
    public function getPageRange()
    {
    }
    
    
    /**
     * @return integer
     */
    public function getTotalCount()
    {
    }
    
    /**
     * 设置结果总数. 注意与setTotalPageNumber()区分
     * 
     * @param integer $totalCount
     * @return Zeed_Pagination
     */
    public function setTotalCount($totalCount)
    {
    }
    
    /**
     * 获取总页码
     * 
     * @return integer
     */
    public function getTotalPageNumber()
    {
    }
    
    /**
     * 设置总页码, 注意与setTotalCount()区分
     * 
     * @param integer $totalPageNumber
     * @return Zeed_Pagination
     */
    public function setTotalPageNumber($totalPageNumber)
    {
    }
    
    /**
     * @return Zeed_Pagination
     */
    public function getCurrentPageNumber()
    {
    }
    
    /**
     * 
     * @param integer $currentPageNumber
     * @param Zeed_Pagination
     */
    public function setCurrentPageNumber($currentPageNumber)
    {
    }
    
    public function setStyle(Zeed_Pagination_Style $style)
    {
    }
    
    public function render()
    {
    }

}

// End ^ Native EOL ^ encoding
