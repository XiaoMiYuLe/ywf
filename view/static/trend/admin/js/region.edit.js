$(document).ready(function() {
	/**
	 * 提交按钮处理
	 */
	$("input.input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_cancel' : form_cancel(); break;
			case 'submit_save_back' : back_listing = true; form_submit(); break;
			case 'submit_save_continue' : back_listing = false; form_submit(); break;
		}
	});
});


/**
 * 表单提交处理
 */
function form_submit() {
	if (! $("input[name=region_name]").val()) {
		alert('地区名称不能为空');
		return false;
	}
	
	var region_id = $("input[name=region_id]").val();
	
	var saveCallBack;
	if (region_id == '' || region_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/trendadmin/region/edit");
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
    if (data.status === 0) {
        alert('添加成功!');
        
        // 判断是否返回列表管理
        if (back_listing == true) {
	    	$('#modal_brand').modal('hide');
	    	window.location.href = "/trendadmin/region"; 
	        $('.close').trigger('click');
        }
    } else {
    	alert(data.error);
    }
}

/**
 * 编辑成功，返回处理
 */
function form_save_edited(data, textStatus) {
    if (data.status === 0) {
        alert('编辑成功!');
        
        $('#modal_brand').modal('hide');
        window.location.href="/trendadmin/region"; 
        $('.close').trigger('click');
        
    } else {
    	alert(data.error);
    }
}