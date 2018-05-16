$(document).ready(function() {
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_save_continue' : user_submit(); break;
		}
	});
});


/**
 * 用户表单提交处理
 */
function user_submit() {
	notice('edit_notice', img_loading_small, false);
	
	var userid = $("input[name=userid]:last").val();
	
	$(".input-submit").attr('disabled', true);
	
	var saveCallBack = user_save_edited;
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
 * 编辑个人资料成功，返回处理
 */
function user_save_edited(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 保存成功!', true, 5000);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}