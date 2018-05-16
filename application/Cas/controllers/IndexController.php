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
class IndexController extends CasAbstract
{
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        $userid = Cas_Authorization::getLoggedInUserid();

        $userinfo = Cas_Model_User::instance()->getUserByUserid($userid);

        $where['user_lv_id'] = (int)$userinfo['user_lv_id'];
        	
        $data['user_info'] = $userinfo;
        $data['userdetail'] = $userdetail[0];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

}

// End ^ LF ^ encoding
