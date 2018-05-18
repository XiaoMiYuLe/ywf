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

class CategoryController extends CommentAdminAbstract
{
    public function getAllcategory()
    {
        $res = Comment_Model_Category::instance()->fetchAll(NULL, "category_id DESC")->toArray();
    	if($res){
    		foreach($res as &$v){
    			$v['name'] = $v['title'].'( ID:'. $v['category_id'] .' )';
    			$v['id'] = $v['category_id'];
    		}
    		echo @json_encode($res);
    	}
    	
    }
}

// End ^ Native EOL ^ UTF-8