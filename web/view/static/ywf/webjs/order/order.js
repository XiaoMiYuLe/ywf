$(document).ready(function () {
	//$("#button1").attr('disabled', true);
	//$("#button1").attr('style','background:gray');
	$("#button1").click(function(){
		var choose = $("#choose").attr("class");
		var order= $("#order").val();
		myCode = $('#check_code').val();
		if(! myCode){
			showAlert({
                   alertTitle: "温馨提示",
                   alertContent: '请输入短信验证码',
                   alertType: 1,
                   singleBtnText: "确定"
                   });
			return false;
		}
		
		if(! choose){
			showAlert({
                   alertTitle: "温馨提示",
                   alertContent: '您需要同意协议条款才能交易!',
                   alertType: 1,
                   singleBtnText: "确定"
                   });
			return false;
		}
		
		$.post("/bts/order/pay",{'order_no':order,'check_code':myCode},function(result1){
			if(result1.status==0){
				$.post("/cas/bank/search",{'order_no':order},function(result3){
					if(result3.status==0){
						window.location.href='/bts/order/prompt?order_no='+order;
					}else if(result3.status==1){
						showAlert({
                                alertTitle: "温馨提示",
                                alertContent: result3.msg,
                                alertType: 1,
                                singleBtnText: "确定"
                            });
						return false;
					}else{
						showAlert({
                                alertTitle: "温馨提示",
                                alertContent: result3.error,
                                alertType: 1,
                                singleBtnText: "确定"
                            });
						return false;
					}
					
				},'json')	
			}else{
				showAlert({
                   alertTitle: "温馨提示",
                   alertContent: result1.msg,
                   alertType: 1,
                   singleBtnText: "确定"
                   });
				return false;
			}
		},'json')
		
	})
	

	 $("#code").click(function(){
		 if ($(this).html() == '获取验证码') {
			 bd();
		 }else {
			 sms();
		 }
	 });
	
	function bd(){
		 var order= $("#order").val();
		 var voucher_id = $("#voucher_k").val();
		 if(! money || ! order){
				alert('缺少参数')
				return false;
	     }
		 $.post("/bts/order/bdCard",{order:order,voucher_id:voucher_id},function(data){
				if(data.status==0){
					 setTime($("#code"))
					$("#button1").attr('disabled', false);
					$("#button1").attr('style','');
				}else{
				    showAlert({
                                alertTitle: "温馨提示",
                                alertContent: data.error,
                                alertType: 1,
                                singleBtnText: "确定"
                            });
				    return false;
				}
			},'json')
	}

	function sms(){
		 var order= $("#order").val();
		$.post("/cas/bank/sms",{'order_no':order},function(result1){
			if(result1.status==0){
				 setTime($("#code"))
			}else{
				showAlert({
                                alertTitle: "温馨提示",
                                alertContent: result1.msg,
                                alertType: 1,
                                singleBtnText: "确定"
                            });
								return false;
			}
		},'json')
	}
		function setTime(btn) {
		    if (!btn.hasClass('sended')) {
		        btn.addClass('sended').attr('disabled', true);
		        var sendCodeTime = 30;
		        btn.text(sendCodeTime + 'S');
		        var sendCodeInterval;
		        sendCodeInterval = setInterval(function () {
		            if (sendCodeTime > 1 && sendCodeTime <= 30) {
		                sendCodeTime--;
		                btn.text( sendCodeTime + 'S');
		            } else {
		                btn.text('重新发送');
		                clearInterval(sendCodeInterval);
		                btn.removeClass('sended').attr('disabled', false);
		            }
		        }, 1000);
		    };
		};
});

