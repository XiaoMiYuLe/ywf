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
		
		/* 处理隐藏字段 */
		if (nav_id == 'nav_app') {
			$('input[name=build_type]:last').val('app');
		} else {
			$('input[name=build_type]:last').val('table');
		}
	});
	
	/**
	 * 处理编辑快捷导航显示/隐藏事件
	 */
	$('.btn-nav-quick').click(function(){
		var i = $(this).attr('btn_nav_quick_index');
		if (i == 1) {
			switch_btn_nav_quick($('.btn-nav-quick:first'));
		} else {
			switch_btn_nav_quick($('.btn-nav-quick:last'));
		}
	});
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_type = $(this).attr("data_submit_type");
		switch (submit_type) {
			case 'submit_save_back' : 
				back_listing = true;
				if ($('#nav_app').hasClass('.active')) {
					$('input[name=build_type]:last').val('app');
				} else {
					$('input[name=build_type]:last').val('table');
				}
				form_submit();
			break;
			case 'submit_save_continue' : 
				back_listing = false;
				$('input[name=build_type]:last').val('all');
				form_submit();
			break;
		}
	});
});


/**
 * 处理编辑快捷导航显示/隐藏事件
 */
function switch_btn_nav_quick(obj) {
	if (obj.hasClass('active')) {
		obj.removeClass('active');
	} else {
		obj.addClass('active');
	}
}

/**
 * 表单提交处理
 */
function form_submit() {
	notice('edit_notice', img_loading_small, false);
	
	/* 判断是否全选 */
	if ($('input[name=build_type]:last').val() == 'all') {
		$('.edit-map').find('input[type=checkbox]').prop('checked', true);
	}
	
	/* 参数校验 */
	if ($('#nav_app').hasClass('active')) {
		if ($(".build-from-apps:checked").size() < 1) {
			notice('edit_notice', img_delete + " 请选择模块", true, 5000);
			return false;
		}
	} else {
		if (! $(".build-from-tables:checked").size()) {
			notice('edit_notice', img_delete + " 请选择数据表", true, 5000);
			return false;
		}
	}
	
	$(".input-submit").attr('disabled', true);
	
	var saveCallBack = form_save_added;
	var options = {
            dataType:'json',
            timeout:60000,
            success:saveCallBack
    };
    $("#edit_form").ajaxSubmit(options);
    return false;
}

/**
 * 生成成功，返回处理
 */
function form_save_added(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 生成成功!', true, 5000);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}
