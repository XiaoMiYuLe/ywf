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
 *
 * @author Administrator
 *
 */
class UserController extends IndexAbstract 
{
    public  function adduserzjcf($params = null)
    {
        $user = Sk_Model_Member::instance()->fetchByWhere("1=1");
        if(!empty($user)){
            foreach ($user as $k=>&$v){
                $user_detail = Sk_Model_Member_Ext::instance()->fetchByWhere("uid ='{$v['id']}'");
                //数据
                $data['userid'] = $v['id'];
                $data['phone'] = $v['username'];
                $data['password'] = $v['password'];
                $data['parent_id'] = $user_detail[0]['parent_id'];
                $id = Cas_Signup::run($data);
            }
        }
       
    }
	
}




























