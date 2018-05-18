$(document).ready(function() {
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var ct = $(this).attr('ct');
		
		if (ct == 'redis' || ct == 'memcache') {
			var str_confirm = '确定要清除 ' + ct + ' 数据缓存吗？';
			if (! confirm(str_confirm)) {
				return false;
			}
		}
		
		$('input[name=target]:last').val(ct);
		
		if (ct == 'all') {
			$("#edit_form").attr("action", "/admin/cache/cleanAll");
		} else {
			$("#edit_form").attr("action", "/admin/cache/cleanSingle");
		}
		
		form_submit();
	});
});

/**
 * 表单提交处理
 */
function form_submit() {
	notice('edit_notice', img_loading_small, false);
	
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
 * 提交成功，返回处理
 */
function form_save_added(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 清理成功!', true, 5000);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}