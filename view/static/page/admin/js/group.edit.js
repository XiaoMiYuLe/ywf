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
});


/**
 * 取消处理
 */
function form_cancel() {
	$('#ajaxModal').modal('hide');
}

/**
 * 表单提交处理
 */
function form_submit() {
	var group_id = $("input[name=group_id]").val();
	
	if (! $("input[name=group_name]").val()) {
		alert('分组名称不能为空');
		return false;
	}
	
	if (! group_id && ! $("input[name=folder]").val()) {
		alert('分组文件名不能为空');
		return false;
	}
	
	$(".input-submit").attr('disabled', true);
	
	var saveCallBack;
	if (group_id == '' || group_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/pageadmin/group/edit");
		saveCallBack = form_save_edited;
	}
	
	var options = {
            dataType:'json',
            timeout:60000,
            success:saveCallBack
    };
    $("#edit_form").ajaxSubmit(options);
    return false;
}

/**
 * 添加成功，返回处理
 */
function form_save_added(data, textStatus) {
    if(data.status === 0) {
    	alert('添加成功!');
        
        // 判断是否返回列表管理
        if (back_listing == true) {
        	$('#content_listing').datagrid('reload');
        	$('#ajaxModal').modal('hide');
        }
    } else {
    	alert(data.error);
    }
    $(".input-submit").removeAttr('disabled');
}

/**
 * 编辑成功，返回处理
 */
function form_save_edited(data, textStatus) {
    if (data.status === 0) {
        alert('编辑成功!');
        $('#content_listing').datagrid('reload');
        $('#ajaxModal').modal('hide');
    } else {
    	alert(data.error);
    }
    $(".input-submit").removeAttr('disabled');
}