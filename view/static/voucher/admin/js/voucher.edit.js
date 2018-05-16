$(document).ready(function() {
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_cancel' : form_cancel(); break;
			case 'submit_save_back' : back_listing = true; form_submit(); break;
			case 'submit_save_continue' : back_listing = false; form_submit(); break;
		}
	});
	
	//类型选项
	var type = $('select[name=type]').val();

	//初始化
	
	$(".order_money").hide();
	
	 if(type ==3){
     	$(".typethree").show()
     	$(".typeone").hide()
     	$(".typeonethree").show()
     	$(".voucher_money").hide();
     } else if(type ==1){
     	$(".typethree").hide()
     	$(".typeone").show()
     	$(".typeonethree").show()
     	$(".voucher_money").show();
     } else if(type ==2){
     	$(".typethree").hide()
     	$(".typeone").hide()
     	$(".typeonethree").hide()
     	$(".voucher_money").show();
     }
	
	 //值改变时
	$('select[name=type]').change(function(){     
        var value = $(this).val();
        if(value ==3){
        	$(".typethree").show()
        	$(".typeone").hide()
        	$(".typeonethree").show()
        	$(".order_money").hide();
        	$(".voucher_money").hide();
        } else if(value ==1){
        	$(".typethree").hide()
        	$(".typeone").show()
        	$(".typeonethree").show()
        	$(".order_money").hide();
        	$(".voucher_money").show();
        } else if(value ==2){
        	$(".typethree").hide()
        	$(".typeone").hide()
        	$(".typeonethree").hide()
        	$(".order_money").hide();
        	$(".voucher_money").show();
        }
     });  
	
	if($(".checkbox2").is(':checked') || $(".checkbox3").is(':checked')){
		$(".order_money").show();
	}else{
		$(".order_money").hide();
	}
	
	$(".checkbox2").change(function(){
		if($(".checkbox2").is(':checked') || $(".checkbox3").is(':checked')){
			$(".order_money").show();
		}else{
			$(".order_money").hide();
		}
	});
	
	$(".checkbox3").change(function(){
		if($(".checkbox2").is(':checked') || $(".checkbox3").is(':checked')){
			$(".order_money").show();
		}else{
			$(".order_money").hide();
		}
	});
	
	//编辑时
	var voucher_id = $("input[name=voucher_id]").val()
	
	if(voucher_id !="" || undefined || null){
		$("select[name=type]").attr("disabled","disabled");
	}
});


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
	
		type = $("select[name=type]").val()
		if(type==3){
			if(!$("input[name=increase_interest]").val()){
				notice('edit_notice', img_delete + ' 利息不能为空', true, 5000);
				return false;
			}
		}
		
		if((type==1 || type==2) && (! $("input[name=voucher_money]").val())){
				notice('edit_notice', img_delete + '金额不能为空', true, 5000);
				return false;
		}
		if(type==1){
			if (! $("input[name=use_money]").val()) {
				notice('edit_notice', img_delete + '满多少使用不能为空', true, 5000);
				return false;
			}
		}
		
		if (! $("input[name=valid_data]").val()) {
			notice('edit_notice', img_delete + '有效天数不能为空', true, 5000);
			return false;
		}
		
		if($(".checkbox2").is(':checked') || $(".checkbox3").is(':checked')){
			
			if($(".checkbox3").is(':checked')){
				var order_money = $("#order_money").val();
				if(!order_money){
					notice('edit_notice', img_delete + '下单满多少返不能为空', true, 5000);
					return false;
				}
			}
		}else{
			 $("#order_money").val("");
		}
		
		checkbox = $("input:checkbox[name='voucher_type[]']:checked");
		
		if(checkbox.length <= 0){
			notice('edit_notice', img_delete + ' 使用不能为空', true, 5000);
			return false;
		}
	
	$(".input-submit").attr('disabled', true);
	
	/* 更新编辑器状态 */
	
	var voucher_id = $("#voucher_id").val();
	var saveCallBack;
	if (voucher_id == '' || voucher_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/voucheradmin/voucher/edit");
		saveCallBack = form_save_edited;
	}
	
	var options = {
            dataType:'json',
            type:'post',
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
	
    if(data.status === 0) {
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