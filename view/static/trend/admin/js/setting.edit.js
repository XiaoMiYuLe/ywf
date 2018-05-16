$(document).ready(function() {
	/**
	 * 根据标签类型，判断是否显示可选值输入项
	 */
	$('#val_inputtype').change(function(){
		if ($(this).val() == 'select' || $(this).val() == 'radio' || $(this).val() == 'checkbox') {
			$('#for_val_inputtype').show();
		} else {
			$('#for_val_inputtype').hide();
		}
	});
	
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
	
	var setting_id = $("#setting_id").val();
	
	/* 校验必填项 */
	if (! $("#name").val() || $.trim($("#name").val()) == '') {
		notice('edit_notice', img_delete + ' 参数名不能为空', true, 5000);
		return false;
	}
	if (! $("#label_name").val() || $.trim($("#label_name").val()) == '') {
		notice('edit_notice', img_delete + ' 标签名称不能为空', true, 5000);
		return false;
	}
	
	if ($("#val_inputtype").val() == 'select' || $("#val_inputtype").val() == 'radio' || $("#val_inputtype").val() == 'checkbox') {
		if (! $("#val_options").val() || $.trim($("#val_options").val()) == '') {
			notice('edit_notice', img_delete + ' 可选值不能为空', true, 5000);
			return false;
		}
	}
	
	$(".input-submit").attr('disabled', true);
	
	var saveCallBack ;
	if (setting_id == '' || setting_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/trendadmin/setting/edit");
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
    if (data.status === 0) {
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