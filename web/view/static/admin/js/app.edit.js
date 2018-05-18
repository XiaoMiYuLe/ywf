$(document).ready(function() {
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_cancel' : app_cancel(); break;
			case 'submit_save_back' : back_listing = true; app_submit(); break;
			case 'submit_save_continue' : back_listing = false; app_submit(); break;
		}
	});
});


/**
 * 取消处理
 */
function app_cancel() {
	history.go(-1);
}

/**
 * 模块表单提交处理
 */
function app_submit() {
	notice('edit_notice', img_loading_small, false);
	
	var appkey = $("#appkey_now").val();
	
	if (! appkey && (! $("textarea[name=name]").val())) {
		notice('edit_notice', img_delete + ' 请填写完所有带红色星号的内容', true, 5000);
		return false;
	}
	
	$(".input-submit").attr('disabled', true);
	
	var saveCallBack;
	if (appkey == '' || appkey == 0) {
		saveCallBack = app_save_added;
	} else {
		$("#edit_form").attr("action", "/admin/app/edit");
		saveCallBack = app_save_edited;
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
 * 添加模块成功，返回处理
 */
function app_save_added(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 添加模块成功!', true, 5000);
        
        // 判断是否返回模块列表管理
        if (back_listing == true) {
        	history.back(-1);
        }
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}

/**
 * 编辑模块成功，返回处理
 */
function app_save_edited(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 编辑成功!', true, 5000);
        history.back(-1);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}