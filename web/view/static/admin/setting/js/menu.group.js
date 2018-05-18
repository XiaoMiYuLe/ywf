$(document).ready(function() {
	/**
	 * 添加按钮处理
	 */
	$("#item_add").click(function(event){
		// 防止点击穿透
		event.preventDefault();
        event.stopPropagation();
        
		var href = "/adminfrontend/menugroup/add?loadtype=ajax";
		$.history.load(href);
	});
	
	/**
	 * 编辑按钮处理
	 */
	$(".operate-edit").click(function(){
		var page_id = $(this).parent().parent().attr("page_id");
		var href = "/pageadmin/group/edit?loadtype=ajax&page_id=" + page_id;
		$.history.load(href);
	});
	
	/**
	 * 删除按钮处理
	 */
	$(".operate-delete").click(function(){
		var group_id = $(this).parent().parent().attr("group_id");
		doDeleteGroup(group_id);
	});
	
	/**
	 * 排序处理
	 */
	$(".order").click(function(){
		var href = $(this).attr("href");
		var order = "DESC";
		if ($(this).hasClass("order-asc")) {
			order = "ASC";
		}
		href += "&orderby=" + order;
		
		$.history.load(href);
		return false;
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
	notice('edit_notice', img_loading_small, false);
	
	if (! $("input[name=group_name]").val() ) {
		notice('edit_notice', img_delete + ' 分组名称不能为空', true, 5000);
		return false;
	}
	
	var saveCallBack = form_save_added;
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
        notice('edit_notice', img_done + ' 添加分组成功!', true, 5000);
        
        // 判断是否返回列表管理
        if (back_listing == true) {
        	history.back(-1);
        }
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
}

/**
 * 删除单页分组
 */
function doDeleteGroup(id) {
	var del = confirm('确定要删除该单页吗？');
	if (! del) {return false;}
	
	var $obj_tr = $("tr#tr_" + id);
	$.ajax({
        type: "POST",
        url: "/adminfrontend/menugroup/delete",
        data: "id=" + id,
        dataType: "json",
        success: function(data) {
			if (data.status == 0) {
				$obj_tr.remove();
			}
			return false;
		},
		error: function() {
			ajaxError();
		}
    });
}