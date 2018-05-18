$(document).ready(function() {

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
	
//	ajax调用分类
	$('.trig').change(function(){
		var id = $(this).val();
		notice('edit_notice', '正在调取分类,请稍后...', true, 1000);
		$.ajax({
			url:'/couponadmin/index/getOption',
			data:{"id":id},
			type:'post', 
			dataType:'json', 
			success:function(data){
				var html = ' '
				$('.area').html(html);
				if(data.status == 0){
					if(data.data){
						$.each(data.data,function(key,val){
							html = '<option value =' + val.category_id + '>'
							     + val.category_name + '</option>';
							$('.area').prepend(html);
						});
					} else {
						html = '<option>全场满减</option>';
						$('.area').prepend(html);
					}
				}	
			}
		});
	});
});

/**
 * 取消处理
 */
function form_cancel() {
	window.location.reload();
	history.go(-1);
}

/**
 * 表单提交处理
 */
function form_submit() {
	notice('edit_notice', img_loading_small, false);
	
	var coupon_id = $("input[name=coupon_id]").val();

	var saveCallBack;
	if (coupon_id == '' || coupon_id == 0) {
		$("#edit_form").attr("action", "/couponadmin/Index/add");
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/couponadmin/Index/edit");
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
        	window.location.reload();
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
        window.location.reload();
        history.back(-1);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}