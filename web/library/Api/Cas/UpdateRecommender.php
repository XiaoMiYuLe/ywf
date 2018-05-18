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
 * 编辑联系方式
 */
class Api_Cas_UpdateRecommender
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    protected static $_allowFields = array('userid','recommender');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        	    
        		/* 检查用户是否存在 */
        		if (! $userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid'])) {
        		    throw new Zeed_Exception('该用户不存在');
        		}
        		
        		$userExists = current($userExists);
        		
        		//检查用户是否被冻结
        		if($userExists['status']==1){
        		    throw  new Zeed_Exception('该用户被冻结');
        		}

        		//自己是经纪人，则不可修改推荐人
                if($userExists['is_ecoman']==1){
                    throw  new Zeed_Exception('您已经是经纪人，不能修改推荐人！');
                }else{
                    if($userExists['parent_id']!=3889 && $userExists['is_buy']==1)
                    {
                       throw  new Zeed_Exception('您有过交易记录，不能修改推荐人');
                    }
                }
        		
        		//推荐人是否为空
                if(!empty($res['data']['recommender'])) {
                    $where = "user_code='{$res['data']['recommender']}' or phone='{$res['data']['recommender']}'";
                    
                    $exist_recommender = Cas_Model_User::instance()->fetchByWhere($where);
                    if(empty($exist_recommender)){
                        throw new Zeed_Exception('该推荐人不存在，请检查输入！');
                    }else{
                       if($exist_recommender[0]['userid']==$res['data']['userid']){
                           throw new Zeed_Exception('推荐人不能是自己');
                       }
                    }
                    
                    
                    if($exist_recommender[0]['parent_id'] ==$userExists['userid']){
                        throw new Zeed_Exception('不能将下限指定为推荐人');
                    }
                       
                    $data['parent_id'] = $exist_recommender[0]['userid'];
                    $data['rootId'] = $exist_recommender[0]['rootId'];
                    
                }

                //更新时间
                $data['mtime'] = date('Y-m-d H:i:s');
        		
        		if (! $recommenderupdate = Cas_Model_User::instance()->updateForEntity($data, $res['data']['userid'])) {
        		    throw new Zeed_Exception('修改推荐人失败');
        		}

        		$res['data'] = $data;
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '修改失败。错误信息：' . $e->getMessage();
        		return $res;
        	}
        }
        return $res;
    }
    
    /**
     * 验证参数
     */
    public static function validate($params)
    {
        if (! isset($params['userid']) || ! $params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '用户id未提供';
            return self::$_res;
        }
        
        if (! isset($params['recommender']) || ! $params['recommender']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '推荐人未提供';
            return self::$_res;
        }
        
        /* 组织数据 */
        $set = array();
        foreach (self::$_allowFields as $f) {
            $set[$f] = isset($params[$f]) ? $params[$f] : null;
        }
        self::$_res['data'] = $set;
        
        return self::$_res;
        
    }
}

// End ^ Native EOL ^ encoding
