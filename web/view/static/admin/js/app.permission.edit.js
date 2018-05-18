$(document).ready(function() {
	/**
	 * 模块搜索处理
	 */
	$(".app-permission-appkey").click(function(event){
		event.preventDefault();
        event.stopPropagation();
        
		var page = $("input[name=page]:last").val();
		var href = $(this).attr("href") + "&page=" + page;
		
		$.history.load(href);
		return false;
	});
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_cancel' : app_permission_cancel(); break;
			case 'submit_save_back' : back_listing = true; app_permission_submit(); break;
			case 'submit_save_continue' : back_listing = false; app_permission_submit(); break;
		}
	});
});


/**
 * 取消处理
 */
function app_permission_cancel() {
	history.go(-1);
}

/**
 * 动作表单提交处理
 */
function app_permission_submit() {
	notice('edit_notice', img_loading_small, false);
	
	if (! $("select[name=ap_permission_id]").val() || ! $("input[name=ap_module]").val() || 
		! $("input[name=ap_controller]").val() || ! $("input[name=ap_action]").val() || ! $("select[name=ap_appkey]").val()) {
		notice('edit_notice', img_delete + ' 请填写完所带红色星号的内容', true, 5000);
		return false;
	}
	
	var keys = $("#keys").val();
	
	var saveCallBack;
	if (keys == '' || keys == 0) {
		saveCallBack = app_permission_save_added;
	} else {
		$("#edit_form").attr("action", "/admin/apppermission/edit");
		saveCallBack = app_permission_save_edited;
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
 * 添加权限成功，返回处理
 */
function app_permission_save_added(data, textStatus) {
    if(data.status === 0) {
        notice('edit_notice', img_done + ' 添加权限成功!', true, 5000);
        
        // 判断是否返回权限列表管理
        if (back_listing == true) {
        	history.back(-1);
        }
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
}

/**
 * 编辑权限成功，返回处理
 */
function app_permission_save_edited(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 编辑成功!', true, 5000);
        history.back(-1);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
}