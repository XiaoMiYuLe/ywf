$(document).ready(function() {
	/* 登陆框层定位 */
	shake('aui_iwrapper', false);
	
	/**
	 * 提交处理
	 */
	$("#sign_submit").click(function(){
		if (! $("input[name=username]").val()) {
			$('.notice-username').html('请输入用户名');
			shake('aui_iwrapper', true);
			return false;
		}
		if ($("input[name=username]").val().length < 3 || $("input[name=username]").val().length > 20) {
			$('.notice-username').html('用户名长度为3~20个字符');
			shake('aui_iwrapper', true);
			return false;
		}
		$('.notice-username').html('');
		
		if (! $("input[name=password]").val()) {
			$('.notice-password').html('请输入密码');
			shake('aui_iwrapper', true);
			return false;
		}
		if ($("input[name=password]").val().length < 6 || $("input[name=password]").val().length > 20) {
			$('.notice-password').html('密码长度为6~20个字符');
			shake('aui_iwrapper', true);
			return false;
		}
		$('.notice-password').html('');
		
		if (! $("input[name=captcha]").val()) {
			$('.notice-captcha').html('请输入验证码');
			shake('aui_iwrapper', true);
			return false;
		}
		if ($("input[name=captcha]").val().length != 6) {
			$('.notice-captcha').html('验证码长度为6位');
			shake('aui_iwrapper', true);
			return false;
		}
		$('.notice-captcha').html('');
		
		var saveCallBack = sign_submited;
		var options = {
	            dataType:'json',
	            timeout:60000,
	            success:saveCallBack,
	            error:ajaxError
	    };
	    $("#form_sign").ajaxSubmit(options);
	    return false;
	});
});

/**
 * 登陆成功，返回处理
 */
function sign_submited(data, textStatus) {
    if(data.status === 0) {
    	window.location = data.data;
    } else {
    	$('.notice-back').html(data.error);
    	$('#vcodeimg').click();
    	shake('aui_iwrapper', true);
    }
}

/**
 * 指定层左右摇动特效
 * 
 * @param string obj_id 指定层ID
 */
function shake(obj_id, shake) {
    var $obj = $("#" + obj_id);
    var box_left = ($(window).width() - $obj.width()) / 2;
    $obj.css({'left': box_left, 'position':'absolute'});
    if (shake == true) {
	    for (var i = 1; i <= 4; i++) {
	    	$obj.animate({left:box_left - (40 - 10 * i)}, 50);
	    	$obj.animate({left:box_left + 2 * (40 - 10 * i)}, 50);
	    }
    }
}

/**
 * ajaxError
 */
function ajaxError(XMLHttpRequest, textStatus, errorThrown) {
    alert('Ooops!Encountered error while connecting to the server.There might be something wrong with your network.Please check your network connection!');
}