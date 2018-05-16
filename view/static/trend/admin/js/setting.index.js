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
	 * 保存设置 - text 类型
	 */
	$('input[type=text]').on('blur', function(){
		save_setting($(this).attr('setting_name'), $(this).val(), $(this).attr('group_id'), $(this).attr('setting_note'), false);
	});
	
	/**
	 * 保存设置 - textarea 类型
	 */
	$('textarea').on('blur', function(){
		save_setting($(this).attr('setting_name'), $(this).val(), $(this).attr('group_id'), $(this).attr('setting_note'), false);
	});
	
	/**
	 * 保存设置 - select 类型
	 */
	$('select').on('change', function(){
		save_setting($(this).attr('setting_name'), $(this).val(), $(this).attr('group_id'), $(this).attr('setting_note'), false);
	});
	
	/**
	 * 保存设置 - checkbox 类型
	 */
	$('input[type=checkbox]').on('click', function(){
		var clear = true;
		if ($(this).is(':checked')) {
			clear = false;
		}
		save_setting($(this).attr('setting_name'), $(this).val(), $(this).attr('group_id'), $(this).attr('setting_note'), clear);
	});
	
	/**
	 * 保存设置 - radio 类型
	 */
	$('input[type=radio]').on('click', function(){
		save_setting($(this).attr('setting_name'), $(this).val(), $(this).attr('group_id'), $(this).attr('setting_note'), false);
	});
	
	/**
	 * 保存设置 - file 类型
	 */
	$('input[type=file]').on('click', function(){
		uploadFiles($(this));
	});
	
	/**
	 * 删除图片
	 */
	$('.gallery-delete-single').click(function(){
		var setting_id = $(this).attr('setting_id');
		if (! setting_id) {
			return false;
		}
		
		/* 等待状态处理 */
    	notice('edit_notice_' + setting_id, img_loading_icon, false);
		
		var options = {
	        url:'/trendadmin/setting/dropFile',
	        data:{"setting_id": setting_id},
	        dataType:'json',
	        type:'POST',
	        timeout:60000,
	        success:function(data){
	        	if (data.status == 0) {
	        		$('#gallery_add_single_' + setting_id).attr('src', '...');
	        		$('#gallery_delete_single_' + setting_id).attr('setting_id', '');
	        		notice('edit_notice_' + setting_id, img_done + ' 删除成功', true, 5000);
	        	} else {
	        		notice('edit_notice_' + setting_id, img_delete + ' 删除失败，请刷新后重试', true, 5000);
	        	}
	        }
		};
		$.ajax(options);
	});
});


/**
 * 保存设置
 * 
 * @param integer setting_id 设置记录的 ID
 * @param string val 设置记录的值
 * @param boolean clear 是否清除设置记录的值
 */
function save_setting(setting_name, val, group_id, note, clear) {
	if(setting_name == ''){return false;}
	$.ajax({
    	type: 'post',
        url: '/trendadmin/setting/setVal',
        data: 'setting_name=' + setting_name + '&val=' + val + '&group_id='+ group_id +'&note='+ note +'&clear=' + clear,
        dataType: 'json',
        timeout: 60000,
        success: function(data){
    		if (data.status == 0) {
    			notice('edit_notice', img_done + ' 设置成功', true, 5000);
    		} else {
    			notice('edit_notice', img_delete + ' 设置失败，请刷新后重试', true, 5000);
    		}
    		return false;
    	}
    });
}

/**
 * 上传图片
 */
function uploadFiles(obj) {
	var setting_id = obj.attr('setting_id');
	var name = obj.attr('name');
	
	(function(){
		$('input[type=file]').fileupload({
	        dataType: 'json',
	        url: '/trendadmin/setting/uploadFile?setting_id=' + setting_id + '&name=' + name,
	        submit: function(e, data) {
	        	/* 上传按钮样式处理 */
	        	$('input[name=' + name + ']').prop('disabled', true);
	        	$('#gallery_delete_single_' + setting_id).prop('disabled', true);
	        	
	        	/* 等待状态处理 */
	        	notice('edit_notice_' + $(this).attr('setting_id'), img_loading_icon, false);
	        },
	        done: function (e, data) {
	        	/* 等待状态处理 */
	        	notice('edit_notice_' + setting_id, img_done + ' 设置成功', true, 5000);
	        	
	        	/* 上传按钮样式处理 */
	        	$('input[name=' + name + ']').removeAttr('disabled');
	        	$('#gallery_delete_single_' + setting_id).removeAttr('disabled');
	        	
	        	/* 返回数据显示处理 */
	        	if (data.result.status == 0) {
	            	$("#gallery_add_single_" + setting_id).attr('src', STORE_URL + data.result.data.url);
	            	$('#gallery_delete_single_' + setting_id).attr('setting_id', setting_id);
	            	notice('edit_notice_' + setting_id, img_done + ' 设置成功', true, 5000);
	            } else {
	            	notice('edit_notice_' + setting_id, img_delete + ' 设置失败，请刷新后重试', true, 5000);
	            }
	        }
	    });
	})();
}