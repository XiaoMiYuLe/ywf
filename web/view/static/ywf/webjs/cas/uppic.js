$(function(){
	$('#qrsc').click(function(){
		var choose = $("#choose").attr("class");
		if(! choose){
			alert('您需要同意经纪人条款才能申请!')
			return false;
		}
		
		saveCallBack = form_save_added;
		var options = {
	            dataType:'json',
	            timeout:60000,
	            success:saveCallBack,
	    };
	    $("#edit_form").ajaxSubmit(options);
	    return false;
	})
	
    /**
	 * 添加成功，返回处理
	 */
	function form_save_added(data, textStatus) {
	    if (data.status === 0) {
	    	alert('上传成功')
	    	window.location.reload();
	    } else {
	    	alert(data.error)
	    }
	}
	
	var choose= true;
	$('#choose').click(function(){
		if (choose) {
			$(this).removeClass('choose');
			choose=false;
		}else{
			$(this).addClass('choose');
			choose=true;
		};
	});
});