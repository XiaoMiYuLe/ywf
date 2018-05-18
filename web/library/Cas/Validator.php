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
 * @since      Jun 21, 2010
 * @version    SVN: $Id$
 */

/**
 * 统一的验证
 */
class Cas_Validator
{
    /**
     * 用户名是否合法
     *
     * @param string $username
     */
    public static function username($username)
    {
        if (! preg_match("/^[a-z][0-9a-z_]{5,19}$/i", $username)) {
            return false;
        }
        
        $blacklist = Zeed_Config::loadGroup('blacklist');
        if (isset($blacklist['eq']) && in_array(strtolower($username), $blacklist['eq'])) {
            return false;
        }
        
        if (isset($blacklist['inc']) && is_array($blacklist['inc'])) {
            foreach ($blacklist['inc'] as $black) {
                if (stripos($username, $black) !== FALSE) {
                    return false;
                }
            }
        }
        
        if (isset($blacklist['regex']) && is_array($blacklist['regex'])) {
            foreach ($blacklist['regex'] as $black) {
                if (@preg_match($black, $username) > 0) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * 昵称是否合法
     * @param string $nickname
     * @return true|array 如果失败返回 array 错误信息
     */
    public static function nickname($nickname)
    {
        /**
         * 2至20个字符长度，可以使用中文、英文、数字、及符号_`^~
         */
        $regex = new Zend_Validate_Regex('#^[a-zA-Z0-9_\`\^\~\x{30A0}-\x{30FF}\x{3040}-\x{309F}\x{4E00}-\x{9FBF}]{2,20}$#u');
        $regex->setMessage("您输入的'%value%'昵称，不符合要求，请检查规则后再试。", Zend_Validate_Regex::NOT_MATCH);
        
        if (! $regex->isValid($nickname)) {
            return $regex->getMessages();
        }
        
        $blacklist = Zeed_Config::loadGroup('blacklist');
        if (isset($blacklist['ext_eq']) && is_array($blacklist['ext_eq'])) {
            if (isset($blacklist['eq']) && is_array($blacklist['eq'])) {
                $blacklist['ext_eq'] = array_merge($blacklist['ext_eq'], $blacklist['eq']);
            }
            if (in_array(strtolower($nickname), $blacklist['ext_eq'])) {
                return array('nickname' => "昵称包含敏感词, 换一个吧 :(");
            }
        }
        
        if (isset($blacklist['ext_inc']) && is_array($blacklist['ext_inc'])) {
            if (isset($blacklist['inc']) && is_array($blacklist['inc'])) {
                $blacklist['ext_inc'] = array_merge($blacklist['ext_inc'], $blacklist['inc']);
            }
            foreach ($blacklist['ext_inc'] as $black) {
                if (stripos(strtolower($nickname), $black) !== FALSE) {
                    return array('nickname' => "昵称包含敏感词, 换一个吧 :(");
                }
            }
        }
        
        if (isset($blacklist['ext_regex']) && is_array($blacklist['ext_regex'])) {
            if (isset($blacklist['regex']) && is_array($blacklist['regex'])) {
                $blacklist['ext_regex'] = array_merge($blacklist['ext_regex'], $blacklist['regex']);
            }
            foreach ($blacklist['ext_regex'] as $black) {
                if (@preg_match($black, $nickname) > 0) {
                    return array('nickname' => "昵称包含敏感词, 换一个吧 :(");
                }
            }
        }
        
        return true;
    }
    
    public static function phone($phone)
    {
        /**
         * 手机应该只有 数字、空格、-
         */
        if (empty($phone)) {
            return true;
        }
        
        $pattern = "/^1[34578][0-9]{1}[0-9]{8}$/";
        $regex = new Zend_Validate_Regex($pattern);
        $regex->setMessage("您输入的'%value%'手机，系统无法识别，请检查规则后再试。", Zend_Validate_Regex::NOT_MATCH);
        
        if (! $regex->isValid($phone)) {
            return $regex->getMessages();
        }
        
        return true;
    }
    
    public static function gender($gender)
    {
        $gender = (int) $gender;
        
        switch ($gender) {
            case 0 :
            case 1 :
            case 2 :
                return true;
			default:
				break;	
        }
        
        return array("您选择的性别，系统无法识别，请检查后再试。");
    }
    
    public static function address()
    {
        return true;
    }
    
    /**
     * 是否合法的登录用户名
     *
     * @param string $username
     */
    public static function usernam4signin($username)
    {
        return preg_match("/^[0-9a-z_@\-\.]{4,40}$/i", $username);
    }
    
    /**
     * 验证密码是否合法
     *
     * @param string $password
     * @param string $passwordConfirm
     * @param array $userInfo
     * @return true|array 如果失败返回 array 错误信息
     */
    public static function password($password, $passwordConfirm = null, $userInfo = null)
    {
		$result = array();
		
        // ERROR CODE 10055
        $empty = new Zend_Validate_NotEmpty();
        $empty->setMessage("为了您的账号安全，密码不能设置为空。", Zend_Validate_NotEmpty::IS_EMPTY);
        
        if (! $empty->isValid($password)) {
            return $empty->getMessages();
        }
        
		try {
			if ($passwordConfirm && strcmp($password, $passwordConfirm) !== 0) {
			    $result = array('您输入的确认密码与密码不一致，请重新输入');
				throw new Zeed_Exception('您输入的确认密码与密码不一致，请重新输入');
			}
			
			@$strlen = new Zend_Validate_StringLength(array(
					'min' => 6, 
					'max' => 14, 
					'encoding' => 'UTF-8'));
			$strlen->setMessage("为了您的账号安全，您的密码不能太短。", Zend_Validate_StringLength::TOO_SHORT);
			$strlen->setMessage("您输入的密码，太长了，不符合要求，请重新输入。", Zend_Validate_StringLength::TOO_LONG);
			
			if (! $strlen->isValid($password)) {
				return $strlen->getMessages();
			}
			
			/**
			 * 密码可以使用以下字符：a-z、A-Z、0-9
			 */
			$regex = new Zend_Validate_Regex('#^[a-zA-Z0-9\_]+$#ui');
			$regex->setMessage("您输入的'%value%'密码，不符合要求存在非法字符，请检查规则后再试。", Zend_Validate_Regex::NOT_MATCH);
			
			if (! $regex->isValid($password)) {
				return $regex->getMessages();
			}
			
			/**
			 * 检查用户信息
			 */
			if (null !== $userInfo && is_array($userInfo)) {
				
				$encodeMode = $userInfo['encrypt'];
				$passwordSaltWord = $userInfo['salt'];
				$encodePassword = Zeed_Encrypt::encode($encodeMode, $password, $passwordSaltWord);
				
				if (strcmp($userInfo['username'], $password) == 0) {
				    $result = array(10053 => '为了您的账号安全，密码不能与账号相同');
					throw new Zeed_Exception('为了您的账号安全，密码不能与账号相同');
				}
				else if (!empty($userInfo['freeze']) && strcmp($encodePassword, $userInfo['freeze']) == 0) {
				    $result = array(10056 => '为了您的账号安全，密码不能与安全锁密码相同');
					throw new Zeed_Exception('为了您的账号安全，密码不能与安全锁密码相同');
				}
				else if (strcmp($encodePassword, $userInfo['password']) == 0) {
				    $result =  array(10056 => '新密码不能与旧密码相同');
					throw new Zeed_Exception('新密码不能与旧密码相同');
				}
			}
		} catch (Zeed_Exception $e) {
                return $result;
            }
       
        return true;
    }
    
    /**
     * 密码强度
     *
     * @param string $password
     * @param boolean $returnDesc 是否返回字符描述, 如果是, 返回"较弱,强"等, 如果否, 返回0到6的数字描述
     */
    public static function passwordStrength($ipassword, $returnDesc = true)
    {
        $securitySettings = Zeed_Config::loadGroup('security.userpassword');
        $password = Zeed_Encrypt::decode($securitySettings['algorithm'], $ipassword, $securitySettings['salt']);
        $desc = array();
        $desc[0] = "形同虚设";
        $desc[1] = "弱";
        $desc[2] = "较弱";
        $desc[3] = "中";
        $desc[4] = "强";
        $desc[5] = "非常强";
        $score = 0;
        if (preg_match("/[A-Z]/", $password)){$score ++;}
            
        if (preg_match("/[a-z]/", $password)){$score ++;}
            
        if (preg_match("/[0-9]/", $password)){$score ++;}
            
        if (preg_match("/.[_]/", $password)){$score ++;}
           
        if (strlen($password) > 12){$score ++;}
 
        if ($returnDesc) {
            return $desc[$score];
        }
        return $score;
    }
    
    /**
     * 是否合法的EMAIL地址
     *
     * @param string $email
     */
    public static function email($email)
    {
        return Zend_Validate::is($email, 'EmailAddress');
    }
    
    /**
     * 是否合法的真实姓名
     *
     * @param string $name
     * @return true|array
     */
    public static function realname($name)
    {
        if (strlen($name) < 2 || strlen($name) > 30) {
            return array('真实姓名不正确');
        }
        
        return true;
    }
    
    /**
     * 身份证号是否防沉迷
     * 
     * @param string $idcard
     */
    public static function idcardProtected($idcard, $checkLegality = true)
    {
        if (! $checkLegality || self::idcard($idcard) && ((strlen($idcard) == 15 && ('19' . substr($idcard, 6, 6)) < date('Ymd', time() - 567993600)) || (strlen($idcard) == 18 && substr($idcard, 6, 8) < date('Ymd', time() - 567993600)))) {
                return false;
            }
        return true;
    }
    
    /**
     * 是否合法的身份证号码
     *
     * @param string $sid
     * @return true|array
     */
    public static function idcard($sid)
    {
		$result = array();
		try {
			if (strlen($sid) != 18 && strlen($sid) != 15) {
			    $result = array("身份证号码验证错误，请检查身份证长度");
				throw new Zeed_Exception("身份证号码验证错误，请检查身份证长度");
			}
			
			$area = array(
					11 => "北京", 
					12 => "天津", 
					13 => "河北", 
					14 => "山西", 
					15 => "内蒙古", 
					21 => "辽宁", 
					22 => "吉林", 
					23 => "黑龙江", 
					31 => "上海", 
					32 => "江苏", 
					33 => "浙江", 
					34 => "安徽", 
					35 => "福建", 
					36 => "江西", 
					37 => "山东", 
					41 => "河南", 
					42 => "湖北", 
					43 => "湖南", 
					44 => "广东", 
					45 => "广西", 
					46 => "海南", 
					50 => "重庆", 
					51 => "四川", 
					52 => "贵州", 
					53 => "云南", 
					54 => "西藏", 
					61 => "陕西", 
					62 => "甘肃", 
					63 => "青海", 
					64 => "宁夏", 
					65 => "新疆", 
					71 => "台湾", 
					81 => "香港", 
					82 => "澳门", 
					91 => "国外");
			
			$areaid = (int) substr($sid, 0, 2);
			if (! isset($area[$areaid])) {
			    $result = array("身份证号码验证错误");
				throw new Zeed_Exception("身份证号码验证错误");
			}
			
			if (strlen($sid) == 15) {
				$year = intval(substr($sid, 6, 2)) + 1900;
				if ($year >= 1984) {
				    $result = array("身份证号码验证错误");
					throw new Zeed_Exception("身份证号码验证错误");
				}
				$ok = FALSE;
				if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) { //闰年
					$ok = preg_match("/^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/", $sid);
				} else {
					$ok = preg_match("/^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/", $sid);
				}
				
				if ($ok !== FALSE) {
					return true;
				}
				
				$result = array("身份证号码验证错误");
				throw new Zeed_Exception("身份证号码验证错误");
			}
			
			$year = intval(substr($sid, 6, 4));
			$ok = FALSE;
			if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) { //闰年
				$ok = preg_match("/^[1-9][0-9]{5}(19|20)[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/", $sid);
			} else {
				$ok = preg_match("/^[1-9][0-9]{5}(19|20)[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/", $sid);
			}
			
			if ($ok === FALSE) {
			    $result = array("身份证号码验证错误");
				throw new Zeed_Exception("身份证号码验证错误");
			}
			
			$yjy = strtoupper(substr($sid, 17, 1));
			$sid = substr($sid, 0, 17);
			$jiaquan = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
			$jiaoyan = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
			
			$sigma = 0;
			for ($i = 0; $i < 17; $i ++) {
				$sigma += ((int) $sid{$i}) * $jiaquan[$i];
			}
			$jy = $jiaoyan[($sigma % 11)];
			if ($yjy == $jy) {
				return true;
			}
		} catch (Zeed_Exception $e) {
			return $result;
		}
		
		return array("身份证号码验证错误");
    }
    
    /**
     * 验证安全问题以及机密答案
     *
     * @param string $qa
     * @return true|array 如果失败返回 array 错误信息
     */
    public static function securityQA($qa)
    {
        /**
         * 安全问题及机密答案必须是1～30个字符，由且仅由中文、下划线、半角英文（区分大小写）或半角数字0～9组成，一个汉字占1个字符。
         */
        $regex = new Zend_Validate_Regex('#^[a-zA-Z0-9_\x{30A0}-\x{30FF}\x{3040}-\x{309F}\x{4E00}-\x{9FBF}]{1,30}$#u');
        $regex->setMessage("您输入的'%value%'安全问题或机密答案，不符合要求，请检查规则后再试。", Zend_Validate_Regex::NOT_MATCH);
        
        if (! $regex->isValid($qa)) {
            return $regex->getMessages();
        }
        
        return true;
    }
    
    /**
     * 验证邮政编码
     *
     * @param string $qa
     * @return true|array 如果失败返回 array 错误信息
     */
    public static function zipcode($code)
    {
        /**
         * 安全问题及机密答案必须是1～30个字符，由且仅由中文、半角英文（区分大小写）或半角数字0～9组成，一个汉字占1个字符。
         */
        if (empty($code)) {
            return true;
        }
        $regex = new Zend_Validate_Regex('#^[0-9]{6,6}$#');
        $regex->setMessage("您输入的'%value%'邮政编码，不符合要求，请检查规则后再试。", Zend_Validate_Regex::NOT_MATCH);
        
        if (! $regex->isValid($code)) {
            return $regex->getMessages();
        }
        
        return true;
    }
}


// End ^ Native EOL ^ encoding
