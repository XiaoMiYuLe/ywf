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
 * 上传照片
 */
class Api_Cas_AddAvatar
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
		        /* 处理头像 */
        	    if(isset($_FILES['avatar'])){
        	        $attachment = Support_Attachment::upload($_FILES['avatar']);
        	        if(!empty($attachment['filepath'])){
        	            $img_url = $attachment['filepath'];
        	        }else{
        	           throw new Zeed_Exception('照片上传失败');
        	        }
        	    }else{
        	        throw new Zeed_Exception('请上传照片');
        	    }

        	    /* 更新照片信息 */
		        $result = Cas_Model_User::instance()->update(array('avatar'=> $img_url,'is_invitaiton'=>2,'ctime'=>date("Y:m:d H:i:s")),"userid = {$res['data']['userid']}");
		        
		        if(empty($result)){
		            throw new Zeed_Exception('上传失败');
		        }
		        
                $res['data']['avatar'] = Support_Image_Url::getImageUrl($img_url);
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
        if (! isset($params['userid']) || strlen($params['userid']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '用户id 未提供';
            return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
