$(document).ready(function() {
	/**
	 * 提交按钮处理
	 */
	$("input.input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_cancel' : form_cancel(); break;
			case 'submit_save_back' : back_listing = false; form_submit(); break;
		}
	});
});


/**
 * 表单提交处理
 */
function form_submit() {
	
	var saveCallBack;
	
		saveCallBack = form_save_edited;
	
	
	var options = {
            dataType:'json',
            timeout:60000,
            success:saveCallBack
    };
    $("#edit_form").ajaxSubmit(options);
    return false;
}

/**
 * 编辑成功，返回处理
 */
function form_save_edited(data, textStatus) {
    if (data.status === 0) {
        alert('编辑成功!');
        $('#comments_listing').datagrid('reload');
        $('#ajaxModal').modal('hide');
    } else {
    	alert(data.error);
    }
}