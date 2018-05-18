$(document).ready(function() {
	/**
	 * 重载已选属性列表的拖拽事件
	 */
	$('#selected_property').find('ul').sortable('refresh');
	var x = $(".x").val();
    var y = $(".y").val();

	if (x != "" && y != "") {
		 $("input[name=x]").val(x);
		 $("input[name=y]").val(y);
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
	});

	/**
	 * 预处理日期选择控件
	 */
	$('.datepicker-input').datepicker();

	/**
	 * 动态加载活动模板
	 */
	$('#category_id').on('change', function(){
		load_template($("#category_id option:selected").attr('template_id'));
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
 * 动态加载活动模板
 */
function load_template(template_id){
	/* 清空规则显示区 */
	$('#edit_rules').html('暂无规则');

	/* 若当前分类没有绑定模板，则直接返回 */
	if (! template_id || template_id == 0) {
		return false;
	}

	/* 动态加载当前选择的规则模板 */
	$.ajax({
    	type:'post',
        url:'/promotionadmin/index/getTemplateById',
        data:'template_id=' + template_id,
        timeout:60000,
        success:function(data){
        	if (data.status == 0) {
        		var rules = data.data.rules ? data.data.rules : '暂无规则';
        		$('#edit_rules').html(data.data.rules);
        	}
    		return false;
    	}
    });

	return false;
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
	var category_id = $("select[name=category_id]").val();
    var title = $("input[name=title]").val();
	if (category_id == 0 ){
		notice('edit_notice', img_delete + "请选择活动类型", true, 5000);
		return false;
	}else if (title == "") {
		notice('edit_notice', img_delete + "活动名称不能为空", true, 5000);
		return false;
	}

	/* 处理参与活动商品信息 */
	var related_content_ids = '';
	var related_content_ids_arr = new Array();
	$('#selected_related_goods').find('ul>li').each(function(k){
		var content_id_selected = $(this).attr('content_id');
		related_content_ids_arr[k] = content_id_selected;
	});

	if (related_content_ids_arr.length > 0) {
		related_content_ids = related_content_ids_arr.join(',');
	}else {
		notice('edit_notice',img_delete + "请选择参与活动的商品",true,5000);
		return false;
	}

	$('input[name=related_goods]').val(related_content_ids);

    var promotion_id = $("input[name=promotion_id]").val();

    //禁用按钮
	$(".input-submit").attr('disabled', true);

	var saveCallBack;
	if (promotion_id == '') {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/promotionadmin/index/edit");
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