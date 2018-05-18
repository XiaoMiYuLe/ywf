$(document).ready(function() {
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_cancel' : user_cancel(); break;
			case 'submit_save_back' : back_listing = true; user_submit(); break;
			case 'submit_save_continue' : back_listing = false; user_submit(); break;
		}
	});
});


/**
 * 取消处理
 */
function user_cancel() {
	history.go(-1);
}

/**
 * 用户表单提交处理
 */
function user_submit() {
	notice('edit_notice', img_loading_small, false);
	
	var userid = $("input[name=userid]:last").val();
	
	if (! userid && (! $("input[name=username]").val() || ! $("input[name=password]").val())) {
		notice('edit_notice', img_delete + ' 请填写完所有带红色星号的内容', true, 5000);
		return false;
	}
	if (userid && $("input[name=password]").val() && ! confirm('本次更新将连同密码一并更新，确定要继续吗？')) {
		notice('edit_notice', '', false);
		return false;
	}
	
	$(".input-submit").attr('disabled', true);
	
	var saveCallBack;
	if (userid == '' || userid == 0) {
		saveCallBack = user_save_added;
	} else {
		$("#edit_form").attr("action", "/admin/user/edit");
		saveCallBack = user_save_edited;
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
 * 添加用户成功，返回处理
 */
function user_save_added(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 添加用户成功!', true, 5000);
        
        // 判断是否返回用户列表管理
        if (back_listing == true) {
        	history.back(-1);
        }
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}

/**
 * 编辑用户成功，返回处理
 */
function user_save_edited(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 编辑成功!', true, 5000);
        history.back(-1);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}