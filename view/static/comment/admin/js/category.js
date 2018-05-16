$(document).ready(function() {
	/**
	 * 添加按钮处理
	 */
	$("#item_add").click(function(event){
		/* 防止点击穿透 */
		event.preventDefault();
        event.stopPropagation();
        
		var href = "/commentadmin/category/add?loadtype=ajax";
		$.history.load(href);
	});
	
	/**
	 * list_filter 条件筛选
	 */
	$(".nav-list-filter").on('click', function(event){
		/* 防止点击穿透 */
		event.preventDefault();
        event.stopPropagation();
        
		$.history.load($(this).attr('href'));
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
	 * 编辑按钮处理
	 */
	$(".operate-edit").click(function(){
		var category_id = $(this).parent().parent().attr("category_id");
		var href = "/commentadmin/category/edit?loadtype=ajax&category_id=" + category_id;
		$.history.load(href);
	});
	
	/**
	 * 添加子分类按钮处理
	 */
	$(".operate-add-son").click(function(){
		var category_id = $(this).parent().parent().attr("category_id");
		var href = "/commentadmin/category/edit?loadtype=ajax&category_id=" + category_id;
		$.history.load(href);
	});
	
	/**
	 * 删除按钮处理
	 */
	$(".operate-delete").click(function(){
		var category_id = $(this).parent().parent().attr("category_id");
		doDeleteGroup(category_id);
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
	
	if (! $("input[name=title]").val()) {
		notice('edit_notice', img_delete + ' 分类名称不能为空', true, 5000);
		return false;
	}
	
	var category_id = $("input[name=category_id]").val();
	
	var saveCallBack;
	if (category_id == '' || category_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/commentadmin/category/edit");
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
}

/**
 * 删除
 */
function doDeleteGroup(id) {
	var del = confirm('确定要删除该分类吗？');
	
	if (! del) {return false;}
	
	var $obj_tr = $("tr#tr_" + id);
	
	$.ajax({
        type: "POST",
        url: "/commentadmin/category/delete",
        data: "category_id=" + id,
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