/**
 * JS 验证器
 */
var CASValidator = {};

// 用户名
CASValidator.username = function($username) {
	if ($username.length < 6 || $username.length > 30) {
		return false;
	} else {
		var regx = /^[a-z0-9_]{6,20}$/i;
		if (! regx.test($username)) {
			return false;
		}
		return true;
	}
};

// 邮箱
CASValidator.email = function($email) {
	var regx = /^[-._A-Za-z0-9]+@([-._A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/;
	if (! regx.test($email)) {
		return false;
	}
	return true;
};

// 密码
CASValidator.password = function($password) {
	if ($password.length < 6 || $password.length > 14) {
		return false;
	} else {
		var regx = /^[a-z0-9\_]{6,14}$/i;
		if (! regx.test($password)) {
			return false;
		}
		return true;
	}
};
