$(document).ready(function() {
	/**
	 * 应用列表处理
	 */
	$(".nav-top-list").click(function(event){
		event.preventDefault();
        event.stopPropagation();
        
		var href = $(this).attr("href");
		
		$.history.load(href);
		return false;
	});
	
	/**
	 * 授权/解除授权
	 */
	$('input.permission_assign').click(function(){
        Permission_Assign($(this).attr('ut'), $("input[name=parameter]:last").val(), $(this).val(), $(this).is(':checked'), $(this).attr('pg'));
    });
});


function Permission_Assign($ptype, $parameter, $permission_id, $assign, $permission_group) {
	var appkey = $("input[name=appkey]:last").val();
	$.ajax({
    	type:'post',
        url:'/admin/permissionAssign/assignPermission',
        data:'ptype=' + $ptype + '&parameter=' + $parameter + '&permission_id=' + $permission_id + '&assign=' + ($assign ? 1 : 0) + '&appkey=' + appkey,
        dataType:'json',
        timeout:60000,
        success:function(data){
        	var op_title = ($assign ? '' : '解除') + '授权' + $permission_id;
    		if (data.status == 0) {
    			notice('edit_notice_' + $permission_group, img_done + ' ' + op_title + '成功', true, 5000);
    		} else {
    			notice('edit_notice_' + $permission_group, img_delete + ' ' + op_title + '失败。原因：' + data.error, true, 5000);
    			if ($assign) {
    				$('#pm_' + $permission_id).attr('checked', false);
    			} else {
    				$('#pm_' + $permission_id).attr('checked', true);
    			}
    		}
    		return false;
    	},
        error:ajaxError
    });
}
