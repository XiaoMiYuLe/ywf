$(document).ready(function() {
	/**
	 * 预处理日期选择控件
	 */
	$('.datepicker-input').datepicker();
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_type = $(this).attr("data_submit_type");
		switch (submit_type) {
			case 'submit_cancel' : form_cancel(); break;
			case 'submit_save_back' : back_listing = true; form_submit(); break;
			case 'submit_save_continue' : back_listing = false; form_submit(); break;
		}
	});
});


/**
 * 取消处理
 */
function form_cancel() {
	history.go(-1);
}

/**
 * 表单提交处理
 */
function form_submit() {
	notice('edit_notice', img_loading_small, false);
	
	var userid = $("#userid").val();
	
	/* 校验重置密码 */
	if ($('#password').val()) {
		if ($('#password').val() == $('#username_edit').val()) {
			notice('edit_notice', img_delete + ' 密码不能和账号相同', true, 5000);
			return false;
		}
		if ($('#password').val().length < 6 || $('#password').val().length > 14) {
			notice('edit_notice', img_delete + ' 密码长度至少6位，最多不超过14位', true, 5000);
			return false;
		}
		if (! CASValidator.password($('#password').val())) {
			notice('edit_notice', img_delete + ' 密码中仅能使用以下字符：a-z、A-Z、0-9 以及下划线“_”', true, 5000);
			return false;
		}
	}
	
	/* 若填写了重置密码，则需提示是否确认要重置密码 */
	if ($('#password').val() && ! confirm('本次更新将连同密码一并更新，确定要继续吗？')) {
		notice('edit_notice', '', false);
		return false;
	}
	
	$(".input-submit").attr('disabled', true);
	
	var saveCallBack;
	if (userid == '' || userid == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/casadmin/index/edit");
		saveCallBack = form_save_edited;
	}
	
	var options = {
            dataType:'json',
            timeout:60000,
            success:saveCallBack,
            error:ajaxError
    };
    $("#edit_form").ajaxSubmit(options);
    return false;
}

/**
 * 添加成功，返回处理(暂不使用)
 */
function form_save_added(data, textStatus) {
    if(data.status === 0) {
        notice('edit_notice', img_done + ' 添加成功!', true, 5000);
        
        // 判断是否返回列表管理
        if (back_listing == true) {
        	history.back(-1);
        }
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}

/**
 * 编辑成功，返回处理
 */
function form_save_edited(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 编辑成功!', true, 5000);
        history.back(-1);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}
