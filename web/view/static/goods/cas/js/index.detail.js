$(document).ready(function() {
	 /**
	 * 返回
	 */
    $("#button_cancel").click(function(){
    	history.go(-1);
	});
	/**
	 * 切换模板
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
	
});


/**
 * 取消处理
 */
function form_cancel() {
	history.go(-1);
}

