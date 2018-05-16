$(document).ready(function() {
	/**
	 * 切换模板编辑位置
	 */
	$('.nav-map').click(function(){
		/* 模板导航的样式处理 */
		$('.nav-map').removeClass('active');
		$(this).addClass('active');
		
		/* 切换编辑区域 */
		$('.edit-map').hide();
		var nav_id = $(this).attr('id');
		var edit_id = nav_id.replace('nav', 'edit');
		$('#' + edit_id).show();
		
		if ($('#' + edit_id).attr('id') == 'edit_related') {
			$('#' + edit_id).css({'display' : 'table'});
		}
	});
	
	/**
	 * 处理编辑内容快捷导航显示/隐藏事件
	 */
	$('.btn-nav-content').click(function(){
		var i = $(this).attr('btn_nav_content_index');
		if (i == 1) {
			switch_btn_nav_content($('.btn-nav-content:first'));
		} else {
			switch_btn_nav_content($('.btn-nav-content:last'));
		}
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
 * 处理编辑内容快捷导航显示/隐藏事件
 */
function switch_btn_nav_content(obj) {
	if (obj.hasClass('active')) {
		obj.removeClass('active');
	} else {
		obj.addClass('active');
	}
}

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
	
	/* 校验必填项 */
	if ($("select[name='category']").val() < 1) {
		notice('edit_notice', img_delete + ' 请选择所属分类', true, 5000);
		return false;
	}
	if (! $("input[name=title]").val()) {
		notice('edit_notice', img_delete + ' 文章标题不能为空', true, 50000);
		return false;
	}
	
	$(".input-submit").attr('disabled', true);
	
	/* 更新编辑器状态 */
	CKupdate();
	
	var content_id = $("#content_id").val();
	
	var saveCallBack;
	if (content_id == '' || content_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/articleadmin/index/edit");
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