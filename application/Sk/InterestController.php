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

/**
 *结算利息，本金
 * @author Administrator
 *
 */
class InterestController extends IndexAbstract 
{
	/**
     * 默认返回数据
     */
	protected static $_res = array('status' => 0, 'msg' => '', 'data' => '');
	  
    public  function index($params = null)
    {
    	$set = Support_Reapal_pay_Interest::run();
    	
    	$r['news_title'] = 'cesss';
    	$r['news_content'] = '计算利息成功';
    	$r['ctime'] = date(DATETIME_FORMAT);
    	News_Model_List::instance()->insert($r);
    	echo sucess;
    	var_dump($res);
	}
	
}