$(document).ready(function() {
    /**
	 * 返回
	 */
    $("#cancel").click(function(){
    	history.go(-1);
	});
    
    /**
     * 处理提现
     */
    $('body').delegate('#put_in', 'click', function(){
    	var withdraw_id = $('#withdraw_id').val();
    	var remark = $('#remark').val();
    	var platform_number = $('#platform_number').val();
    	var withdraw_status = $('#withdraw_status:checked').val(); 
    	dealWithdraw(withdraw_id,remark,platform_number,withdraw_status);
    	return false;
    });
});   

    /**
     * 处理提现
     */
    function dealWithdraw(withdraw_id, remark, platform_number, withdraw_status) {
    	$.ajax({
        	type:'post',
            url:'/withdrawadmin/index/deal',
            data:'withdraw_id=' + withdraw_id + '&remark=' + remark + '&platform_number=' + platform_number + '&withdraw_status=' + withdraw_status,
            dataType:'json',
            timeout:60000,
            success:function(data){
        		if (data.status == 0) {
        			alert('成功！');
        			location.reload();
        		} else {
        			alert(data.error);
        		}
        		return false;
        	}
        });
    }
    
