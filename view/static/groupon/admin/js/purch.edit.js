$(document).ready(function() {
	/**
	 *  日期控件格式化
	 */
	$(".wdatepicker").focus(function(){
		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	});
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
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
	
//	if (! $("input[name=page_id]").val()) {
//		if (! $("input[name=page_folder]").val() || ! $("input[name=title]").val()) {
//			notice('edit_notice', img_delete + ' 网页名称及网页标题不能为空', true, 5000);
//			return false;
//		}
//	} else {
//		if (! $("input[name=title]").val()) {
//			notice('edit_notice', img_delete + ' 网页标题不能为空', true, 5000);
//			return false;
//		}
//	}
	
	$(".input-submit").attr('disabled', true);
	
	var page_id = $("#bulk_id").val();
	
	var saveCallBack;
	if (page_id == '' || page_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/grouponadmin/purch/edit");
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
 * 添加成功，返回处理
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