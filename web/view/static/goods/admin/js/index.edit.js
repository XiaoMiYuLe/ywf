/**
 * 定义一些常量
 */
var content_id = $('#content_id').val();

$(document).ready(function() {
	/**
	 * 表单初始化
	 */
	if (! content_id) {
		$('input[name=attachment_ids]:last').val('');
	}
	
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
	 * 处理编辑商品快捷导航显示/隐藏事件
	 */
	$('.btn-nav-goods').click(function(){
		var i = $(this).attr('btn_nav_goods_index');
		if (i == 1) {
			switch_btn_nav_goods($('.btn-nav-goods:first'));
		} else {
			switch_btn_nav_goods($('.btn-nav-goods:last'));
		}
	});
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_type = $(this).attr("data_submit_type");
		switch (submit_type) {
			case 'submit_cancel' : form_cancel(); break;
			case 'submit_save_back' : back_listing = true; form_submit(); break;
			case 'submit_save_continue' : back_listing = false; form_submit(); break;
		}
	});
});


/**
 * 处理编辑商品快捷导航显示/隐藏事件
 */
function switch_btn_nav_goods(obj) {
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
	
	
	/* 必填项校验 */
	if (! $("input[name=goods_name]").val()) {
		notice('edit_notice', img_delete + ' 产品名称不能为空', true, 50000);
		return false;
	}
	if (! $("#goods_type").val()) {
		notice('edit_notice', img_delete + ' 请选择产品类型', true, 5000);
		return false;
	}
	if (! $("#goods_status").val()) {
		notice('edit_notice', img_delete + ' 请选择产品状态', true, 5000);
		return false;
	}
	if (! $("input[name=yield]").val()) {
		notice('edit_notice', img_delete + ' 产品年化收益率不能为空', true, 50000);
		return false;
	}
	if (! $("input[name=high_pay]").val()) {
		notice('edit_notice', img_delete + ' 产品购买上限不能为空', true, 50000);
		return false;
	}
	if (! $("input[name=increasing_pay]").val()) {
		notice('edit_notice', img_delete + ' 产品递增金额不能为空', true, 50000);
		return false;
	}
	if (! $("input[name=low_pay]").val()) {
		notice('edit_notice', img_delete + ' 产品起售金额不能为空', true, 50000);
		return false;
	}
	if (! $("input[name=goods_broratio]").val()) {
		notice('edit_notice', img_delete + ' 产品佣金比例不能为空', true, 50000);
		return false;
	}
	if (! $("input[name=all_fee]").val()) {
		notice('edit_notice', img_delete + ' 产品总额度不能为空', true, 50000);
		return false;
	}
	if (! $("textarea[name=comment]").val()) {
		notice('edit_notice', img_delete + ' 产品备注不能为空', true, 50000);
		return false;
	}
	if (! $("input[name=deal_way]").val()) {
		notice('edit_notice', img_delete + ' 产品兑付方式不能为空', true, 50000);
		return false;
	}
	$('textarea[name="goods_detail"]').val(ckeditor1.getData());
	$('textarea[name="safety"]').val(ckeditor2.getData());
	
	var goods_id = $("input[name=goods_id]").val();
	
	$(".input-submit").attr('disabled', true);
	
//	CKupdate();
	
	var saveCallBack;
	if (goods_id == '' || goods_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/goodsadmin/index/edit");
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