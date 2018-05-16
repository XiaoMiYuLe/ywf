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
 * 在线反馈接口
 *
 * 用户在线反馈接口，可用于匿名和非匿名用户提供在线反馈功能
 */
class Api_Feedback_Add
{

    protected static $_res = array(
            'status' => 0,
            'error' => '',
            'data' => ''
    );

    /**
     * 接口运行方法
     *
     * @param string $params            
     * @throws Zeed_Exception
     * @return Ambigous <string, multitype:number, multitype:number string >
     */
    public static function run ($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                
                /* 检查用户是否存在 */
                if (! Cas_Model_User::instance()->fetchByPK($res['data']['userid'])) {
                    throw new Zeed_Exception('用户不存在');
                }
                
                /* 添加一条数据 */
                $res['data']['ctime'] = date(DATETIME_FORMAT);
                Feedback_Model_Content::instance()->addForEntity($res['data']);
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '登录失败。错误信息：' . $e->getMessage();
            }
        }
        return $res;
    }

    /**
     * 参数校验
     *
     * @param array $params            
     * @return multitype:number string
     */
    protected static function validate ($params)
    {
        try {
            
            /* 如果传入token,判断是否安装cas会员模块 */
            if (isset($params['token']) && $params['token']) {
                $casApp = Admin_Model_App::instance()->fetchByPK('cas');
                if (! $casApp) {
                    throw new Zeed_Exception('未安装会员模块');
                }
                
                /* 用token获取userid */
                $userid = Cas_Token::getUserIdByToken($params['token']);
                if (! $userid) {
                    throw new Zeed_Exception('用户不存在，请确认token是否正确');
                }
                $params['userid'] = $userid;
                unset($params['token']);
            }
            
            /* 反馈内容验证 */
            if (! isset($params['body']) || ! $params['body']) {
                throw new Zeed_Exception('content 未提供');
            }
            
        } catch (Exception $e) {
            self::$_res['status'] = 1;
            self::$_res['error'] = $e->getMessage();
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
