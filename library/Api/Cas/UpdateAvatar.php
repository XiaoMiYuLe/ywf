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
 * 更新会员头像（文件上传采用表单上传的方式）
 */
class Api_Cas_UpdateAvatar
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        $url_mapping=Zeed_Config::loadGroup('url_mapping');
        if ($res['status'] == 0) {
        	try {
		        /* 处理头像 */
        	    $files = $_FILES['avatar'];
//                var_dump($files);exit;
        	    if ($files['tmp_name']) {
        	    	if ($files['error'] == UPLOAD_ERR_OK) {

                        $attachment = Trend_Attachment::add($files['tmp_name'],null, $res['data']['userid']);
        	    		$res['data']['avatar'] = $attachment['attachmentid'];
        	    	} else {
        	    		throw new Zeed_Exception('头像上传失败');
        	    	}
        	    } else {
        	        throw new Zeed_Exception('请选择您要上传的头像');
        	    }

		        /* 更改用户信息 */
		        Cas_Model_User::instance()->save($res['data']);
		        
                $res['data']['avatar'] = Support_Image_Url::getImageUrl($url_mapping['store_url']);
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '上传头像。错误信息：' . $e->getMessage();
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
        if (! isset($params['token']) || strlen($params['token']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 token 未提供';
            return self::$_res;
        }

        $params['userid'] = Cas_Token::getUserIdByToken($params['token']);
        unset($params['token']);
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
