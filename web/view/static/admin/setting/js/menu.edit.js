$(document).ready(function() {
	/**
	 * 智能定位分组
	 */
	var group_id = $('input[name=group_id]').val();
	$('select[name=group_id]').val(group_id);
	
	/**
	 * 重置父导航
	 */
    $("#resetParentNavigation").click(function() {
        $('#pid').get(0).selectedIndex = 0;
    });
    
    /**
     * 处理链接方式
     */
    $('input[name=link_type]').change(function(){
    	var link_type = $(this).val();
    	if (link_type == 1) {
    		$('#link_folder').show();
    		$('#link_url').hide();
    	} else {
    		$('#link_folder').hide();
    		$('#link_url').show();
    	}
    });
    
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
	if (! $("input[name=menu]").val()) {
		alert('导航名称不能为空');
		return false;
	}
	
	var menu_id = $("input[name=menu_id]").val();
	
	var saveCallBack;
	if (menu_id == '' || menu_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/setting/menu/edit");
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
        	$('#menu_listing').datagrid('reload');
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
        $('#menu_listing').datagrid('reload');
        $('#ajaxModal').modal('hide');
    } else {
    	alert(data.error);
    }
}