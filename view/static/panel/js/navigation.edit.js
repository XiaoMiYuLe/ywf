$(document).ready(function() {
	/**
	 * 选择 ICON 背景
	 */
	$('#icon_bg_ul>li').click(function(){
		var icon_bg = $(this).find('input[name=icon_bg]').val();
		var icon_bg_old = $('#icon_bg_selected>span:first').text();
		$('#icon_bg_selected>span:first').html(icon_bg).removeClass(icon_bg_old).addClass(icon_bg);
	});
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_save_back' : back_listing = true; form_submit(); break;
			case 'submit_save_continue' : back_listing = false; form_submit(); break;
		}
	});
});


/**
 * 表单提交处理
 */
function form_submit() {
	if (! $("input[name=title]").val()) {
		alert('导航名称不能为空');
		return false;
	}
	
	var navigation_id = $("input[name=navigation_id]").val();
	
	var saveCallBack;
	if (navigation_id == '' || navigation_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/panel/navigation/edit");
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
        	$('#navigation_listing').datagrid('reload');
        	$('#ajaxModal').modal('hide');
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
        $('#navigation_listing').datagrid('reload');
        $('#ajaxModal').modal('hide');
    } else {
    	alert(data.error);
    }
}