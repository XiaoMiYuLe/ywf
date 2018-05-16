$(document).ready(function() {
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
	/**
	 * 预处理日期选择控件
	 */
	$('.datepicker-input').datepicker();

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
		
		if (! $("input[name=phone]").val()) {
			notice('edit_notice', img_delete + '手机号不能为空', true, 5000);
			return false;
		}
		if (! $("input[name=voucher_money]").val()) {
			notice('edit_notice', img_delete + ' 体验金金额不能为空', true, 5000);
			return false;
		}
		if (! $("input[name=use_money]").val()) {
			notice('edit_notice', img_delete + ' 每次使用金额不能为空', true, 5000);
			return false;
		}
	$(".input-submit").attr('disabled', true);
	
	/* 更新编辑器状态 */
	
	var voucher_id = $("#voucher_id").val();
	var saveCallBack;
	if (voucher_id == '' || voucher_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/voucheradmin/index/edit");
		saveCallBack = form_save_edited;
	}
	
	var options = {
            dataType:'json',
            type:'post',
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