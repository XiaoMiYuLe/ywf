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
class FreeController extends StoreAdminAbstract
{
	public $perpage = 15;
    
    /**
     * 已签约商户列表
     */
   	public function index()
   	{
   		$this->addResult(self::RS_SUCCESS, 'json');
   		 
   		/* 接收参数 */
   		$ordername = $this->input->get('ordername', null);
   		$orderby = $this->input->get('orderby', null);
   		$page = (int) $this->input->get('pageIndex', 0);
   		$perpage = $this->input->get('pageSize', $this->perpage);
   		$key = trim($this->input->get('key'));
   		$status = (int) $this->input->get('status');
   		$is_verify = (int) $this->input->get('is_verify');
   		 
   		/* ajax 加载数据 */
   		if ($this->input->isAJAX()) {
   			$offset = $page * $perpage;
   			$page = $page + 1;
   				
   			$where = " is_signing = 0  "; // 只显示未签约商户
   	
   				
   				
   			if ($status != '-100')
   			{
   				$where .= " AND status = {$status}";
   			}
   				
   				
   			if ($is_verify != '-100'){
   				$where .= " AND is_verify = {$is_verify}";
   			}
   	
   			if ($key) {
   				$where .= " AND store_name LIKE '%{$key}%' ";
   			}
   				
   	
   			$order = 'store_id ASC';
   			if ($ordername) {
   				$order = $ordername . " " . $orderby;
   			}

   				
   			$contents = Store_Model_Content::instance()->fetchByWhere($where, $order, $perpage, $offset);
   			
   			
   			$data['count'] = Store_Model_Content::instance()->getCount($where);
   			$data['contents'] = $contents ? $contents : array();
   		}
   		 
   		$data['ordername'] = $ordername;
   		$data['orderby'] = $orderby;
   		$data['page'] = $page;
   		$data['perpage'] = $perpage;
   		 
   		$this->setData('data', $data);
   		$this->addResult(self::RS_SUCCESS, 'php', 'free.index');
   		return parent::multipleResult(self::RS_SUCCESS);
   	}
   	
}