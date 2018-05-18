$(document).ready(function() {
    /**
	 * 返回
	 */
    $("#cancel").click(function(){
    	history.go(-1);
	});
    
	/**
	 * 预处理日期选择控件
	 */
	$('.datepicker-input').datepicker();
	
    $(".button_bank").click(function(){
        $bank_id = $(this).val();
   	  ajax_post();
    });

    	function ajax_post(){
    	  $.post("/casadmin/index/delete",{bank_id:$bank_id},
    	  function(data){
    		 if(data.status==0){
    			alert('成功')
    			location.reload();
    		 }else{
    			 alert(data.error)
    			 }
    	  },
    	  "json");
    	}
    	
    	/*
    	 * 更改状态
    	 * */
    	$("#change_status").click(function(){
    		var order_id = $('#order_id').val();
    		$.ajax({
    	    	type:'post',
    	        url:'/btsadmin/order/delete',
    	        data:'order_id=' + order_id ,
    	        dataType:'json',
    	        timeout:60000,
    	        success:function(data){
    	    		if (data.status == 0) {
    	    			alert('取消成功！');
    	    			location.reload();
    	    		} else {
    	    			alert(data.error);
    	    		}
    	    	}
    	    });
    		
    	});
    	
    	/**
         * 处理预约订单
         */
        $("#put_in").click(function(){
        	var start_time = $('#start_time').val();
        	var end_time = $('#end_time').val();
        	var pay_time = $('#pay_time').val();
        	var cash_time = $('#cash_time').val(); 
        	var order_id = $('#order_id').val();
        	if (! $("#start_time").val()) {
        		alert('订单起息时间不能为空');
        		return false;
        	}
        	if (! $("#end_time").val()) {
        		alert('订单结息时间不能为空');
        		return false;
        	}
        	if (! $("#pay_time").val()) {
        		alert('订单支付时间不能为空');
        		return false;
        	}
        	if (! $("#cash_time").val()) {
        		alert('订单兑付时间不能为空');
        		return false;
        	}
        	dealOrder(start_time,end_time,pay_time,cash_time,order_id);
        	return false;
        });
});

/**
 * 处理预约订单
 */
function dealOrder(start_time,end_time,pay_time,cash_time,order_id) {
	$.ajax({
    	type:'post',
        url:'/btsadmin/order/deal',
        data:'start_time=' + start_time + '&end_time=' + end_time + '&pay_time=' + pay_time + '&cash_time=' + cash_time + '&order_id=' + order_id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			alert('生效成功！');
    			location.reload();
    		} else {
    			alert(data.error);
    		}
    	}
    });
}