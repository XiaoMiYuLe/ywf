$(document).ready(function () {
	$(".bankcard_btn").click(function(){
		var owner = trim($("input[name='owner']").val());
		var cert_no = trim($("input[name='cert_no']").val());
		var subbank_name = trim($("input[name='subbank_name']").val());
		var card_no = trim($("input[name='card_no']").val());
		var phone = trim($("input[name='phone']").val());
		var bank = trim($("select[name='bk_name']").val());

		if(! owner ){
			showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入姓名',
                alertType: 1,
                singleBtnText: "确定"
            });
			return false;
		}
		
		if(! cert_no ){
			showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入身份证号码',
                alertType: 1,
                singleBtnText: "确定"
            });
			return false;
		}

		if(bank=='选择银行(必填)'){
			showAlert({
                alertTitle: "温馨提示",
                alertContent: '请选择银行',
                alertType: 1,
                singleBtnText: "确定"
            });
			return false;
		}
		
		
		if(! card_no ){
			showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入银行卡号',
                alertType: 1,
                singleBtnText: "确定"
            });
			return false;
		}
		
		if(! phone ){
			showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入预留手机号码',
                alertType: 1,
                singleBtnText: "确定"
            });
			return false;
		}
		
		function trim(str)

        { 

            return str.replace(/(^\s*)|(\s*$)/g, ""); 

        }
		
		$.post("/cas/bank/dt",{owner:owner,cert_no:cert_no,subbank_name:subbank_name,card_no:card_no,phone:phone},function(data){
			if(data.status==0){
				if(data.data.bank_name=='招商银行'){
					window.location.href="/cas/bank/km?order_no="+data.data.order_no+'&bd='+data.data.bind_id+'&phone='+phone;
				}else{
					telNum=phone.substring(0,3)+'****'+phone.substring(7,11);
					showAlert({
						alertTitle:"付款确认",
						alertContent:"本次交易需要短信验证，验证码将发送到你的手机"+telNum,
						alertType:3,
						doubleBtnText1:"取消",
						doubleBtnText2:"确认",
						goUrl:"javascript:;"
					});
					setTime($('#getCodeBtn'));
					
					$('#getCodeBtn').click(function(){
						$.post("/cas/bank/sms",{'order_no':data.data.order_no},function(result1){
							if(result1.status==0){
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
					
					$('.btnRight').click(function(){
						myCode = $('#myCode').val();
						$.post("/cas/bank/pay",{'order_no':data.data.order_no,'check_code':myCode},function(result2){
							if(result2.status==0){
								$.post("/cas/bank/search",{'order_no':data.data.order_no},function(result3){
									if(result3.status==0){
										showAlert({
                                            alertTitle: "温馨提示",
                                            alertContent: '恭喜您，绑卡成功！',
                                            alertType: 1,
                                            singleBtnText: "确定"
                                        });
										window.location.href='/cas/user/index';
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
                                           alertContent: result2.msg,
                                           alertType: 1,
                                           singleBtnText: "确定"
                                        });                       
						        return false;
							}
						},'json')
					})
				}
			}else{
				showAlert({
                        alertTitle: "温馨提示",
                        alertContent: data.msg,
                        alertType: 1,
                        singleBtnText: "确定"
                    });                       
			    return false;
			}
		},'json')
	})
});

